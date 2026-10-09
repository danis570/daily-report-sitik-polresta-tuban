<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class AttendancePdfService
{
    public function generate(string $html): string
    {
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');   // ← GANTI

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }
}