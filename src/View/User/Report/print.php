<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        Laporan Harian - <?= htmlspecialchars($formattedDate) ?>
    </title>

    <style>
        @page {
            size: A4 landscape;
            margin: 2.4cm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: "Times New Roman", Times, serif;
            font-size: 12pt;
            color: #000;
            background: #e5e5e5;
        }

        .container {
            width: 297mm;
            min-height: 210mm;

            margin: 20px auto;

            padding: 2.4cm;

            box-sizing: border-box;

            background: #fff;
        }

        /* =========================
           TOMBOL PRINT
        ========================= */

        .print-button {
            margin-bottom: 20px;
        }

        .print-button button {
            padding: 10px 16px;
            border: 2px solid #000;
            background: #00d982;
            font-weight: 700;
            cursor: pointer;
        }


        /* =========================
           HEADER
        ========================= */

        .document-header {
            position: relative;
            width: 100%;
            min-height: 72px;
            margin-bottom: 12px;
        }


        /* HEADER KIRI */

        .header-left {
            position: absolute;
            top: 0;
            left: 0;

            width: 210px;

            text-align: center;

            font-size: 10px;
            line-height: 1.15;

            text-transform: uppercase;
        }

        .header-left .line {
            width: 100%;
            border-bottom: 1px solid #000;
            margin-top: 5px;
        }


        /* HEADER KANAN */

        .header-right {
            position: absolute;
            top: 0;
            right: 0;

            width: 260px;

            font-size: 9px;
            line-height: 1.2;

            text-transform: uppercase;
        }

        .header-right table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-right td {
            padding: 1px 0;
            vertical-align: top;
            border-bottom: 1px solid #000;
        }

        .header-right .label {
            width: 145px;
            text-align: left;
            white-space: nowrap;
        }

        .header-right .separator {
            width: 15px;
            text-align: center;
        }

        .header-right .value {
            text-align: left;
            white-space: nowrap;
        }


        /* =========================
           JUDUL
        ========================= */

        .document-title {
            text-align: center;

            margin-top: 5px;
            margin-bottom: 12px;

            font-size: 12px;
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
        }

        .document-title .meta>div {
            display: grid;

            grid-template-columns: 60px 15px 1fr;

            align-items: baseline;
        }

        .document-title .meta-label {
            text-align: left;
        }

        .document-title .meta-separator {
            text-align: center;
        }


        /* =========================
           TABEL LAPORAN
        ========================= */

        .report-table {
            width: 100%;

            border-collapse: collapse;

            table-layout: fixed;

            page-break-inside: auto;
        }

        .report-table th,
        .report-table td {
            border: 1px solid #000;

            padding: 5px;

            vertical-align: top;

            word-wrap: break-word;
            overflow-wrap: break-word;

            line-height: 1.15;
        }


        /* HEADER TABEL */

        .report-table thead {
            display: table-header-group;
        }

        .report-table th {
            text-align: center;

            font-weight: 700;

            vertical-align: middle;

            text-transform: uppercase;

            line-height: 1.05;
        }


        /* BARIS NOMOR KOLOM */

        .report-table .column-number {
            font-weight: 400;

            text-align: center;

            padding-top: 3px;
            padding-bottom: 3px;
        }


        /* =========================
           LEBAR KOLOM
        ========================= */

        .report-table .no {
            width: 4%;
            text-align: center;
        }

        .report-table .target {
            width: 17%;
        }

        .report-table .activity {
            width: 21%;
        }

        .report-table .personnel {
            width: 12%;
        }

        .report-table .location {
            width: 11%;
        }

        .report-table .pic {
            width: 13%;
        }

        .report-table .result {
            width: 18%;
        }

        .report-table .remarks {
            width: 8%;
        }


        /* =========================
           ISI TABEL
        ========================= */

        .report-table tbody tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        .report-table tbody td {
            min-height: 70px;
        }


        /* =========================
           TANDA TANGAN
        ========================= */

        .signature {
            width: 100%;

            margin-top: 25px;

            page-break-inside: avoid;
        }

        .signature-box {
            width: 220px;

            margin-left: auto;

            text-align: center;

            font-size: 10px;
            line-height: 1.25;
        }

        .signature-space {
            height: 65px;
        }

        .signature-name {
            font-weight: 700;

            text-decoration: underline;

            margin-bottom: 2px;
        }


        /* =========================
           PRINT
        ========================= */

        @media print {

            body {
                margin: 0;
                padding: 0;

                font-family: "Times New Roman", Times, serif;
                font-size: 10pt;

                background: #fff;
            }

            .print-button {
                display: none;
            }

            .container {
                width: auto;
                min-height: auto;

                margin: 0;
                padding: 0;

                background: #fff;
            }

            .document-header {
                margin-bottom: 12px;
            }

            .document-title {
                margin-bottom: 12px;
            }

            .report-table th,
            .report-table td {
                padding: 5px;
            }

            .report-table thead {
                display: table-header-group;
            }

            .report-table tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            .signature {
                page-break-inside: avoid;
            }
        }
    </style>

