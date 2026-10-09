<div class="min-h-screen bg-gray-50 dark:bg-[#121212] pt-12 pb-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">

        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="font-black text-4xl sm:text-5xl uppercase tracking-tight leading-none text-[#121212] dark:text-white">
                    Status Absensi
                </h1>
                <p class="mt-3 text-sm sm:text-base font-medium text-gray-600 dark:text-gray-400">
                    Master data status absensi.
                </p>
            </div>
            <a href="/admin/attendance/status/add"
                class="inline-flex items-center justify-center gap-2 px-5 py-3
                       bg-[#00d982] text-[#121212] border-4 border-[#121212]
                       font-black uppercase text-sm
                       shadow-[5px_5px_0_0_#121212]
                       hover:shadow-none hover:translate-x-[5px] hover:translate-y-[5px]
                       transition-all">
                <i data-lucide="plus" class="w-5 h-5"></i>
                Tambah Status
            </a>
        </div>

        <!-- Flash Message -->
        <?php if (!empty($_SESSION['flash_message'])): ?>
            <div class="mb-6 p-4 <?= ($_SESSION['flash_type'] ?? 'success') === 'error' ? 'bg-red-500 text-white' : 'bg-[#00d982] text-black' ?>
                        border-4 border-[#121212] font-black uppercase text-sm
                        shadow-[5px_5px_0_0_#121212]">
                <?= htmlspecialchars($_SESSION['flash_message']) ?>
            </div>
            <?php \Unirow2026\DailyReportSitikPolrestaTuban\App\View::clearFlashMessage(); ?>
        <?php endif; ?>

        <!-- Tabel -->
        <div class="bg-white dark:bg-[#181818] border-4 border-[#121212] dark:border-white
                    shadow-[6px_6px_0_0_#121212] dark:shadow-[6px_6px_0_0_#00d982] overflow-x-auto">

            <table class="w-full text-sm">
                <thead class="bg-[#121212] text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-black uppercase">Kode</th>
                        <th class="px-4 py-3 text-left text-xs font-black uppercase">Label</th>
                        <th class="px-4 py-3 text-center text-xs font-black uppercase">Sort</th>
                        <th class="px-4 py-3 text-center text-xs font-black uppercase">Status</th>
                        <th class="px-4 py-3 text-center text-xs font-black uppercase">Dipakai</th>
                        <th class="px-4 py-3 text-center text-xs font-black uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($statusList as $s): ?>
                        <?php $usage = $statusRepository->countUsage($s->code); ?>
                        <tr class="border-t-2 border-[#121212] dark:border-gray-700">
                            <td class="px-4 py-3 font-black font-mono">
                                <?= htmlspecialchars($s->code) ?>
                            </td>
                            <td class="px-4 py-3 font-bold">
                                <?= htmlspecialchars($s->label) ?>
                            </td>
                            <td class="px-4 py-3 text-center font-bold">
                                <?= (int) $s->sortOrder ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <?php if ($s->isActive): ?>
                                    <span class="inline-block px-3 py-1 bg-[#00d982] border-2 border-[#121212] text-xs font-black uppercase">
                                        Aktif
                                    </span>
                                <?php else: ?>
                                    <span class="inline-block px-3 py-1 bg-gray-400 border-2 border-[#121212] text-xs font-black uppercase">
                                        Nonaktif
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-4 py-3 text-center font-bold">
                                <?= $usage ?>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="inline-flex gap-2">
                                    <a href="/admin/attendance/status/edit/<?= urlencode($s->code) ?>"
                                        class="px-3 py-1 bg-yellow-400 text-black border-2 border-black
                                               font-black uppercase text-xs
                                               shadow-[2px_2px_0_0_#000] hover:shadow-none
                                               transition-all">
                                        Edit
                                    </a>
                                    <?php if ($usage === 0): ?>
                                        <form action="/admin/attendance/status/delete/<?= urlencode($s->code) ?>"
                                              method="POST" class="inline"
                                              onsubmit="return confirm('Yakin hapus status <?= htmlspecialchars($s->code, ENT_QUOTES) ?>?');">
                                            <button type="submit"
                                                class="px-3 py-1 bg-red-500 text-white border-2 border-black
                                                       font-black uppercase text-xs
                                                       shadow-[2px_2px_0_0_#000] hover:shadow-none
                                                       transition-all cursor-pointer">
                                                Hapus
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="px-3 py-1 bg-gray-300 text-gray-500 border-2 border-black
                                                     font-black uppercase text-xs cursor-not-allowed"
                                              title="Tidak bisa dihapus, sedang dipakai di <?= $usage ?> absensi">
                                            Terpakai
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>