<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Laporan Harian - <?= htmlspecialchars($formattedDate) ?>
    </title>

    <style>
        @page {
            size: A4 landscape;
            margin-top: 1cm;
            margin-bottom: 1cm;
            margin-left: 1.5cm;
            margin-right: 1.5cm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;

            font-family: Arial, Helvetica, sans-serif;
            font-size: 12pt;

            color: #000;
            background: #fff;
        }


        /*
        |--------------------------------------------------------------------------
        | CONTAINER
        |--------------------------------------------------------------------------
        */

        .container {
            width: 100%;
            margin: 0;
            padding: 0;
        }


        /*
        |--------------------------------------------------------------------------
        | HEADER DOKUMEN
        |--------------------------------------------------------------------------
        */

        .document-header {
            position: relative;

            width: 100%;

            min-height: 60px;

            margin-bottom: 8px;
            margin-top: 8px;
        }


        /*
|--------------------------------------------------------------------------
| HEADER KIRI
|--------------------------------------------------------------------------
*/

        .header-left {
            position: absolute;

            top: 0;
            left: 0;

            width: 42%;
            /* 🔑 pakai persen, bukan 260px */

            text-align: center;

            font-size: 12pt;
            line-height: 1.2;

            text-transform: uppercase;

            white-space: nowrap;
        }

        .header-left .line {
            width: 100%;

            border-bottom: 1px solid #000;

            margin-top: 4px;
        }


        /*
|--------------------------------------------------------------------------
| HEADER KANAN
|--------------------------------------------------------------------------
*/

        .header-right {
            position: absolute;

            top: 0;
            right: 0;

            width: 42%;
            /* 🔑 pakai persen, bukan 260px */

            font-size: 12pt;
            line-height: 1.3;

            text-transform: uppercase;
        }

        .header-right table {
            width: 100%;

            border-collapse: collapse;

            table-layout: fixed;
        }

        .header-right td {
            padding: 0.5px 0;

            vertical-align: top;

            border-bottom: 1px solid #000;
        }

        .header-right .label {
            width: 60%;
            /* 🔑 label proporsional */

            text-align: left;

            white-space: nowrap;
        }

        .header-right .separator {
            width: 5%;
            /* 🔑 separator lebih ramping */

            text-align: center;
        }

        .header-right .value {
            text-align: left;

            white-space: nowrap;
        }


        /*
        |--------------------------------------------------------------------------
        | JUDUL
        |--------------------------------------------------------------------------
        */

        .document-title {
            text-align: center;

            margin-top: 66px;
            margin-bottom: 8px;

            font-size: 12pt;

            font-weight: 700;

            text-transform: uppercase;
        }

        .document-title .title {
            text-decoration: underline;
            display: inline-block;
            padding-bottom: 2px;
        }

        .document-title .meta {
            width: 280px;
            margin: 6px auto 0;
            font-weight: 400;
            line-height: 1.2;
            padding-left: 60px;
            text-align: left;
            font-size: 12pt;
            text-transform: uppercase;
        }

        .document-title .meta>div {
            display: table;

            width: 100%;
        }

        .document-title .meta-label,
        .document-title .meta-separator,
        .document-title .meta-value {
            display: table-cell;
        }

        .document-title .meta-label {
            width: 110px;
            /* 🔑 dari 60px → biar "TANGGAL" & ":" sejajar */
            text-align: left;
        }

        .document-title .meta-separator {
            width: 15px;
            /* 🔑 jarak ":" dari label */
            text-align: center;
        }

        .document-title .meta-value {
            text-align: left;
            white-space: nowrap;
        }


        /*
        |--------------------------------------------------------------------------
        | TABEL LAPORAN
        |--------------------------------------------------------------------------
        */

        .report-table {
            width: 100%;

            border-collapse: collapse;

            table-layout: fixed;

            page-break-inside: auto;

            font-size: 12pt;
            margin-top: 22px;
            margin-bottom: 10px;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #000;

            padding: 8px 10px;

            vertical-align: top;

            word-wrap: break-word;
            overflow-wrap: break-word;

            line-height: 1.4;
        }


        /*
        |--------------------------------------------------------------------------
        | HEADER TABEL
        |--------------------------------------------------------------------------
        */

        .report-table thead {
            display: table-header-group;
        }

        .report-table th {
            text-align: center;

            font-weight: 700;

            vertical-align: middle;

            text-transform: uppercase;

            line-height: 1.05;

            font-size: 12pt;
        }


        /*
        |--------------------------------------------------------------------------
        | NOMOR KOLOM
        |--------------------------------------------------------------------------
        */

        .report-table .column-number {
            font-weight: 400;

            text-align: center;

            padding-top: 2px;
            padding-bottom: 2px;
        }


        /*
        |--------------------------------------------------------------------------
        | LEBAR KOLOM
        |--------------------------------------------------------------------------
        */

        .report-table .no {
            width: 6%;
            text-align: center;
        }

        .report-table .target {
            width: 15%;
        }

        .report-table .activity {
            width: 23%;
        }

        .report-table .personnel {
            width: 13%;
        }

        .report-table .location {
            width: 13%;
        }

        .report-table .pic {
            width: 14%;
        }

        .report-table .result {
            width: 18%;
        }


        /*
        |--------------------------------------------------------------------------
        | ISI TABEL
        |--------------------------------------------------------------------------
        */

        .report-table tbody tr {
            page-break-inside: avoid;

            page-break-after: auto;
        }

        .report-table tbody td {
            min-height: 50px;
        }


        /*
        |--------------------------------------------------------------------------
        | TANDA TANGAN
        |--------------------------------------------------------------------------
        */

        .signature {
            width: 100%;

            margin-top: 25px;

            page-break-inside: avoid;
        }

        .signature-box {
            width: 200px;

            margin-left: auto;

            text-align: center;

            font-size: 12pt;

            line-height: 1.3;
        }

        .signature-space {
            height: 80px;
        }

        .signature-name {
            font-weight: 700;

            text-decoration: underline;

            margin-bottom: 2px;
        }
    </style>

