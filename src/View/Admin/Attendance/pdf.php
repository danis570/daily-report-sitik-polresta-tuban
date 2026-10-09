<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($titleDocument) ?></title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }


        body,
        body * {
            font-family: Arial, Helvetica, sans-serif !important;
            font-size: 12px !important;
        }

        /* ============ KOP POLRI ============ */
        .kop {
            display: inline-block;
            text-align: left;
            margin-bottom: 15px;
            line-height: 1.4;
            padding-bottom: 3px;
            border-bottom: 1px solid #000;
        }

        .kop .line1 {
            font-size: 12px;
            font-weight: normal;
            text-transform: uppercase;
            padding-left: 45px;
        }

        .kop .line2 {
            font-size: 12px;
            font-weight: normal;
            text-transform: uppercase;
            padding-left: 70px;
        }

        .kop .line3 {
            font-size: 12px;
            font-weight: normal;
            text-transform: uppercase;
            padding-left: 0;
        }

        /* ============ JUDUL ============ */
        .judul {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 10px;
            margin-bottom: 2px;
            text-decoration: underline;
        }

        .tanggal {
            text-align: center;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        /* ============ TABEL ============ */
        table.main {
            width: auto;
            min-width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
            table-layout: auto;
        }

        table.main th,
        table.main td {
            border: 1px solid #000;
            padding: 3px 5px;
            font-size: 10px;
            vertical-align: middle;
            white-space: nowrap;
        }

        table.main thead th {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 10px;
            background-color: #fff;
            line-height: 1.2;
            white-space: nowrap;
            overflow-wrap: normal;
            word-break: normal;
        }

        table.main thead th.status-col {
            white-space: nowrap;
            padding: 3px 5px;
        }

        table.main tbody td {
            height: 16px;
        }

        /* Kolom nomor (baris "1 2 3 ...") */
        table.main .no-col {
            text-align: center;
            font-weight: bold;
            font-size: 9px;
        }

        /* ============ INFO BAWAH ============ */
        table.info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            table-layout: fixed;
        }

        table.info td {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 10px;
            vertical-align: top;
        }

        table.info .label {
            font-weight: bold;
            text-transform: uppercase;
            width: 20%;
        }

        table.info .sep {
            width: 4%;
            text-align: center;
        }

        table.info .val {
            width: 6%;
            text-align: center;
        }

        table.info .catatan {
            width: auto;
        }

        /* ============ TANDA TANGAN ============ */
        .ttd {
            margin-top: 25px;
            text-align: center;
            margin-left: 550px;
            font-size: 11px;
            line-height: 1.4;
            width: 200px;
        }

        .ttd .jabatan {
            margin-bottom: 60px;
        }

        .ttd .nama {
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .ttd .nrp {
            font-weight: bold;
        }

        /* ============ CHECKMARK ============ */
        .check {
            font-family: 'DejaVu Sans', sans-serif !important;
            font-size: 12px !important;
            font-weight: bold;
        }

        .page-break {
            page-break-after: always;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }
    </style>
</head>

<body>

    <?php
    // ══════════════════════════════════════════════════════════════
// MACRO: kop Polri
// ══════════════════════════════════════════════════════════════
    function renderKop()
    {
        ?>
        <div class="kop-wrapper">
            <div class="kop">
                <div class="line1">POLRI DAERAH JAWA TIMUR</div>
                <div class="line2">RESOR KOTA TUBAN</div>
                <div class="line3">SEKSI TEKNOLOGI INFORMASI KOMUNIKASI</div>
            </div>
        </div>
        <?php
    }
    ?>

    <?php
    // ══════════════════════════════════════════════════════════════
// MACRO: tabel absensi
// ══════════════════════════════════════════════════════════════
    function renderTable($mode, $statusCodes, $rows, $minRows = 12)
    {
        $totalCols = 4 + count($statusCodes);
        ?>
        <table class="main">

            <thead>
                <tr>
                    <th style="width: 6%;" rowspan="2">NO</th>
                    <th rowspan="2">NAMA</th>
                    <th rowspan="2">PANGKAT/NRP</th>
                    <th rowspan="2">JABATAN</th>
                    <th colspan="<?= count($statusCodes) ?>">KETERANGAN</th>
                </tr>
                <tr>
                    <?php foreach ($statusCodes as $code): ?>
                        <th class="status-col"><?= htmlspecialchars($code) ?></th>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <th class="no-col">1</th>
                    <th class="no-col">2</th>
                    <th class="no-col">3</th>
                    <th class="no-col">4</th>
                    <?php $n = 5;
                    foreach ($statusCodes as $code): ?>
                        <th class="no-col"><?= $n++ ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                    <tr>
                        <td colspan="<?= $totalCols ?>" class="text-center" style="padding: 15px;">
                            Tidak ada data absensi.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1;
                    foreach ($rows as $row):
                        $profile = $row['profile'];
                        ?>
                        <tr>
                            <td class="text-center"><?= $no++ ?></td>
                            <td><?= htmlspecialchars($profile->name ?? '-') ?></td>
                            <td><?= htmlspecialchars(($profile->rank ?? '') . '/' . ($profile->nrp ?? '-')) ?></td>
                            <td><?= htmlspecialchars($profile->position ?? '-') ?></td>
                            <?php if ($mode === 'harian'): ?>
                                <?php $att = $row['attendance'];
                                foreach ($statusCodes as $code): ?>
                                    <td class="text-center">
                                        <?php if ($att !== null && $att->statusCode === $code): ?>
                                            <span class="check">&#10003;</span>
                                        <?php endif; ?>
                                    </td>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <?php $counts = $row['counts'];
                                foreach ($statusCodes as $code): ?>
                                    <td class="text-center" style="font-weight: bold;">
                                        <?= (int) ($counts[$code] ?? 0) ?>
                                    </td>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>

                    <?php for ($i = count($rows); $i < $minRows; $i++): ?>
                        <tr>
                            <td>&nbsp;</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <?php foreach ($statusCodes as $code): ?>
                                <td></td><?php endforeach; ?>
                        </tr>
                    <?php endfor; ?>
                <?php endif; ?>
            </tbody>
        </table>
        <?php
    }
    ?>

    <?php
    // ══════════════════════════════════════════════════════════════
// MACRO: info bawah (JUMLAH/KURANG/HADIR) + catatan
// ══════════════════════════════════════════════════════════════
    function renderInfo($summary)
    {
        ?>
        <table class="info">
            <tr>
                <td class="label">JUMLAH</td>
                <td class="sep">:</td>
                <td class="val"><?= (int) ($summary['total_user'] ?? 0) ?></td>
                <td class="catatan" rowspan="3">
                    <strong>Catatan :</strong>
                </td>
            </tr>
            <tr>
                <td class="label">KURANG</td>
                <td class="sep">:</td>
                <td class="val"><?= (int) (($summary['total_user'] ?? 0) - ($summary['total_present'] ?? 0)) ?></td>
            </tr>
            <tr>
                <td class="label">HADIR</td>
                <td class="sep">:</td>
                <td class="val"><?= (int) ($summary['total_present'] ?? 0) ?></td>
            </tr>
        </table>
        <?php
    }
    ?>

    <?php
    // ══════════════════════════════════════════════════════════════
// MACRO: tanda tangan
// ══════════════════════════════════════════════════════════════
    function renderTtd()
    {
        ?>
        <div class="ttd">
            <div>Mengetahui :</div>
            <div class="jabatan">PS. KASI TIK</div>
            <div class="nama">INDRA DHEDY S</div>
            <div class="nrp">AIPTU NRP 82031270</div>
        </div>
        <?php
    }
    ?>

    <?php if ($mode === 'harian'): ?>

        <!-- ══════════════════════════════════════════ -->
        <!-- HARIAN: 2 HALAMAN -->
        <!-- ══════════════════════════════════════════ -->

        <!-- HALAMAN 1: APEL PAGI -->
        <?php renderKop(); ?>
        <div class="judul">ABSENSI KEHADIRAN APEL PAGI ANGGOTA SI TIK POLRES TUBAN</div>
        <div class="tanggal">HARI / TANGGAL : <?= htmlspecialchars($hariIndo) ?>, <?= htmlspecialchars($tanggalIndo) ?>
        </div>
        <?php renderTable('harian', $statusCodes, $rows); ?>
        <?php renderInfo($summary); ?>
        <?php renderTtd(); ?>

        <div class="page-break"></div>

        <!-- HALAMAN 2: APEL SORE -->
        <?php renderKop(); ?>
        <div class="judul">ABSENSI KEHADIRAN APEL SORE ANGGOTA SI TIK POLRES TUBAN</div>
        <div class="tanggal">HARI / TANGGAL : <?= htmlspecialchars($hariIndo) ?>, <?= htmlspecialchars($tanggalIndo) ?>
        </div>
        <?php renderTable('harian', $statusCodes, $rows); ?>
        <?php renderInfo($summary); ?>
        <?php renderTtd(); ?>

    <?php elseif ($mode === 'harian-range'): ?>

        <!-- ══════════════════════════════════════════ -->
        <!-- HARIAN RANGE: LOOP TIAP TANGGAL -->
        <!-- ══════════════════════════════════════════ -->
        <?php foreach ($dailyData as $index => $dayData): ?>
            <!-- APEL PAGI -->
            <?php renderKop(); ?>
            <div class="judul">ABSENSI KEHADIRAN APEL PAGI ANGGOTA SI TIK POLRES TUBAN</div>
            <div class="tanggal">HARI / TANGGAL : <?= htmlspecialchars($dayData['hariIndo']) ?>,
                <?= htmlspecialchars($dayData['tanggalIndo']) ?>
            </div>
            <?php renderTable('harian', $statusCodes, $dayData['rows']); ?>
            <?php renderInfo($dayData['summary']); ?>
            <?php renderTtd(); ?>

            <div class="page-break"></div>

            <!-- APEL SORE -->
            <?php renderKop(); ?>
            <div class="judul">ABSENSI KEHADIRAN APEL SORE ANGGOTA SI TIK POLRES TUBAN</div>
            <div class="tanggal">HARI / TANGGAL : <?= htmlspecialchars($dayData['hariIndo']) ?>,
                <?= htmlspecialchars($dayData['tanggalIndo']) ?>
            </div>
            <?php renderTable('harian', $statusCodes, $dayData['rows']); ?>
            <?php renderInfo($dayData['summary']); ?>
            <?php renderTtd(); ?>

            <?php if ($index < count($dailyData) - 1): ?>
                <div class="page-break"></div>
            <?php endif; ?>
        <?php endforeach; ?>

    <?php else: ?>

        <!-- ══════════════════════════════════════════ -->
        <!-- BULANAN: 1 HALAMAN -->
        <!-- ══════════════════════════════════════════ -->
        <?php renderKop(); ?>
        <div class="judul">REKAP ABSENSI BULANAN ANGGOTA SI TIK POLRES TUBAN</div>
        <div class="tanggal">PERIODE : <?= htmlspecialchars($periodeIndo) ?></div>
        <?php renderTable('bulanan', $statusCodes, $rows); ?>
        <?php renderTtd(); ?>

    <?php endif; ?>

</body>

</html>