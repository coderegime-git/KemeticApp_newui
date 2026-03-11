<?php
namespace App\Services;

use setasign\Fpdi\Fpdi;
use Exception;

class PdfResizerService
{
    private function decompressPdf(string $inputPath): string
    {
        $outputPath = $inputPath . '_decompressed.pdf';

        $cmd = sprintf(
            'gs -dBATCH -dNOPAUSE -sDEVICE=pdfwrite -dCompatibilityLevel=1.4 '
            . '-dNOCOMPRESSTEXT -dNOCOMPRESSFONTS -dFastWebView=false '
            . '-sOutputFile=%s %s',
            \escapeshellarg($outputPath),
            \escapeshellarg($inputPath)
        );

        $process = \proc_open($cmd, [2 => ['pipe', 'w']], $pipes);

        if (!\is_resource($process)) {
            throw new Exception('Failed to start Ghostscript process');
        }

        $stderr = \stream_get_contents($pipes[2]);
        \fclose($pipes[2]);
        $returnCode = \proc_close($process);

        if ($returnCode !== 0 || !\file_exists($outputPath)) {
            throw new Exception('Ghostscript failed: ' . $stderr);
        }

        return $outputPath;
    }

    public function resizeForLulu(string $pdfUrl, bool $fullBleed = false): array
    {
        $tmpDir = public_path('store/lulu/interior');
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0775, true);
        }

        $inputPath  = $tmpDir . '/input_' . time() . '.pdf';
        $outputPath = $tmpDir . '/interior_' . time() . '.pdf';

        // 1️⃣ Download PDF
        $pdfContent = $this->loadPdf($pdfUrl);
        if (!$pdfContent) {
            throw new Exception('Unable to download PDF');
        }
        file_put_contents($inputPath, $pdfContent);

        // 2️⃣ Decompress so FPDI free parser can read it
        $decompressedPath = $this->decompressPdf($inputPath);

        // 3️⃣ Lulu sizes
        if ($fullBleed) {
            $widthPt  = 450; // 6.25"
            $heightPt = 666; // 9.25"
        } else {
            $widthPt  = 432; // 6"
            $heightPt = 648; // 9"
        }

        // 4️⃣ Resize using the DECOMPRESSED file ✅
        $pdf = new Fpdi('P', 'pt', [$widthPt, $heightPt]);
        $pageCount = $pdf->setSourceFile($decompressedPath); // ✅ was $inputPath

        for ($i = 1; $i <= $pageCount; $i++) {
            $tpl  = $pdf->importPage($i);
            $size = $pdf->getTemplateSize($tpl);

            if ($size['width'] > $size['height']) {
                throw new Exception(
                    "Landscape or spread page detected on page {$i}. Lulu requires portrait single pages."
                );
            }

            $pdf->AddPage('P', [$widthPt, $heightPt]);

            $scale = min(
                $widthPt / $size['width'],
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

    public function generateCoverFromPdf(
        string $coverPdfUrl,
        int    $pageCount,
        float  $trimWidth  = 6,
        float  $trimHeight = 9
    ): array {

        $tmpDir = public_path('store/lulu/cover');
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0775, true);
        }

        /* ---------- Lulu Calculations ---------- */
        $spineWidth = $pageCount * 0.002252; // inches
        $bleed      = 0.25;                  // total bleed (0.125 each side)

        $coverWidthIn  = ($trimWidth * 2) + $spineWidth + $bleed;
        $coverHeightIn = $trimHeight + $bleed;

        $widthPt  = $coverWidthIn  * 72;
        $heightPt = $coverHeightIn * 72;

        /* ---------- Download & Decompress ---------- */
        $inputPath  = $tmpDir . '/input_' . time() . '.pdf';
        $outputPath = $tmpDir . '/cover_'  . time() . '.pdf';

        file_put_contents($inputPath, $this->loadPdf($coverPdfUrl));

        // Decompress so FPDI free parser can read it
        $decompressedPath = $this->decompressPdf($inputPath);

        /* ---------- Create Output PDF ---------- */
        $pdf = new Fpdi('L', 'pt', [$widthPt, $heightPt]); // LANDSCAPE
        $pageCountInput = $pdf->setSourceFile($decompressedPath); // ✅ was $inputPath

        if ($pageCountInput < 1) {
            throw new Exception('Cover PDF has no pages');
        }

        // ONLY FIRST PAGE
        $tpl  = $pdf->importPage(1);
        $size = $pdf->getTemplateSize($tpl);

        $pdf->AddPage('L', [$widthPt, $heightPt]);

        $scale = min(
            $widthPt  / $size['width'],
            $heightPt / $size['height']
        );

        $w = $size['width']  * $scale;
        $h = $size['height'] * $scale;
        $x = ($widthPt  - $w) / 2;
        $y = ($heightPt - $h) / 2;

        $pdf->useTemplate($tpl, $x, $y, $w, $h);

        $pdf->Output($outputPath, 'F'); // ✅ fixed argument order (was 'F', $outputPath)

        @unlink($inputPath);
        @unlink($decompressedPath);

        return [
            'local_path' => $outputPath,
            'width_in'   => round($coverWidthIn, 3),
            'height_in'  => round($coverHeightIn, 3),
            'spine_in'   => round($spineWidth, 3),
            'pages'      => $pageCount,
        ];
    }

    private function loadPdf(string $pathOrUrl): string
    {
        if (!filter_var($pathOrUrl, FILTER_VALIDATE_URL)) {
            if (!file_exists($pathOrUrl)) {
                throw new \Exception("Local PDF not found: {$pathOrUrl}");
            }
            return file_get_contents($pathOrUrl);
        }

        $ch = curl_init($pathOrUrl);
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
            throw new \Exception('cURL error: ' . curl_error($ch));
        }
        curl_close($ch);

        if ($status !== 200 || empty($data)) {
            throw new \Exception("Failed to download PDF (HTTP {$status})");
        }

        return $data;
    }
}