</head>


<body>

    <div class="container">


        <!-- ==========================================
        HEADER DOKUMEN
    =========================================== -->

        <div class="document-header">


            <!-- HEADER KIRI -->

            <div class="header-left">

                <div>
                    KEPOLISIAN NEGARA REPUBLIK INDONESIA
                </div>

                <div>
                    DAERAH JAWA TIMUR
                </div>

                <div>
                    RESOR TUBAN
                </div>

                <div class="line"></div>

            </div>


            <!-- HEADER KANAN -->

            <div class="header-right">

                <table>

                    <tr>

                        <td class="label">
                            LAMPIRAN
                        </td>

                        <td class="separator">
                        </td>

                        <td class="value">
                        </td>

                    </tr>


                    <tr>

                        <td class="label">
                            PETUNJUK PELAKSANAAN KAPOLRI
                        </td>

                        <td class="separator">
                        </td>

                        <td class="value">
                        </td>

                    </tr>


                    <tr>

                        <td class="label">
                            NO.POL
                        </td>

                        <td class="separator">
                            :
                        </td>

                        <td class="value">
                            JUKLAK 02/II/1993
                        </td>

                    </tr>


                    <tr>

                        <td class="label">
                            TANGGAL
                        </td>

                        <td class="separator">
                            :
                        </td>

                        <td class="value">
                            1 FEBRUARI 1993
                        </td>

                    </tr>

                </table>

            </div>

        </div>


        <?php

        $hariIndonesia = [

            'Sunday' => 'Minggu',
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',

        ];


        $bulanIndonesia = [

            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember',

        ];


        $hari = $hariIndonesia[
            $report->reportDate->format('l')
        ];


        $tanggal = $report->reportDate->format('d');


        $bulan = $bulanIndonesia[
            $report->reportDate->format('m')
        ];


        $tahun = $report->reportDate->format('Y');


        $tanggalIndonesia =
            $tanggal . ' ' .
            $bulan . ' ' .
            $tahun;


        // ============================================
        // TANGGAL TTD — kondisional per halaman
        // ============================================
        if (!empty($isSecondPage)) {
            // Halaman 2 (Hasil): tanggal pelaksanaan
            $tanggalTtd = $tanggalIndonesia;
        } else {
            // Halaman 1 (Rencana): tanggal pembuatan report (created_at)
            $created = $report->createdAt;

            $tanggalTtd = $created->format('d') . ' ' .
                $bulanIndonesia[$created->format('m')] . ' ' .
                $created->format('Y');
        }

        ?>


        <!-- ==========================================
        JUDUL
    =========================================== -->

        <div class="document-title">


            <div class="title">
                <?= htmlspecialchars($titleDocument) ?>
            </div>


            <div class="meta">


                <div>

                    <span class="meta-label">
                        HARI
                    </span>

                    <span class="meta-separator">
                        :
                    </span>

                    <span class="meta-value">
                        <?= htmlspecialchars($hari) ?>
                    </span>

                </div>


                <div>

                    <span class="meta-label">
                        TANGGAL
                    </span>

                    <span class="meta-separator">
                        :
                    </span>

                    <span class="meta-value">
                        <?= htmlspecialchars($tanggalIndonesia) ?>
                    </span>

                </div>


            </div>

        </div>


        <!-- ==========================================
        TABEL LAPORAN
    =========================================== -->

        <table class="report-table">


            <colgroup>

                <col class="no">

                <col class="target">

                <col class="activity">

                <col class="personnel">

                <col class="location">

                <col class="pic">

                <col class="result">

            </colgroup>


            <thead>


                <tr>

                    <th class="no">NO</th>
                    <th class="target">SASARAN</th>
                    <th class="activity">KEGIATAN</th>
                    <th class="personnel">KUAT PERS</th>
                    <th class="location">LOKASI</th>
                    <th class="pic">PENANGGUNG<br>JAWAB</th>
                    <th class="result">HASIL YANG<br>INGIN DICAPAI</th>

                </tr>


                <tr>

                    <td class="column-number">1</td>
                    <td class="column-number">2</td>
                    <td class="column-number">3</td>
                    <td class="column-number">4</td>
                    <td class="column-number">5</td>
                    <td class="column-number">6</td>
                    <td class="column-number">7</td>

                </tr>


            </thead>


            <tbody>


                <?php if (!empty($activities)) { ?>


                    <?php foreach ($activities as $data) { ?>


                        <tr>


                            <td class="no">

                                <?= htmlspecialchars(
                                    $data['item']->itemNo ?? '-'
                                ) ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $data['targetName'] ?? '-'
                                ) ?>

                            </td>


                            <td>
                                <?php
                                $activityText = $data['activityName'] ?? '';

                                // 1. Coba split newline dulu
                                $lines = array_filter(
                                    array_map('trim', explode("\n", $activityText)),
                                    fn($line) => $line !== ''
                                );

                                // 2. Kalau cuma 1 baris, coba split dengan nomor "1. 2. 3."
                                if (count($lines) <= 1) {

                                    if (preg_match('/\d+[\.\)]\s/', $activityText)) {

                                        $parts = preg_split(
                                            '/(?=\b\d+[\.\)]\s)/',
                                            $activityText,
                                            -1,
                                            PREG_SPLIT_NO_EMPTY
                                        );

                                        $lines = array_filter(
                                            array_map(function ($part) {
                                                $clean = preg_replace('/^\d+[\.\)]\s*/', '', trim($part));
                                                return trim($clean);
                                            }, $parts),
                                            fn($line) => $line !== ''
                                        );
                                    }
                                }
                                ?>

                                <?php if (!empty($lines)): ?>
                                    <?php if (count($lines) > 1): ?>
                                        <ul style="margin: 0; padding-left: 16px; line-height: 1.3; list-style-type: disc;">
                                            <?php foreach ($lines as $line): ?>
                                                <li><?= htmlspecialchars($line) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <?= htmlspecialchars($lines[0]) ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $data['personnelName'] ?? '-'
                                ) ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $data['locationName'] ?? '-'
                                ) ?>

                            </td>


                            <td>

                                <?= htmlspecialchars(
                                    $data['picName'] ?? '-'
                                ) ?>

                            </td>


                            <td>

                                <?php
                                $expectedText = $data['expectedResultName'] ?? '';

                                // 1. Coba split newline dulu
                                $lines = array_filter(
                                    array_map('trim', explode("\n", $expectedText)),
                                    fn($line) => $line !== ''
                                );

                                // 2. Kalau cuma 1 baris, coba split dengan nomor "1. 2. 3."
                                if (count($lines) <= 1) {

                                    // Cek apakah ada pola "1." atau "1)"
                                    if (preg_match('/\d+[\.\)]\s/', $expectedText)) {

                                        $parts = preg_split(
                                            '/(?=\b\d+[\.\)]\s)/',
                                            $expectedText,
                                            -1,
                                            PREG_SPLIT_NO_EMPTY
                                        );

                                        $lines = array_filter(
                                            array_map(function ($part) {
                                                $clean = preg_replace('/^\d+[\.\)]\s*/', '', trim($part));
                                                return trim($clean);
                                            }, $parts),
                                            fn($line) => $line !== ''
                                        );
                                    }
                                }
                                ?>

                                <?php if (!empty($lines)): ?>
                                    <?php if (count($lines) > 1): ?>
                                        <ul style="margin: 0; padding-left: 16px; line-height: 1.3; list-style-type: disc;">
                                            <?php foreach ($lines as $line): ?>
                                                <li><?= htmlspecialchars($line) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php else: ?>
                                        <?= htmlspecialchars($lines[0]) ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>

                            </td>


                        </tr>


                    <?php } ?>


                <?php } else { ?>


                    <tr>

                        <td colspan="7" style="text-align: center;">

                            Belum ada kegiatan.

                        </td>

                    </tr>


                <?php } ?>


            </tbody>


        </table>


        <!-- ==========================================
        TANDA TANGAN
    =========================================== -->

        <div class="signature">


            <div class="signature-box">


                <div>

                    Tuban,
                    <?= htmlspecialchars($tanggalTtd) ?>

                </div>


                <div>

                    Ps.KASI TIK

                </div>


                <div class="signature-space">
                </div>


                <div class="signature-name">

                    INDRA DHEDY.S

                </div>


                <div>

                    AIPTU NRP 82031270

                </div>


            </div>


        </div>


    </div>

</body>

</html>