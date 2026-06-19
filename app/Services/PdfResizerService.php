<?php
namespace App\Services;

use setasign\Fpdi\Fpdi;
use Exception;
use Illuminate\Support\Facades\Log;

class PdfResizerService
{
    /**
     * Decompress a PDF using Ghostscript so FPDI free parser can read it.
     */
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

        $stderr     = stream_get_contents($pipes[2]);
        fclose($pipes[2]);
        $returnCode = proc_close($process);

        if ($returnCode !== 0 || !file_exists($outputPath)) {
            throw new Exception('Ghostscript decompress failed: ' . $stderr);
        }

        return $outputPath;
    }

    /**
     * Resize an interior PDF to exact Lulu dimensions.
     *
     * No-bleed : exactly 6.00" × 9.00" = 432pt × 648pt
     * Full-bleed: 6.25" × 9.25"        = 450pt × 666pt
     */
    public function resizeForLulu(string $pdfUrl, bool $fullBleed = false): array
    {
        $tmpDir = public_path('store/lulu/interior');
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0775, true);
        }

        $inputPath  = $tmpDir . '/input_'    . time() . '.pdf';
        $outputPath = $tmpDir . '/interior_' . time() . '.pdf';

        $pdfContent = $this->loadPdf($pdfUrl);
        if (!$pdfContent) {
            throw new Exception('Unable to download PDF');
        }
        file_put_contents($inputPath, $pdfContent);

        $decompressedPath = $this->decompressPdf($inputPath);

        if ($fullBleed) {
            $widthPt  = 450;
            $heightPt = 666;
        } else {
            $widthPt  = 432;
            $heightPt = 648;
        }

        $pdf       = new Fpdi('P', 'pt', [$widthPt, $heightPt]);
        $pageCount = $pdf->setSourceFile($decompressedPath);

        Log::info('Interior resize: pageCount=' . $pageCount
            . ' widthPt=' . $widthPt . ' heightPt=' . $heightPt);

        for ($i = 1; $i <= $pageCount; $i++) {
            $tpl  = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tpl);

            $pdf->AddPage('P', [$widthPt, $heightPt]);

            $scale = min(
                $widthPt  / $size['width'],
                $heightPt / $size['height']
            );

            $w = $size['width']  * $scale;
            $h = $size['height'] * $scale;
            $x = ($widthPt  - $w) / 2;
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
     * Formula:
     *   bleed       = 0.125" on every edge
     *   spineWidth  = interiorPageCount × 0.002252"  (60 lb stock)
     *   coverWidth  = (trimWidth × 2) + spineWidth + (bleed × 2)
     *   coverHeight = trimHeight + (bleed × 2) = 9.25" always in range
     *
     * ROTATION STRATEGY (two-step)
     * ─────────────────────────────
     * The source PDF is a portrait wrap cover (tall).
     * The Lulu canvas must be landscape (wide).
     *
     * WHY THE PS PREAMBLE APPROACH FAILED
     * ─────────────────────────────────────
     * Ghostscript processes the source PDF's embedded page dictionary AFTER
     * the preamble, so it re-applies the original portrait page size and
     * overrides the preamble rotation. The content never actually rotates.
     *
     * THE FIX — two explicit steps:
     *   Step 1 — qpdf --rotate=-90
     *            Physically rewrites the PDF page dictionary so the page
     *            IS landscape before Ghostscript ever opens the file.
     *            No scaling, no distortion — pure rotation.
     *
     *   Step 2 — Ghostscript -dFIXEDMEDIA -dPDFFitPage -dAutoRotatePages=/None
     *            Scales the now-landscape source to fill the exact Lulu
     *            canvas dimensions without any further rotation.
     *
     * @param  string  $coverPdfUrl  URL or local path to the cover PDF (1 page).
     * @param  int     $pageCount    Number of INTERIOR pages (drives spine width).
     * @param  float   $trimWidth    Trim width in inches  (default 6.0).
     * @param  float   $trimHeight   Trim height in inches (default 9.0).
     */
    public function generateCoverFromPdf(
        string $coverPdfUrl,
        int    $pageCount,
        float  $trimWidth  = 6.0,
        float  $trimHeight = 9.0
    ): array {

        Log::info('generateCoverFromPdf called with pageCount=' . $pageCount);

        if ($pageCount <= 0) {
            throw new Exception('Invalid page count: ' . $pageCount);
        }

        $tmpDir = public_path('store/lulu/cover');
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0775, true);
        }

        /* ── Lulu exact dimensions ── */
        $spineWidth    = $pageCount * 0.002252;
        $bleed         = 0.125;

        $coverWidthIn  = ($trimWidth * 2) + $spineWidth + ($bleed * 2);
        $coverHeightIn = $trimHeight + ($bleed * 2);

        $widthPt  = $coverWidthIn  * 72;
        $heightPt = $coverHeightIn * 72;

        /* ── Validation warnings ── */
        $minW = 13.245; $maxW = 13.370;
        $minH = 9.188;  $maxH = 9.312;

        if ($coverWidthIn < $minW || $coverWidthIn > $maxW) {
            Log::warning(sprintf(
                'Cover width %.4f" is OUTSIDE Lulu range [%.3f"–%.3f"]. '
                . 'Interior page count=%d requires spine=%.4f". '
                . 'For a 6×9 trim you need roughly 442–497 interior pages.',
                $coverWidthIn, $minW, $maxW, $pageCount, $spineWidth
            ));
        }

        if ($coverHeightIn < $minH || $coverHeightIn > $maxH) {
            Log::warning(sprintf(
                'Cover height %.4f" is OUTSIDE Lulu range [%.3f"–%.3f"]. '
                . 'Check trim height (%.2f").',
                $coverHeightIn, $minH, $maxH, $trimHeight
            ));
        }

        Log::info(sprintf(
            'Cover calc: interiorPages=%d spine=%.4f" coverWidth=%.4f" (%.2fpt) coverHeight=%.4f" (%.2fpt)',
            $pageCount, $spineWidth, $coverWidthIn, $widthPt, $coverHeightIn, $heightPt
        ));

        /* ── File paths ── */
        $ts          = time();
        $inputPath   = $tmpDir . '/input_'   . $ts . '.pdf';
        $rotatedPath = $tmpDir . '/rotated_' . $ts . '.pdf';
        $outputPath  = $tmpDir . '/cover_'   . $ts . '.pdf';

        file_put_contents($inputPath, $this->loadPdf($coverPdfUrl));

        /* ── Log source PDF dimensions ── */
        $infoCmd = sprintf(
            'gs -dBATCH -dNOPAUSE -dQUIET -sDEVICE=nullpage -dPDFINFO %s 2>&1',
            escapeshellarg($inputPath)
        );
        $infoProc = proc_open($infoCmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $infoPipes);
        if (is_resource($infoProc)) {
            $infoOut = stream_get_contents($infoPipes[1]) . stream_get_contents($infoPipes[2]);
            fclose($infoPipes[1]);
            fclose($infoPipes[2]);
            proc_close($infoProc);
            Log::info('Source cover PDF info: ' . $infoOut);
        }

        /* ── Step 1: Rotate 90° CCW with qpdf ───────────────────────────────
         *
         * --rotate=-90 rewrites the page dictionary to landscape.
         * This is a lossless operation — no pixel data is changed.
         * ─────────────────────────────────────────────────────────────── */
        $rotateCmd = sprintf(
            'qpdf --rotate=+90 %s %s 2>&1',
            escapeshellarg($inputPath),
            escapeshellarg($rotatedPath)
        );

        Log::info('Cover rotate cmd: ' . $rotateCmd);
        $rotateProc = proc_open($rotateCmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $rotatePipes);
        if (!is_resource($rotateProc)) {
            throw new Exception('Failed to start qpdf rotation process');
        }
        $rotateOut  = stream_get_contents($rotatePipes[1]) . stream_get_contents($rotatePipes[2]);
        fclose($rotatePipes[1]);
        fclose($rotatePipes[2]);
        $rotateCode = proc_close($rotateProc);
        Log::info('Cover rotate output: ' . $rotateOut);

        if ($rotateCode !== 0 || !file_exists($rotatedPath)) {
            throw new Exception('qpdf rotation failed (exit ' . $rotateCode . '): ' . $rotateOut);
        }

        /* ── Step 2: Fit rotated landscape PDF onto Lulu canvas ─────────────
         *
         * -dFIXEDMEDIA          : force output page to DEVICEWIDTH/HEIGHT points.
         * -dPDFFitPage          : scale source content to fill fixed canvas.
         * -dAutoRotatePages=/None : prevent GS from rotating again.
         * ─────────────────────────────────────────────────────────────── */
        $gsCmd = sprintf(
            'gs -dBATCH -dNOPAUSE -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 '
            . '-dFIXEDMEDIA -dPDFFitPage -dAutoRotatePages=/None '
            . '-dDEVICEWIDTHPOINTS=%F -dDEVICEHEIGHTPOINTS=%F '
            . '-sOutputFile=%s %s 2>&1',
            $widthPt,
            $heightPt,
            escapeshellarg($outputPath),
            escapeshellarg($rotatedPath)
        );

        Log::info('Cover GS cmd: ' . $gsCmd);

        $process = proc_open($gsCmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);

        if (!is_resource($process)) {
            throw new Exception('Failed to start Ghostscript');
        }

        $stdout     = stream_get_contents($pipes[1]);
        $stderr     = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $returnCode = proc_close($process);

        Log::info('Cover GS stdout: ' . $stdout);
        Log::info('Cover GS stderr: ' . $stderr);

        if ($returnCode !== 0 || !file_exists($outputPath)) {
            throw new Exception('Ghostscript cover resize failed: ' . $stderr);
        }

        @unlink($inputPath);
        @unlink($rotatedPath);

        return [
            'local_path' => $outputPath,
            'width_in'   => round($coverWidthIn,  4),
            'height_in'  => round($coverHeightIn, 4),
            'spine_in'   => round($spineWidth,    4),
            'pages'      => $pageCount,
        ];
    }

    /**
     * Resize a cover PDF to explicit point dimensions.
     * Uses the same two-step qpdf rotate + Ghostscript fit approach.
     *
     * @param  string  $coverPdfUrl  URL or local path to the cover PDF.
     * @param  float   $widthPt      Target canvas width  in points.
     * @param  float   $heightPt     Target canvas height in points.
     */
    public function generateCoverFromDimensions(
        string $coverPdfUrl,
        float  $widthPt,
        float  $heightPt
    ): array {

        Log::info('generateCoverFromDimensions called with widthPt=' . $widthPt . ' heightPt=' . $heightPt);

        $tmpDir = public_path('store/lulu/cover');
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0775, true);
        }

        $ts          = time();
        $inputPath   = $tmpDir . '/input_'   . $ts . '.pdf';
        $rotatedPath = $tmpDir . '/rotated_' . $ts . '.pdf';
        $outputPath  = $tmpDir . '/cover_'   . $ts . '.pdf';

        file_put_contents($inputPath, $this->loadPdf($coverPdfUrl));

        /* ── Step 1: Rotate 90° CCW with qpdf ── */
        $rotateCmd = sprintf(
            'qpdf --rotate=+90 %s %s 2>&1',
            escapeshellarg($inputPath),
            escapeshellarg($rotatedPath)
        );

        Log::info('Cover rotate cmd: ' . $rotateCmd);
        $rotateProc = proc_open($rotateCmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $rotatePipes);
        if (!is_resource($rotateProc)) {
            throw new Exception('Failed to start qpdf rotation process');
        }
        $rotateOut  = stream_get_contents($rotatePipes[1]) . stream_get_contents($rotatePipes[2]);
        fclose($rotatePipes[1]);
        fclose($rotatePipes[2]);
        $rotateCode = proc_close($rotateProc);
        Log::info('Cover rotate output: ' . $rotateOut);

        if ($rotateCode !== 0 || !file_exists($rotatedPath)) {
            throw new Exception('qpdf rotation failed (exit ' . $rotateCode . '): ' . $rotateOut);
        }

        /* ── Step 2: Fit onto exact canvas ── */
        $gsCmd = sprintf(
            'gs -dBATCH -dNOPAUSE -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 '
            . '-dFIXEDMEDIA -dPDFFitPage -dAutoRotatePages=/None '
            . '-dDEVICEWIDTHPOINTS=%F -dDEVICEHEIGHTPOINTS=%F '
            . '-sOutputFile=%s %s 2>&1',
            $widthPt,
            $heightPt,
            escapeshellarg($outputPath),
            escapeshellarg($rotatedPath)
        );

        Log::info('Cover Dimensions GS cmd: ' . $gsCmd);

        $process = proc_open($gsCmd, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);

        if (!is_resource($process)) {
            throw new Exception('Failed to start Ghostscript');
        }

        $stdout     = stream_get_contents($pipes[1]);
        $stderr     = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $returnCode = proc_close($process);

        Log::info('Cover GS stdout: ' . $stdout);
        Log::info('Cover GS stderr: ' . $stderr);

        if ($returnCode !== 0 || !file_exists($outputPath)) {
            throw new Exception('Ghostscript cover resize failed: ' . $stderr);
        }

        @unlink($inputPath);
        @unlink($rotatedPath);

        return [
            'local_path' => $outputPath,
            'width_pt'   => $widthPt,
            'height_pt'  => $heightPt,
        ];
    }

    /**
     * Download a PDF from a URL or read it from a local path.
     */
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
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        ]);

        $data   = curl_exec($ch);
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