<div class="min-h-screen bg-gray-50 dark:bg-[#121212] pt-12 pb-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="mb-6">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div>

                    <h1
                        class="font-black text-4xl sm:text-5xl uppercase tracking-tight text-[#121212] dark:text-white leading-none">
                        <?= htmlspecialchars($title ?? 'Kelola Laporan Harian') ?>
                    </h1>

                    <p class="mt-3 text-sm sm:text-base font-medium text-gray-600 dark:text-gray-400 max-w-2xl">
                        Daftar rekapitulasi laporan harian SITIK Polresta Tuban.
                    </p>
                </div>


            </div>

        </div>

        <!-- Global Flash Message -->
        <!-- ============================== -->
        <!-- Global Flash Message -->
        <!-- ============================== -->
        <?php
        use Unirow2026\DailyReportSitikPolrestaTuban\App\View;

        if (!empty($_SESSION['flash_message'])):

            $flashType = $_SESSION['flash_type'] ?? 'success';
            $isError = $flashType === 'error';
            ?>

            <div id="flash-message" class="mb-6 p-4
               <?= $isError
                   ? 'bg-red-500 text-white'
                   : 'bg-[#00d982] text-black' ?>
               border-4 border-black dark:border-white
               shadow-[6px_6px_0_0_#000] dark:shadow-[6px_6px_0_0_#00d982]
               flex items-start gap-4
               transition-all duration-200" role="alert">

                <!-- Icon -->
                <div class="w-9 h-9 flex-shrink-0
                    flex items-center justify-center
                    border-2 border-black
                    <?= $isError
                        ? 'bg-white text-red-500'
                        : 'bg-black text-[#00d982]' ?>">

                    <?php if ($isError): ?>
                        <!-- Icon error (silang) -->
                        <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    <?php else: ?>
                        <!-- Icon sukses (centang) -->
                        <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    <?php endif; ?>
                </div>

                <!-- Text -->
                <div class="flex-1 min-w-0">
                    <p class="font-black uppercase text-sm">
                        <?= $isError ? 'Gagal' : 'Berhasil' ?>
                    </p>
                    <p class="mt-1 font-bold text-sm break-words">
                        <?= htmlspecialchars($_SESSION['flash_message']) ?>
                    </p>
                </div>

                <!-- Close -->
                <button type="button" id="flash-close" class="shrink-0 w-8 h-8 flex items-center justify-center
                   border-2 border-black
                   <?= $isError
                       ? 'bg-white text-red-500 hover:bg-black hover:text-white'
                       : 'bg-black text-[#00d982] hover:bg-white hover:text-black' ?>
                   transition-colors cursor-pointer" aria-label="Tutup notifikasi">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 6l12 12M6 18L18 6" />
                    </svg>
                </button>

            </div>

            <?php View::clearFlashMessage(); ?>

        <?php endif; ?>


        <!-- Error -->
        <?php if (!empty($error)) { ?>
            <div class="mb-8 p-5 bg-yellow-300 border-4 border-[#121212]
                shadow-[6px_6px_0_0_#121212]" role="alert">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-[#121212]" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-black uppercase text-sm">Miss</p>
                        <p class="mt-1 font-bold text-sm"><?= htmlspecialchars($error) ?></p>
                    </div>
                </div>
            </div>
        <?php } ?>

        <!-- Filter & Cetak (Collapsible) -->
        <div class="mb-10">

            <!-- Tombol Toggle Tools -->
            <button type="button" id="filter-toggle" class="inline-flex items-center gap-2 px-5 py-3
           bg-white dark:bg-[#181818] text-[#121212] dark:text-white
           border-4 border-[#121212] dark:border-white
           font-black uppercase text-sm
           shadow-[5px_5px_0_0_#121212] dark:shadow-[5px_5px_0_0_#00d982]
           hover:shadow-none hover:translate-x-[5px] hover:translate-y-[5px]
           transition-all duration-150">
                <i data-lucide="settings" class="w-5 h-5"></i>
                <span>Tools</span>
            </button>

            <!-- Panel Filter (default hidden) -->
            <div id="filter-panel" class="hidden">
                <div class="bg-white dark:bg-[#181818] border-4 border-[#121212] dark:border-white
                    shadow-[6px_6px_0_0_#121212] dark:shadow-[6px_6px_0_0_#00d982]">
                    <div class="p-6 sm:p-7">

                        <!-- Section Header -->
                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="w-3 h-3 bg-[#00d982] border-2 border-black"></span>
                                    <span
                                        class="text-xs font-black uppercase tracking-[0.2em] text-gray-600 dark:text-gray-400">
                                        Filter & Cetak
                                    </span>
                                </div>
                                <h2
                                    class="font-black text-xl sm:text-2xl uppercase tracking-tight text-[#121212] dark:text-white">
                                    Rekap Laporan
                                </h2>
                                <p class="mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                                    Pilih rentang tanggal untuk menyaring laporan atau mencetak beberapa laporan
                                    sekaligus.
                                </p>
                            </div>

                            <!-- Tombol Tutup Panel -->
                            <button type="button" id="filter-close" class="self-start lg:self-auto inline-flex items-center gap-2 px-4 py-2
                               bg-red-500 text-white border-4 border-black
                               font-black uppercase text-xs
                               shadow-[4px_4px_0_0_#121212]
                               hover:shadow-none hover:translate-x-1 hover:translate-y-1
                               transition-all duration-150">
                                <i data-lucide="x" class="w-4 h-4"></i>
                                Tutup
                            </button>
                        </div>

                        <!-- Form Filter -->
                        <form method="GET" action="/reports"
                            class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-[1fr_1fr_auto_auto_auto] gap-4 items-end">

                            <!-- Tanggal Mulai -->
                            <div>
                                <label for="start_date"
                                    class="block mb-2 text-xs font-black uppercase tracking-wider text-[#121212] dark:text-white">
                                    Tanggal Mulai
                                </label>
                                <input type="date" id="start_date" name="start_date"
                                    value="<?= htmlspecialchars($startDate ?? '') ?>"
                                    class="w-full px-4 py-3 bg-white dark:bg-[#222] text-[#121212] dark:text-white border-4 border-[#121212] dark:border-white font-bold focus:outline-none focus:ring-4 focus:ring-[#00d982]">
                            </div>

                            <!-- Tanggal Akhir -->
                            <div>
                                <label for="end_date"
                                    class="block mb-2 text-xs font-black uppercase tracking-wider text-[#121212] dark:text-white">
                                    Tanggal Akhir
                                </label>
                                <input type="date" id="end_date" name="end_date"
                                    value="<?= htmlspecialchars($endDate ?? '') ?>"
                                    class="w-full px-4 py-3 bg-white dark:bg-[#222] text-[#121212] dark:text-white border-4 border-[#121212] dark:border-white font-bold focus:outline-none focus:ring-4 focus:ring-[#00d982]">
                            </div>

                            <!-- Reset -->
                            <?php if (!empty($startDate) || !empty($endDate)): ?>
                                <a href="/reports"
                                    class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-red-500 text-white border-4 border-black font-black uppercase text-sm shadow-[5px_5px_0_0_#121212] hover:shadow-none hover:translate-x-[5px] hover:translate-y-[5px] transition-all duration-150">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                    Reset
                                </a>
                            <?php endif; ?>

                            <button type="submit" id="filter-submit" disabled class="inline-flex items-center justify-center gap-2 px-5 py-3 
           bg-yellow-400 text-black border-4 border-black 
           font-black uppercase text-sm 
           shadow-[5px_5px_0_0_#121212] 
           hover:shadow-none hover:translate-x-[5px] hover:translate-y-[5px] 
           transition-all duration-150
           disabled:opacity-40 disabled:cursor-not-allowed 
           disabled:shadow-none disabled:hover:translate-x-0 disabled:hover:translate-y-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M3 4h18M6 10h12M10 16h4" />
                                </svg>
                                Tampilkan
                            </button>



                            <!-- Cetak PDF -->
                            <a href="#" id="printRangeButton" target="_blank" class="inline-flex items-center justify-center gap-2 px-5 py-3 
           bg-[#00d982] text-black border-4 border-black 
           font-black uppercase text-sm 
           shadow-[5px_5px_0_0_#121212] 
           hover:shadow-none hover:translate-x-[5px] hover:translate-y-[5px] 
           transition-all duration-150
           pointer-events-none opacity-40 cursor-not-allowed
           aria-disabled=" true">
                                <i data-lucide="printer" class="w-5 h-5"></i>
                                Cetak PDF
                            </a>

                        </form>

                        <p id="filter-hint" class="mt-3 text-xs font-bold text-gray-500 dark:text-gray-400">
                            <span class="font-black text-[#121212] dark:text-white"></span>
                            <span class="font-black text-[#121212] dark:text-white"></span>
                        </p>

                        <!-- Pencarian Realtime -->
                        <div class="mt-5">
                            <label for="search_reports"
                                class="block mb-2 text-xs font-black uppercase tracking-wider text-[#121212] dark:text-white">
                                Cari Laporan
                            </label>
                            <div class="relative">
                                <input type="text" id="search_reports"
                                    placeholder="Cari tanggal, pembuat, atau ID laporan..."
                                    class="w-full px-4 py-3 pr-12 bg-white dark:bg-[#222] text-[#121212] dark:text-white border-4 border-[#121212] dark:border-white font-bold focus:outline-none focus:ring-4 focus:ring-[#00d982]">

                                <button type="button" id="search-clear" class="hidden absolute right-3 top-1/2 -translate-y-1/2
                               w-7 h-7 flex items-center justify-center
                               bg-[#121212] text-white border-2 border-[#121212]
                               hover:bg-[#00d982] hover:text-[#121212] transition-colors" aria-label="Hapus pencarian">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 6l12 12M6 18L18 6" />
                                    </svg>
                                </button>
                            </div>

                            <p id="search-info" class="mt-2 text-xs font-bold text-gray-500 dark:text-gray-400 hidden">
                                Menampilkan <span id="search-count">0</span> dari <span id="search-total">0</span>
                                laporan
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Info Hasil -->
        <?php if (!empty($reports)): ?>
            <div class="mb-5 px-2 py-3 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">

                <div>
                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400">
                        <?php if (!empty($startDate) && !empty($endDate)): ?>
                            Menampilkan <span class="font-black text-[#121212] dark:text-white"><?= count($reports) ?></span>
                            laporan dalam rentang
                        <?php else: ?>
                            Menampilkan
                            <span class="font-black text-[#121212] dark:text-white"><?= count($reports) ?></span>
                            dari
                            <span
                                class="font-black text-[#121212] dark:text-white"><?= number_format($total, 0, ',', '.') ?></span>
                            laporan
                        <?php endif; ?>
                    </p>

                    <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mt-1">
                        <?php if (!empty($startDate) && !empty($endDate)): ?>
                            Waktu : <?= htmlspecialchars($startDate) ?> s/d <?= htmlspecialchars($endDate) ?>
                        <?php else: ?>
                            10 laporan terbaru · Filter untuk melihat data lainnya (Maks rentang filter 31 hari)
                        <?php endif; ?>
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <!-- Daftar Report -->
        <div class="space-y-7" id="report-list">

            <?php if (!empty($reports)) { ?>

                <?php foreach ($reports as $item) { ?>

                    <!-- Report Card Container -->
                    <div class="report-card block bg-white dark:bg-[#181818]
                        border-4 border-[#121212] dark:border-white
                        shadow-[6px_6px_0_0_#121212]
                        dark:shadow-[6px_6px_0_0_#00d982]
                        hover:shadow-none
                        hover:translate-x-[6px]
                        hover:translate-y-[6px]
                        transition-all duration-150 mb-6" data-search="<?= htmlspecialchars(strtolower(
                            ($item['formattedDate'] ?? '') . ' ' .
                            ($item['creatorName'] ?? '') . ' ' .
                            'report ' . $item['report']->id . ' ' .
                            $item['report']->reportDate->format('Y-m-d')
                        )) ?>">

                        <div class="p-6 sm:p-7">

                            <!-- Card Header -->
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-5">

                                <!-- Sisi Kiri -->
                                <div class="min-w-0 flex-1">

                                    <div class="flex items-center gap-2 mb-3">
                                        <span
                                            class="inline-block px-3 py-1 bg-[#121212] text-[#00d982] border-2 border-[#121212] font-black text-[10px] uppercase tracking-widest">
                                            LAPORAN HARIAN
                                        </span>
                                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400">
                                            #<?= (int) $item['report']->id ?>
                                        </span>
                                    </div>

                                    <h2
                                        class="font-black text-2xl sm:text-3xl uppercase tracking-tight text-[#121212] dark:text-white transition-colors">
                                        <?= htmlspecialchars($item['formattedDate']) ?>
                                    </h2>

                                    <div
                                        class="mt-3 flex items-center gap-2 text-sm font-bold text-gray-600 dark:text-gray-400">
                                        <div
                                            class="w-8 h-8 flex items-center justify-center bg-[#00d982] border-2 border-[#121212]">
                                            <svg class="w-4 h-4 text-[#121212]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                        </div>
                                        <span>
                                            Oleh:
                                            <span class="text-[#121212] dark:text-white font-black">
                                                <?= htmlspecialchars($item['creatorName']) ?>
                                            </span>
                                        </span>
                                    </div>

                                </div>

                                <!-- Sisi Kanan -->
                                <div class="flex flex-col items-stretch sm:items-end gap-2 shrink-0 w-full sm:w-auto">

                                    <div class="flex items-center gap-2 w-full sm:w-auto">

                                        <a href="/report/edit/<?= $item['report']->id ?>"
                                            class="flex-1 sm:flex-none text-center px-3 py-1.5 bg-yellow-400 text-black border-2 border-black font-black uppercase text-xs shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all">
                                            Edit
                                        </a>

                                        <!-- Duplikat (BARU) -->
                                        <button type="button" class="duplicate-btn flex-1 sm:flex-none px-3 py-1.5
                                            bg-blue-500 text-white
                                            border-2 border-black
                                            font-black uppercase text-xs
                                            shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]
                                            hover:translate-x-[1px] hover:translate-y-[1px]
                                            hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)]
                                            transition-all cursor-pointer"
                                            data-report-id="<?= (int) $item['report']->id ?>"
                                            data-report-date="<?= htmlspecialchars($item['formattedDate']) ?>">
                                            Duplikat
                                        </button>

                                        <a href="/report/print/pdf/<?= $item['report']->reportDate->format('Y-m-d') ?>"
                                            target="_blank"
                                            class="flex-1 sm:flex-none text-center px-3 py-1.5 bg-[#00d982] text-black border-2 border-black font-black uppercase text-xs shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all">
                                            <span class="inline-flex items-center gap-1">
                                                <i data-lucide="printer" class="w-4 h-4"></i>
                                                Cetak
                                            </span>
                                        </a>

                                        <?php
                                        // Daftar template acuan (sinkron dengan ReportService::PROTECTED_TEMPLATE_IDS)
                                        $protectedTemplateIds = [66, 67, 68, 69, 70];
                                        $isProtected = in_array((int) $item['report']->id, $protectedTemplateIds, true);
                                        ?>

                                        <?php if ($isProtected): ?>

                                            <!-- Template acuan: tidak boleh dihapus -->
                                            <span class="flex-1 sm:flex-none text-center px-3 py-1.5
                                                        bg-yellow-400 text-[#121212]
                                                        border-2 border-black
                                                        font-black uppercase text-xs
                                                        shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]
                                                        cursor-not-allowed"
                                                title="Laporan ini adalah template acuan, tidak boleh dihapus.">
                                                Template
                                            </span>

                                        <?php else: ?>

                                            <!-- Report biasa: tombol Hapus normal -->
                                            <form action="/report/delete/<?= $item['report']->id ?>" method="POST"
                                                class="delete-form flex-1 sm:flex-none inline">
                                                <button type="button" class="delete-btn w-full text-center px-3 py-1.5 bg-red-500 text-white
                                                    border-2 border-black font-black uppercase text-xs
                                                    shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]
                                                    hover:translate-x-[1px] hover:translate-y-[1px]
                                                    hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all"
                                                    data-date="<?= htmlspecialchars($item['formattedDate']) ?>"
                                                    data-id="<?= (int) $item['report']->id ?>">
                                                    Hapus
                                                </button>
                                            </form>

                                        <?php endif; ?>

                                    </div>

                                    <a href="/report/<?= $item['report']->reportDate->format('Y-m-d') ?>" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 h-10
                                        bg-[#00d982] text-[#121212] border-2 border-[#121212]
                                        font-black uppercase text-xs
                                        shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]
                                        hover:translate-x-0.5 hover:-translate-y-0.5 transition-transform">

                                        <span>Manajemen Kegiatan</span>

                                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                                d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>

                                </div>
                            </div>

                            <div class="my-6 border-t-2 border-dashed border-gray-300 dark:border-gray-700"></div>

                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="px-3 py-1.5 bg-gray-100 dark:bg-[#222] border-2 border-[#121212] dark:border-gray-600 text-xs font-black text-[#121212] dark:text-white">
                                        REPORT ID #<?= (int) $item['report']->id ?>
                                    </span>
                                </div>

                                <div class="mt-1 flex items-center gap-2 text-xs font-bold text-gray-500 dark:text-gray-400">
                                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>Dibuat: <?= htmlspecialchars($item['formattedCreatedAt'] ?? '-') ?></span>
                                </div>
                            </div>

                        </div>
                    </div>

                <?php } ?>

            <?php } else { ?>

                <!-- Empty State -->
                <div class="bg-white dark:bg-[#181818] border-4 border-dashed
                border-[#121212] dark:border-gray-600 p-10 sm:p-16 text-center">

                    <div class="mx-auto w-20 h-20 flex items-center justify-center
                    bg-[#00d982] border-4 border-[#121212] shadow-[5px_5px_0_0_#121212]">
                        <svg class="w-10 h-10 text-[#121212]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>

                    <?php if (!empty($startDate) && !empty($endDate)): ?>

                        <!-- Empty saat FILTER AKTIF → hanya tombol Reset -->
                        <h3 class="mt-7 font-black text-2xl uppercase text-[#121212] dark:text-white">
                            Tidak Ada Laporan di Rentang Ini
                        </h3>
                        <p class="mt-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                            Coba ubah rentang tanggal atau reset filter.
                        </p>

                        <a href="/reports" class="inline-flex items-center gap-2 mt-6 px-5 py-3
                       bg-gray-200 dark:bg-[#222] text-[#121212] dark:text-white
                       border-4 border-[#121212] dark:border-white
                       font-black uppercase text-sm
                       shadow-[5px_5px_0_0_#121212] dark:shadow-[5px_5px_0_0_#00d982]
                       hover:shadow-none hover:translate-x-[5px] hover:translate-y-[5px]
                       transition-all duration-150">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Reset Filter
                        </a>

                    <?php else: ?>

                        <!-- Empty saat TANPA FILTER (DB kosong) → hanya Buat Laporan -->
                        <h3 class="mt-7 font-black text-2xl uppercase text-[#121212] dark:text-white">
                            Belum Ada Laporan
                        </h3>
                        <p class="mt-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                            Silakan tambahkan laporan harian baru melalui tombol di atas.
                        </p>

                        <a href="/report/add" class="inline-flex items-center gap-2 mt-6 px-5 py-3
                       bg-[#121212] text-white border-4 border-[#121212]
                       font-black uppercase text-sm
                       shadow-[5px_5px_0_0_#00d982] hover:shadow-none
                       hover:translate-x-[5px] hover:translate-y-[5px]
                       transition-all duration-150">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                            </svg>
                            Buat Laporan
                        </a>

                    <?php endif; ?>

                </div>

            <?php } ?>

        </div>

    </div>
</div>

<!-- ============================== -->
<!-- MODAL KONFIRMASI HAPUS -->
<!-- ============================== -->
<div id="delete-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4
           bg-black/70 backdrop-blur-sm">

    <div id="delete-modal-content" class="w-full max-w-md bg-white dark:bg-[#181818]
               border-4 border-[#121212] dark:border-white
               shadow-[8px_8px_0_0_#121212] dark:shadow-[8px_8px_0_0_#00d982]
               transform scale-95 transition-transform duration-200">

        <!-- Header -->
        <div class="flex items-center gap-3 p-5 border-b-4 border-[#121212] dark:border-white">
            <div class="w-10 h-10 flex items-center justify-center
                        bg-red-500 border-2 border-[#121212]">
                <svg class="w-5 h-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <h3 class="font-black uppercase text-lg text-[#121212] dark:text-white">
                Konfirmasi Hapus
            </h3>
        </div>

        <!-- Body -->
        <div class="p-5">
            <p class="text-sm font-bold text-[#121212] dark:text-white">
                Yakin ingin menghapus laporan:
            </p>
            <p id="delete-modal-date" class="mt-2 px-3 py-2 bg-gray-100 dark:bg-[#222]
                       border-2 border-[#121212] dark:border-white
                       font-black text-sm text-[#121212] dark:text-white">
                -
            </p>
            <p class="mt-3 text-xs font-bold text-red-600 dark:text-red-400 uppercase">
                Peringatan: seluruh rincian kegiatan di dalamnya akan ikut terhapus.
            </p>
        </div>

        <!-- Actions -->
        <div class="flex gap-3 p-5 border-t-4 border-[#121212] dark:border-white">
            <button type="button" id="delete-cancel" class="flex-1 px-4 py-3 bg-gray-200 dark:bg-[#222] text-[#121212] dark:text-white
                       border-4 border-[#121212] dark:border-white
                       font-black uppercase text-sm
                       shadow-[4px_4px_0_0_#121212] dark:shadow-[4px_4px_0_0_#00d982]
                       hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px]
                       transition-all">
                Batal
            </button>
            <button type="button" id="delete-confirm" class="flex-1 px-4 py-3 bg-red-500 text-white
                       border-4 border-[#121212]
                       font-black uppercase text-sm
                       shadow-[4px_4px_0_0_#121212]
                       hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px]
                       transition-all">
                Ya, Hapus
            </button>
        </div>

    </div>
</div>

<!-- ============================== -->
<!-- MODAL DUPLIKAT LAPORAN -->
<!-- ============================== -->
<div id="duplicate-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4
           bg-black/70 backdrop-blur-sm">

    <div id="duplicate-modal-content" class="w-full max-w-md
               bg-white dark:bg-[#181818]
               border-4 border-[#121212] dark:border-white
               shadow-[8px_8px_0_0_#121212] dark:shadow-[8px_8px_0_0_#00d982]
               transform scale-95 transition-transform duration-200">

        <!-- Header -->
        <div class="flex items-center gap-3 p-5
                    border-b-4 border-[#121212] dark:border-white">
            <div class="w-10 h-10 flex items-center justify-center
                        bg-blue-500 border-2 border-[#121212]">
                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <rect width="14" height="14" x="8" y="8" rx="2" ry="2" />
                    <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" />
                </svg>
            </div>
            <h3 class="font-black uppercase text-lg
                       text-[#121212] dark:text-white">
                Duplikat Laporan
            </h3>
        </div>

        <!-- Form -->
        <form action="" method="POST" id="duplicate-form">
            <!-- Action URL di-set via JS -->

            <div class="p-5 space-y-4">
                <p class="text-sm font-bold text-[#121212] dark:text-white">
                    Duplikat laporan dari:
                </p>

                <p id="duplicate-source-date" class="px-3 py-2
                           bg-gray-100 dark:bg-[#222]
                           border-2 border-[#121212] dark:border-white
                           font-black text-sm
                           text-[#121212] dark:text-white">
                    -
                </p>

                <div>
                    <label for="target-date" class="block mb-2 text-xs font-black uppercase
                               text-[#121212] dark:text-white">
                        Tanggal Target <span class="text-red-500">*</span>
                    </label>

                    <input type="date" id="target-date" name="target_date" required class="w-full px-4 py-3
                               bg-white dark:bg-[#222]
                               text-[#121212] dark:text-white
                               border-4 border-[#121212] dark:border-white
                               font-bold
                               outline-none
                               focus:ring-4 focus:ring-[#00d982]">

                    <p class="mt-2 text-xs font-bold
                              text-gray-500 dark:text-gray-400">
                        Tanggal ini harus belum punya laporan. Semua kegiatan akan disalin.
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-3 p-5
                        border-t-4 border-[#121212] dark:border-white">

                <button type="button" id="duplicate-cancel" class="flex-1 px-4 py-3
                           bg-gray-200 dark:bg-[#222]
                           text-[#121212] dark:text-white
                           border-4 border-[#121212] dark:border-white
                           font-black uppercase text-sm
                           shadow-[4px_4px_0_0_#121212] dark:shadow-[4px_4px_0_0_#00d982]
                           hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px]
                           transition-all cursor-pointer">
                    Batal
                </button>

                <button type="submit" class="flex-1 px-4 py-3
                           bg-blue-500 text-white
                           border-4 border-[#121212]
                           font-black uppercase text-sm
                           shadow-[4px_4px_0_0_#121212]
                           hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px]
                           transition-all cursor-pointer">
                    Duplikat
                </button>

            </div>

        </form>

    </div>
</div>

<script>
    // ==============================
    // Pencarian Realtime
    // ==============================
    const searchInput = document.getElementById('search_reports');
    const searchClear = document.getElementById('search-clear');
    const searchInfo = document.getElementById('search-info');
    const searchCount = document.getElementById('search-count');
    const searchTotal = document.getElementById('search-total');
    const reportList = document.getElementById('report-list');
    const reportCards = document.querySelectorAll('.report-card');

    searchTotal.textContent = reportCards.length;

    function filterReports() {
        const keyword = searchInput.value.trim().toLowerCase();
        let visible = 0;

        reportCards.forEach(card => {
            const haystack = card.dataset.search || '';
            const match = keyword === '' || haystack.includes(keyword);

            card.style.display = match ? '' : 'none';
            if (match) visible++;
        });

        if (keyword !== '') {
            searchInfo.classList.remove('hidden');
            searchCount.textContent = visible;
        } else {
            searchInfo.classList.add('hidden');
        }

        // Tampilkan / sembunyikan tombol clear
        if (keyword !== '') {
            searchClear.classList.remove('hidden');
        } else {
            searchClear.classList.add('hidden');
        }

        // Pesan kosong saat pencarian tidak ketemu
        const existing = document.getElementById('empty-search');

        if (visible === 0 && keyword !== '') {
            if (!existing && reportList) {
                const div = document.createElement('div');
                div.id = 'empty-search';
                div.className = 'bg-white dark:bg-[#181818] border-4 border-dashed border-[#121212] dark:border-gray-600 p-10 text-center';
                div.innerHTML = `
                <p class="font-black uppercase text-xl text-[#121212] dark:text-white">
                    Tidak ada hasil untuk "<span class="text-[#00d982]">${keyword}</span>"
                </p>
                <p class="mt-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                    Coba kata kunci lain.
                </p>
            `;
                reportList.appendChild(div);
            }
        } else {
            if (existing) existing.remove();
        }
    }

    searchInput.addEventListener('input', filterReports);

    searchClear.addEventListener('click', () => {
        searchInput.value = '';
        filterReports();
        searchInput.focus();
    });

    // ==============================
    // Modal Konfirmasi Hapus
    // ==============================
    (() => {
        const modal = document.getElementById('delete-modal');
        const modalContent = document.getElementById('delete-modal-content');
        const modalDate = document.getElementById('delete-modal-date');
        const btnCancel = document.getElementById('delete-cancel');
        const btnConfirm = document.getElementById('delete-confirm');

        if (!modal) return;

        let pendingForm = null;

        function openModal(form, date) {
            pendingForm = form;
            modalDate.textContent = date;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            // Animasi scale-in
            requestAnimationFrame(() => {
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            });
        }

        function closeModal() {
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            document.body.style.overflow = '';
            pendingForm = null;

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }

        // Buka modal saat tombol hapus diklik
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const form = btn.closest('.delete-form');
                const date = btn.dataset.date || 'Laporan ini';
                openModal(form, date);
            });
        });

        // Batal
        btnCancel.addEventListener('click', closeModal);

        // Klik backdrop → tutup
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });

        // ESC → tutup
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });

        // Konfirmasi hapus
        btnConfirm.addEventListener('click', () => {
            if (pendingForm) pendingForm.submit();
        });
    })();

    // ==============================
    // Flash Message Close
    // ==============================
    const flash = document.getElementById('flash-message');
    const flashClose = document.getElementById('flash-close');

    if (flash && flashClose) {
        function dismissFlash() {
            flash.style.opacity = '0';
            flash.style.transform = 'translateY(-10px)';
            setTimeout(() => flash.remove(), 200);
        }

        flashClose.addEventListener('click', dismissFlash);

        // (Opsional) Auto-dismiss setelah 5 detik
        // setTimeout(dismissFlash, 5000);
    }

    // ==============================
    // Toggle Panel Filter (Tools)
    // ==============================
    (() => {
        const toggleBtn = document.getElementById('filter-toggle');
        const panel = document.getElementById('filter-panel');
        const closeBtn = document.getElementById('filter-close');

        if (!toggleBtn || !panel) return;

        function openPanel() {
            panel.classList.remove('hidden');
            toggleBtn.classList.add('hidden');   // ← sembunyikan tombol Tools
        }

        function closePanel() {
            panel.classList.add('hidden');
            toggleBtn.classList.remove('hidden'); // ← munculkan lagi
        }

        toggleBtn.addEventListener('click', openPanel);
        closeBtn?.addEventListener('click', closePanel);
    })();


    // ==============================
    // Validasi Rentang Tanggal + Enable/Disable Tombol Tampilkan
    // ==============================
    (() => {
        const form = document.querySelector('form[action="/reports"]');
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        const submitBtn = document.getElementById('filter-submit');
        const hint = document.getElementById('filter-hint');

        if (!form || !startDate || !endDate) return;

        const MAX_DAYS = 31;

        function markError(input, message) {
            input.classList.add('border-red-600', 'bg-red-50', 'ring-4', 'ring-red-300');
            input.setCustomValidity(message);
            input.reportValidity();
        }

        function clearError(input) {
            input.classList.remove('border-red-600', 'bg-red-50', 'ring-4', 'ring-red-300');
            input.setCustomValidity('');
        }

        // Update state tombol submit
        function updateSubmitState() {
            const bothFilled = startDate.value !== '' && endDate.value !== '';
            const valid = bothFilled && endDate.value >= startDate.value;

            // === Tombol Tampilkan ===
            if (submitBtn) {
                submitBtn.disabled = !bothFilled;
            }

            // === Tombol Cetak PDF ===
            const printBtn = document.getElementById('printRangeButton');
            if (printBtn) {
                if (valid) {
                    // Aktifkan
                    printBtn.href = `/report/print/pdf/${startDate.value}/${endDate.value}`;
                    printBtn.classList.remove('pointer-events-none', 'opacity-40', 'cursor-not-allowed');
                    printBtn.classList.add('hover:shadow-none', 'hover:translate-x-[5px]', 'hover:translate-y-[5px]');
                    printBtn.removeAttribute('aria-disabled');
                } else {
                    // Disable
                    printBtn.href = '#';
                    printBtn.classList.add('pointer-events-none', 'opacity-40', 'cursor-not-allowed');
                    printBtn.classList.remove('hover:shadow-none', 'hover:translate-x-[5px]', 'hover:translate-y-[5px]');
                    printBtn.setAttribute('aria-disabled', 'true');
                }
            }

            // === Hint ===
            if (hint) {
                if (bothFilled) {
                    hint.classList.add('hidden');
                } else {
                    hint.classList.remove('hidden');
                    hint.innerHTML = 'Isi <span class="font-black text-[#121212] dark:text-white">Tanggal Mulai</span> dan <span class="font-black text-[#121212] dark:text-white">Tanggal Akhir</span> untuk mengaktifkan tombol Tampilkan & Cetak PDF.';
                }
            }
        }

        function validateRange() {
            clearError(startDate);
            clearError(endDate);

            if (!startDate.value || !endDate.value) return true;

            // Cek end < start
            if (endDate.value < startDate.value) {
                markError(endDate, 'Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.');
                return false;
            }

            // Cek rentang maks 31 hari
            const s = new Date(startDate.value);
            const e = new Date(endDate.value);
            const diffDays = Math.floor((e - s) / (1000 * 60 * 60 * 24)) + 1;

            if (diffDays > MAX_DAYS) {
                markError(endDate, `Rentang maksimal ${MAX_DAYS} hari. Rentang Anda: ${diffDays} hari.`);
                return false;
            }

            return true;
        }

        // Event: start_date
        startDate.addEventListener('change', function () {
            if (startDate.value) {
                endDate.min = startDate.value;
            } else {
                endDate.removeAttribute('min');
            }
            updateSubmitState();
            validateRange();
        });

        // Event: end_date
        endDate.addEventListener('change', function () {
            if (endDate.value) {
                startDate.max = endDate.value;
            } else {
                startDate.removeAttribute('max');
            }
            updateSubmitState();
            validateRange();
        });

        // Event: input (realtime, mis. saat user paste/clear)
        startDate.addEventListener('input', updateSubmitState);
        endDate.addEventListener('input', updateSubmitState);

        // Submit handler
        form.addEventListener('submit', function (e) {
            if (!startDate.value || !endDate.value) {
                e.preventDefault();
                startDate.focus();
                return;
            }
            if (!validateRange()) {
                e.preventDefault();
                endDate.focus();
            }
        });

        // Init: cek state saat halaman load (mis. setelah reload dengan filter)
        updateSubmitState();
    })();

    /* ==========================================================
 * MODAL DUPLIKAT LAPORAN
 * ========================================================== */
    (() => {
        const modal = document.getElementById('duplicate-modal');
        const modalContent = document.getElementById('duplicate-modal-content');
        const form = document.getElementById('duplicate-form');
        const sourceDate = document.getElementById('duplicate-source-date');
        const targetDateInput = document.getElementById('target-date');
        const btnCancel = document.getElementById('duplicate-cancel');

        if (!modal || !form) return;

        function openModal(reportId, reportDate) {
            // Set form action
            form.action = '/report/duplicate/' + reportId;

            // Set source date (read-only label)
            sourceDate.textContent = reportDate;

            // Reset target date
            targetDateInput.value = '';

            // Tampilkan modal
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            requestAnimationFrame(() => {
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            });

            // Auto-focus ke input tanggal
            setTimeout(() => targetDateInput.focus(), 100);
        }

        function closeModal() {
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            document.body.style.overflow = '';

            setTimeout(() => modal.classList.add('hidden'), 150);
        }

        // Buka modal saat tombol duplikat diklik
        document.querySelectorAll('.duplicate-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const reportId = this.dataset.reportId;
                const reportDate = this.dataset.reportDate;

                openModal(reportId, reportDate);
            });
        });

        // Batal
        btnCancel.addEventListener('click', closeModal);

        // Klik backdrop → tutup
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });

        // ESC → tutup
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });

    })();
</script>