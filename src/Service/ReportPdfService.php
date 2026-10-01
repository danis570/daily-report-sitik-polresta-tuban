<?php

namespace Unirow2026\DailyReportSitikPolrestaTuban\Service;

use Dompdf\Dompdf;
use Dompdf\Options;

class ReportPdfService
{
    public function generate(string $html): string
    {
        $options = new Options();

        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Times New Roman');

        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'landscape');

        $dompdf->render();

        return $dompdf->output();
    }
}