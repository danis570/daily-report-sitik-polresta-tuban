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

            font-family: "Times New Roman", Times, serif;
            font-size: 10pt;

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
        }


        /*
        |--------------------------------------------------------------------------
        | HEADER KIRI
        |--------------------------------------------------------------------------
        | Lebar 260px agar "KEPOLISIAN NEGARA REPUBLIK INDONESIA" muat 1 baris
        | Font 8pt agar hemat ruang
        */

        .header-left {
            position: absolute;

            top: 0;
            left: 0;

            width: 260px;

            text-align: center;

            font-size: 8pt;
            line-height: 1.2;

            text-transform: uppercase;

            white-space: nowrap;   /* 🔑 jangan wrap */
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
        | Lebar 260px, font 7pt, label 155px, value nowrap
        */

        .header-right {
            position: absolute;

            top: 0;
            right: 0;

            width: 260px;

            font-size: 7pt;
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
            width: 155px;

            text-align: left;

            white-space: nowrap;   /* 🔑 jangan wrap */
        }

        .header-right .separator {
            width: 12px;

            text-align: center;
        }

        .header-right .value {
            text-align: left;

            white-space: nowrap;   /* 🔑 jangan wrap */
        }


        /*
        |--------------------------------------------------------------------------
        | JUDUL
        |--------------------------------------------------------------------------
        */

        .document-title {
            text-align: center;

            margin-top: 4px;
            margin-bottom: 8px;

            font-size: 9pt;

            font-weight: 700;

            text-transform: uppercase;
        }

        .document-title .title {
            text-decoration: underline;
        }

        .document-title .meta {
            width: 220px;

            margin: 2px auto 0;

            font-weight: 400;

            line-height: 1.3;

            text-align: left;

            font-size: 9pt;

            text-transform: uppercase;
        }

        .document-title .meta > div {
            display: table;

            width: 100%;
        }

        .document-title .meta-label,
        .document-title .meta-separator,
        .document-title .meta-value {
            display: table-cell;
        }

        .document-title .meta-label {
            width: 60px;

            text-align: left;
        }

        .document-title .meta-separator {
            width: 12px;

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

            font-size: 9pt;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #000;

            padding: 3px 4px;

            vertical-align: top;

            word-wrap: break-word;
            overflow-wrap: break-word;

            line-height: 1.1;
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

            font-size: 9pt;
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
            width: 4%;
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

            margin-top: 15px;

            page-break-inside: avoid;
        }

        .signature-box {
            width: 200px;

            margin-left: auto;

            text-align: center;

            font-size: 9pt;

            line-height: 1.3;
        }

        .signature-space {
            height: 50px;
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

    ?>


    <!-- ==========================================
        JUDUL
    =========================================== -->

    <div class="document-title">


        <div class="title">

            RENCANA KEGIATAN HARIAN SIE TIK POLRES TUBAN

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

                            <?= htmlspecialchars(
                                $data['activityName'] ?? '-'
                            ) ?>

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

                            <?= htmlspecialchars(
                                $data['expectedResultName'] ?? '-'
                            ) ?>

                        </td>


                    </tr>


                <?php } ?>


            <?php } else { ?>


                <tr>

                    <td
                        colspan="7"
                        style="text-align: center;"
                    >

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
                <?= htmlspecialchars($tanggalIndonesia) ?>

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