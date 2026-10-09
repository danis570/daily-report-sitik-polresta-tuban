<div class="min-h-screen bg-gray-50 dark:bg-[#121212] pt-12 pb-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">

        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="font-black text-4xl sm:text-5xl uppercase tracking-tight leading-none text-[#121212] dark:text-white">
            Rekap Harian (Admin)
        </h1>
    </div>
    <a href="/admin/attendance/rekap/pdf?date=<?= $date->format('Y-m-d') ?>"
       target="_blank"
       class="inline-flex items-center justify-center gap-2 px-5 py-3
              bg-red-500 text-white border-4 border-[#121212]
              font-black uppercase text-sm
              shadow-[5px_5px_0_0_#121212]
              hover:shadow-none hover:translate-x-[5px] hover:translate-y-[5px]
              transition-all">
        <i data-lucide="file-text" class="w-5 h-5"></i>
        Download PDF
    </a>
</div>

        <!-- Filter Tanggal -->
        <form method="GET" action="/admin/attendance/rekap" class="mb-6 flex gap-3 items-end">
            <div class="flex-1 max-w-xs">
                <label class="block mb-2 text-xs font-black uppercase text-[#121212] dark:text-white">Tanggal</label>
                <input type="date" name="date" value="<?= htmlspecialchars($date->format('Y-m-d')) ?>"
                    class="w-full px-4 py-3 bg-white dark:bg-[#121212] text-[#121212] dark:text-white
                           border-4 border-[#121212] dark:border-white font-bold outline-none">
            </div>
            <button type="submit"
                class="px-5 py-3 bg-[#00d982] text-[#121212] border-4 border-[#121212]
                       font-black uppercase text-sm shadow-[5px_5px_0_0_#121212]
                       hover:shadow-none transition-all cursor-pointer">
                Tampilkan
            </button>
        </form>

        <?php
        $startDate = $attendanceStartDate ?? new DateTimeImmutable('2026-10-01');
        $isBeforeStart = $date < $startDate;
        ?>

        <?php if ($isBeforeStart): ?>
            <div class="mb-6 p-5 bg-yellow-300 border-4 border-[#121212] shadow-[5px_5px_0_0_#121212]">
                <p class="font-black uppercase text-sm text-[#121212]">Info</p>
                <p class="mt-1 text-sm font-bold text-[#121212]">
                    Sistem absensi mulai aktif tanggal
                    <strong><?= htmlspecialchars($startDate->format('j F Y')) ?></strong>.
                </p>
            </div>
        <?php endif; ?>

        <?php $statusCodes = ['H','D','P','DIKSIP','DIK','PATSUS','LD','IZIN','CUTI','SKT','TK']; ?>

        <div class="mb-6 grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3">
            <?php foreach ($statusCodes as $code): ?>
                <div class="p-3 bg-white dark:bg-[#181818] border-4 border-[#121212] dark:border-white">
                    <p class="text-xs font-black uppercase text-gray-500"><?= $code ?></p>
                    <p class="font-black text-2xl text-[#121212] dark:text-white">
                        <?= (int) ($summary[$code] ?? 0) ?>
                    </p>
                </div>
            <?php endforeach; ?>
            <div class="p-3 bg-[#00d982] border-4 border-[#121212]">
                <p class="text-xs font-black uppercase text-[#121212]">Absen</p>
                <p class="font-black text-2xl text-[#121212]">
                    <?= (int) ($summary['total_present'] ?? 0) ?>
                    <span class="text-xs text-[#121212]/60">/ <?= (int) ($summary['total_user'] ?? 0) ?></span>
                </p>
            </div>
        </div>

        <!-- Tabel sama seperti rekap harian User, ganti current saja -->
        <div class="bg-white dark:bg-[#181818] border-4 border-[#121212] dark:border-white
                    shadow-[6px_6px_0_0_#121212] overflow-x-auto">
            <table class="w-full">
                <thead class="bg-[#121212] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-black uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-black uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-black uppercase">Pangkat/NRP</th>
                        <th class="px-4 py-3 text-center text-xs font-black uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-black uppercase">Masuk</th>
                        <th class="px-4 py-3 text-center text-xs font-black uppercase">Keluar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($rows as $row):
                        $profile = $row['profile'];
                        $att = $row['attendance'];
                    ?>
                        <tr class="border-t-2 border-[#121212] dark:border-gray-700">
                            <td class="px-4 py-3 text-sm font-bold"><?= $no++ ?></td>
                            <td class="px-4 py-3 text-sm font-black"><?= htmlspecialchars($profile->name ?? '-') ?></td>
                            <td class="px-4 py-3 text-sm font-bold">
                                <?= htmlspecialchars(($profile->rank ?? '') . '/' . ($profile->nrp ?? '-')) ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <?php if ($att !== null): ?>
                                    <span class="inline-block px-3 py-1 bg-[#00d982] border-2 border-[#121212] text-xs font-black uppercase">
                                        <?= htmlspecialchars($att->statusCode) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-center text-sm font-bold">
                                <?= $att && $att->checkInTime ? $att->checkInTime->format('H:i') : '-' ?>
                            </td>
                            <td class="px-4 py-3 text-center text-sm font-bold">
                                <?= $att && $att->checkOutTime ? $att->checkOutTime->format('H:i') : '-' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>