</head>


<body>


    <div class="container">


        <!-- ==========================================
            TOMBOL PRINT
        =========================================== -->

        <div class="print-button">

            <button onclick="window.print()">
                🖨 Cetak / Print
            </button>

        </div>


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

        $tanggalIndonesia = $tanggal . ' ' . $bulan . ' ' . $tahun;

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

                    <span>
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

                    <span>
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
                <col class="remarks">

            </colgroup>


            <thead>


                <!-- JUDUL KOLOM -->

                <tr>

                    <th class="no">
                        NO
                    </th>

                    <th class="target">
                        SASARAN
                    </th>

                    <th class="activity">
                        KEGIATAN
                    </th>

                    <th class="personnel">
                        KUAT PERS
                    </th>

                    <th class="location">
                        LOKASI
                    </th>

                    <th class="pic">
                        PENANGGUNG<br>
                        JAWAB
                    </th>

                    <th class="result">
                        HASIL YANG<br>
                        INGIN DICAPAI
                    </th>

                    <th class="remarks">
                        KET
                    </th>

                </tr>


                <!-- NOMOR KOLOM -->

                <tr>

                    <td class="column-number">
                        1
                    </td>

                    <td class="column-number">
                        2
                    </td>

                    <td class="column-number">
                        3
                    </td>

                    <td class="column-number">
                        4
                    </td>

                    <td class="column-number">
                        5
                    </td>

                    <td class="column-number">
                        6
                    </td>

                    <td class="column-number">
                        7
                    </td>

                    <td class="column-number">
                        8
                    </td>

                </tr>


            </thead>


            <tbody>


                <?php if (!empty($activities)) { ?>


                    <?php foreach ($activities as $data) { ?>


                        <tr>


                            <!-- NO -->

                            <td class="no">

                                <?= htmlspecialchars(
                                    $data['item']->itemNo ?? '-'
                                ) ?>

                            </td>


                            <!-- SASARAN -->

                            <td>

                                <?= htmlspecialchars(
                                    $data['targetName'] ?? '-'
                                ) ?>

                            </td>


                            <!-- KEGIATAN -->

                            <td>

                                <?= htmlspecialchars(
                                    $data['activityName'] ?? '-'
                                ) ?>

                            </td>


                            <!-- KUAT PERSONEL -->

                            <td>

                                <?= htmlspecialchars(
                                    $data['personnelName'] ?? '-'
                                ) ?>

                            </td>


                            <!-- LOKASI -->

                            <td>

                                <?= htmlspecialchars(
                                    $data['locationName'] ?? '-'
                                ) ?>

                            </td>


                            <!-- PENANGGUNG JAWAB -->

                            <td>

                                <?= htmlspecialchars(
                                    $data['picName'] ?? '-'
                                ) ?>

                            </td>


                            <!-- HASIL -->

                            <td>

                                <?= htmlspecialchars(
                                    $data['expectedResultName'] ?? '-'
                                ) ?>

                            </td>


                            <!-- KETERANGAN -->

                            <td>

                                <?= htmlspecialchars(
                                    $data['item']->remarks ?: '-'
                                ) ?>

                            </td>


                        </tr>


                    <?php } ?>


                <?php } else { ?>


                    <tr>

                        <td colspan="8" style="text-align: center;">

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
                    Tuban, <?= htmlspecialchars($tanggalIndonesia) ?>
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