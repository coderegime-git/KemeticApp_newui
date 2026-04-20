<?php
namespace App\Services;

use setasign\Fpdi\Fpdi;
use Exception;
use Illuminate\Support\Facades\Log;

class PdfResizerService
{
    private function decompressPdf(string $inputPath): string
    {
        $outputPath = $inputPath . '_decompressed.pdf';

        $cmd = sprintf(
            'gs -dBATCH -dNOPAUSE -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 '
            . '-dNOCOMPRESSTEXT -dNOCOMPRESSFONTS -dFastWebView=false '
            . '-sOutputFile=%s %s',
            escapeshellarg($outputPath),
            escapeshellarg($inputPath)
        );

        $process = proc_open($cmd, [2 => ['pipe', 'w']], $pipes);

        if (!is_resource($process)) {
            throw new Exception('Failed to start Ghostscript process');
        }

        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        $returnCode = proc_close($process);

        if ($returnCode !== 0 || !file_exists($outputPath)) {
            throw new Exception('Ghostscript decompress failed: ' . $stderr);
        }

        return $outputPath;
    }

    public function resizeForLulu(string $pdfUrl, bool $fullBleed = false): array
    {
        $tmpDir = public_path('store/lulu/interior');
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0775, true);
        }

        $inputPath = $tmpDir . '/input_' . time() . '.pdf';
        $outputPath = $tmpDir . '/interior_' . time() . '.pdf';

        // 1️⃣ Download PDF
        $pdfContent = $this->loadPdf($pdfUrl);
        if (!$pdfContent) {
            throw new Exception('Unable to download PDF');
        }
        file_put_contents($inputPath, $pdfContent);

        // 2️⃣ Decompress so FPDI free parser can read it
        $decompressedPath = $this->decompressPdf($inputPath);

        // 3️⃣ Lulu interior sizes
        //    No-bleed : exactly 6.00" × 9.00" = 432pt × 648pt
        //    Full-bleed: 6.00" + 0.125" each side = 6.25" × 9.25" = 450pt × 666pt
        if ($fullBleed) {
            $widthPt = 450; // 6.25"
            $heightPt = 666; // 9.25"
        } else {
            $widthPt = 432; // 6.00"
            $heightPt = 648; // 9.00"
        }

        // 4️⃣ Resize using FPDI — scales each source page to fill the target canvas
        $pdf = new Fpdi('P', 'pt', [$widthPt, $heightPt]);
        $pageCount = $pdf->setSourceFile($decompressedPath);

        Log::info('Interior resize: pageCount=' . $pageCount
            . ' widthPt=' . $widthPt . ' heightPt=' . $heightPt);

        for ($i = 1; $i <= $pageCount; $i++) {
            $tpl = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tpl);

            $pdf->AddPage('P', [$widthPt, $heightPt]);

            $scale = min(
                $widthPt / $size['width'],
                $heightPt / $size['height']
            );

            $w = $size['width'] * $scale;
            $h = $size['height'] * $scale;
            $x = ($widthPt - $w) / 2;
            $y = ($heightPt - $h) / 2;

            $pdf->useTemplate($tpl, $x, $y, $w, $h);
        }

        $pdf->Output($outputPath, 'F');

        @unlink($inputPath);
        @unlink($decompressedPath);

        return [
            'page_count' => $pageCount,
            'local_path' => $outputPath,
        ];
    }

    /**
     * Resize a cover PDF to exact Lulu wrap-cover dimensions.
     *
     * HOW LULU COVER DIMENSIONS WORK
     * ───────────────────────────────
     * Lulu's required range for a 6" × 9" book:
     *   Width : 13.245" – 13.370"
     *   Height: 9.188"  – 9.312"
     *
     * The formula is:
     *   bleed       = 0.125" on every edge (Lulu standard)
     *   spineWidth  = interiorPageCount × 0.002252"  (60 lb white/cream stock)
     *   coverWidth  = (trimWidth × 2) + spineWidth + (bleed × 2)
     *               = (6 × 2) + spineWidth + 0.25
     *               = 12.25 + spineWidth
     *   coverHeight = trimHeight + (bleed × 2) = 9 + 0.25 = 9.25" ✓ always in range
     *
     * ⚠️  $interiorPageCount is the number of INTERIOR pages, NOT the cover page count.
     *     The cover PDF is always 1 physical page; it is $interiorPageCount that
     *     determines the spine width and therefore the total cover width.
     *
     * WHY THE OLD CODE FAILED
     * ────────────────────────
     * 1. The method was commented out and never ran.
     * 2. It used -dPDFFitPage which SCALES content to fill the canvas.
     *    Because source (12.25" × 9.25") and target share the same height,
     *    Ghostscript's fit-by-height kept the width at 12.25" — never growing it.
     * 3. The correct operation is to EXPAND the canvas (add spine gap in the centre)
     *    and TRANSLATE the content, not scale it.
     *
     * WHAT THIS FIX DOES
     * ───────────────────
     * • Builds the exact target canvas: (12.25 + spineWidth)" × 9.25"
     * • Uses Ghostscript -dFIXEDMEDIA (WITHOUT -dPDFFitPage) to set the new size.
     * • Adds a PostScript translate preamble to shift the existing artwork right
     *   by (spineWidth / 2) points so it is centred on the wider canvas — the
     *   spine gap naturally falls in the middle between back and front covers.
     * • Does NOT scale the content; only the canvas changes.
     *
     * @param  string  $coverPdfUrl       URL or local path to the cover PDF (1 page).
     * @param  int     $interiorPageCount Number of interior pages (drives spine width).
     * @param  float   $trimWidth         Trim width in inches  (default 6.0).
     * @param  float   $trimHeight        Trim height in inches (default 9.0).
     */
    public function generateCoverFromPdf(
        string $coverPdfUrl,
        int $pageCount,
        float $trimWidth = 6.0,
        float $trimHeight = 9.0
    ): array {

        Log::info('generateCoverFromPdf called with pageCount=' . $pageCount);
        if ($pageCount <= 0) {
            throw new Exception('Invalid page count: ' . $pageCount);
        }

        $tmpDir = public_path('store/lulu/cover');
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0775, true);
        }

        /* ---------- Lulu Exact Dimensions ---------- */
        $spineWidth = $pageCount * 0.002252;
        $bleed = 0.125; // each side

        $coverWidthIn = ($trimWidth * 2) + $spineWidth + ($bleed * 2);
        $coverHeightIn = $trimHeight + ($bleed * 2);

        $widthPt = $coverWidthIn * 72;
        $heightPt = $coverHeightIn * 72;

        $spineWidthPt = $spineWidth * 72;
        $xOffsetPt = $spineWidthPt / 2.0;

        $minW = 13.245;
        $maxW = 13.370;
        $minH = 9.188;
        $maxH = 9.312;

        if ($coverWidthIn < $minW || $coverWidthIn > $maxW) {
            Log::warning(sprintf(
                'Cover width %.4f" is OUTSIDE Lulu range [%.3f"–%.3f"]. '
                . 'Interior page count=%d requires spine=%.4f". '
                . 'For a 6×9 trim you need roughly 442–497 interior pages.',
                $coverWidthIn,
                $minW,
                $maxW,
                $pageCount,
                $spineWidth
            ));
        }

        if ($coverHeightIn < $minH || $coverHeightIn > $maxH) {
            Log::warning(sprintf(
                'Cover height %.4f" is OUTSIDE Lulu range [%.3f"–%.3f"]. '
                . 'Check trim height (%.2f").',
                $coverHeightIn,
                $minH,
                $maxH,
                $trimHeight
            ));
        }

        // Log::info(sprintf(
        //     'Cover calc: interiorPages=%d spine=%.4f" '
        //     . 'coverWidth=%.4f" (%.2fpt) coverHeight=%.4f" (%.2fpt) '
        //     . 'xOffset=%.4fpt',
        //     $pageCount, $spineWidth,
        //     $coverWidthIn,  $widthPt,
        //     $coverHeightIn, $heightPt,
        //     $xOffsetPt
        // ));

        /* ---------- Download ---------- */
        $ts = time();
        $inputPath = $tmpDir . '/input_' . $ts . '.pdf';
        $outputPath = $tmpDir . '/cover_' . $ts . '.pdf';

        file_put_contents($inputPath, $this->loadPdf($coverPdfUrl));

        /* ---------- Get source PDF dimensions first ---------- */
        $infoCmd = sprintf(
            'gs -dBATCH -dNOPAUSE -dQUIET -sDEVICE=nullpage -dPDFINFO %s 2>&1',
            escapeshellarg($inputPath)
        );
        $infoOut = proc_open($infoCmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $infoPipes);
        $infoStr = '';
        if (is_resource($infoOut)) {
            $infoStr = stream_get_contents($infoPipes[1]) . stream_get_contents($infoPipes[2]);
            fclose($infoPipes[1]);
            fclose($infoPipes[2]);
            proc_close($infoOut);
        }
        Log::info('Source cover PDF info: ' . $infoStr);

        /* ---------- Ghostscript: force EXACT output size ---------- */
        // -dFIXEDMEDIA + -dPDFFitPage = stretch/fit source into exact target box
        $cmd = sprintf(
            'gs -dBATCH -dNOPAUSE -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 '
            . '-dFIXEDMEDIA -dPDFFitPage '
            . '-dDEVICEWIDTHPOINTS=%F -dDEVICEHEIGHTPOINTS=%F '
            . '-sOutputFile=%s %s 2>&1',
            $widthPt,
            $heightPt,
            escapeshellarg($outputPath),
            escapeshellarg($inputPath)
        );

        Log::info('Cover GS cmd: ' . $cmd);

        $process = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        if (!is_resource($process)) {
            throw new Exception('Failed to start Ghostscript');
        }

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $returnCode = proc_close($process);

        Log::info('Cover GS stdout: ' . $stdout);
        Log::info('Cover GS stderr: ' . $stderr);

        if ($returnCode !== 0 || !file_exists($outputPath)) {
            throw new Exception('Ghostscript cover resize failed: ' . $stderr);
        }

        @unlink($inputPath);

        return [
            'local_path' => $outputPath,
            'width_in' => round($coverWidthIn, 4),
            'height_in' => round($coverHeightIn, 4),
            'spine_in' => round($spineWidth, 4),
            'pages' => $pageCount,
        ];
    }

    public function generateCoverFromDimensions(
        string $coverPdfUrl,
        float $widthPt,
        float $heightPt
    ): array {
        Log::info('generateCoverFromDimensions called with widthPt=' . $widthPt . ' heightPt=' . $heightPt);

        $tmpDir = public_path('store/lulu/cover');
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0775, true);
        }

        $ts = time();
        $inputPath = $tmpDir . '/input_' . $ts . '.pdf';
        $outputPath = $tmpDir . '/cover_' . $ts . '.pdf';

        file_put_contents($inputPath, $this->loadPdf($coverPdfUrl));

        $cmd = sprintf(
            'gs -dBATCH -dNOPAUSE -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 '
            . '-dFIXEDMEDIA -dPDFFitPage '
            . '-dDEVICEWIDTHPOINTS=%F -dDEVICEHEIGHTPOINTS=%F '
            . '-sOutputFile=%s %s 2>&1',
            $widthPt,
            $heightPt,
            escapeshellarg($outputPath),
            escapeshellarg($inputPath)
        );

        Log::info('Cover Dimensions GS cmd: ' . $cmd);

        $process = proc_open($cmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
        if (!is_resource($process)) {
            throw new Exception('Failed to start Ghostscript');
        }

        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $returnCode = proc_close($process);

        Log::info('Cover GS stdout: ' . $stdout);
        Log::info('Cover GS stderr: ' . $stderr);

        if ($returnCode !== 0 || !file_exists($outputPath)) {
            throw new Exception('Ghostscript cover resize failed: ' . $stderr);
        }

        @unlink($inputPath);

        return [
            'local_path' => $outputPath,
            'width_pt' => $widthPt,
            'height_pt' => $heightPt,
        ];
    }

    

    private function loadPdf(string $pathOrUrl): string
    {
        $pathOrUrl = preg_replace_callback('/https?:\/\/[^\s]+/', function ($m) {
            return str_replace(' ', '%20', $m[0]);
        }, $pathOrUrl);

        $encoded = str_replace(' ', '%20', $pathOrUrl);

        if (!filter_var($encoded, FILTER_VALIDATE_URL)) {
            if (!file_exists($pathOrUrl)) {
                throw new Exception("Local PDF not found: {$pathOrUrl}");
            }
            return file_get_contents($pathOrUrl);
        }

        $ch = curl_init($encoded);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $data = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if (curl_errno($ch)) {
            throw new Exception('cURL error: ' . curl_error($ch));
        }
        curl_close($ch);

        if ($status !== 200 || empty($data)) {
            throw new Exception("Failed to download PDF (HTTP {$status})");
        }

        return $data;
    }
}