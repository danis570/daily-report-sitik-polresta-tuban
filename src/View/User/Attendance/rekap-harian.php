<div class="min-h-screen bg-gray-50 dark:bg-[#121212] pt-12 pb-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">

        <div class="mb-6">
            <h1
                class="font-black text-4xl sm:text-5xl uppercase tracking-tight leading-none text-[#121212] dark:text-white">
                Rekap Absensi Harian
            </h1>
        </div>

        <?php
        $startDate = $attendanceStartDate ?? new DateTimeImmutable('2026-10-01');
        $isBeforeStart = $date < $startDate;
        ?>

        <?php if ($isBeforeStart): ?>
            <div class="mb-6 p-5 bg-yellow-300 border-4 border-[#121212]
                shadow-[5px_5px_0_0_#121212]">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 shrink-0 flex items-center justify-center
                        bg-[#121212] border-2 border-[#121212]">
                        <svg class="w-5 h-5 text-yellow-300" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-black uppercase text-sm text-[#121212]">
                            Info
                        </p>
                        <p class="mt-1 text-sm font-bold text-[#121212]">
                            Sistem absensi mulai aktif tanggal
                            <strong>
                                <?= htmlspecialchars($startDate->format('j F Y')) ?>
                            </strong>.
                            Data sebelum tanggal tersebut tidak ditampilkan.
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Filter Tanggal -->
        <form method="GET" action="/absen/rekap" class="mb-6 flex gap-3 items-end">
            <div class="flex-1 max-w-xs">
                <label class="block mb-2 text-xs font-black uppercase text-[#121212] dark:text-white">
                    Tanggal
                </label>
                <input type="date" name="date" value="<?= htmlspecialchars($date->format('Y-m-d')) ?>" class="w-full px-4 py-3 bg-white dark:bg-[#121212] text-[#121212] dark:text-white
                           border-4 border-[#121212] dark:border-white font-bold outline-none
                           focus:border-[#00d982]">
            </div>
            <button type="submit" class="px-5 py-3 bg-[#00d982] text-[#121212] border-4 border-[#121212]
                       font-black uppercase text-sm shadow-[5px_5px_0_0_#121212]
                       hover:shadow-none hover:translate-x-[5px] hover:translate-y-[5px]
                       transition-all cursor-pointer">
                Tampilkan
            </button>
        </form>

        <?php
        // Daftar status yang ditampilkan
        $statusCodes = ['H', 'D', 'P', 'DIKSIP', 'DIK', 'PATSUS', 'LD', 'IZIN', 'CUTI', 'SKT', 'TK'];
        ?>

        <!-- Ringkasan -->
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
                    <span class="text-xs text-[#121212]/60">
                        / <?= (int) ($summary['total_user'] ?? 0) ?>
                    </span>
                </p>
            </div>
        </div>

        <!-- Tabel -->
        <div class="bg-white dark:bg-[#181818] border-4 border-[#121212] dark:border-white
                    shadow-[6px_6px_0_0_#121212] dark:shadow-[6px_6px_0_0_#00d982] overflow-x-auto">

            <table class="w-full">
                <thead class="bg-[#121212] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-black uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-black uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-black uppercase">Pangkat/NRP</th>
                        <th class="px-4 py-3 text-left text-xs font-black uppercase">Jabatan</th>
                        <th class="px-4 py-3 text-center text-xs font-black uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-black uppercase">Masuk</th>
                        <th class="px-4 py-3 text-center text-xs font-black uppercase">Keluar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    foreach ($rows as $row):
                        $profile = $row['profile'];
                        $att = $row['attendance'];
                        ?>
                        <tr class="border-t-2 border-[#121212] dark:border-gray-700">
                            <td class="px-4 py-3 text-sm font-bold"><?= $no++ ?></td>
                            <td class="px-4 py-3 text-sm font-black">
                                <?= htmlspecialchars($profile->name ?? '-') ?>
                            </td>
                            <td class="px-4 py-3 text-sm font-bold">
                                <?= htmlspecialchars(($profile->rank ?? '') . '/' . ($profile->nrp ?? '-')) ?>
                            </td>
                            <td class="px-4 py-3 text-sm font-bold">
                                <?= htmlspecialchars($profile->position ?? '-') ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <?php if ($att !== null): ?>
                                    <span
                                        class="inline-block px-3 py-1 bg-[#00d982] border-2 border-[#121212] text-xs font-black uppercase">
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