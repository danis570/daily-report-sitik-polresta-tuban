<div class="min-h-screen bg-gray-50 dark:bg-[#121212] pt-24 pb-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="mb-10">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 mb-4">
                        <span class="w-3 h-3 bg-[#00d982] border-2 border-black"></span>
                        <span class="text-xs font-black uppercase tracking-[0.2em] text-gray-600 dark:text-gray-400">
                            Daily Report
                        </span>
                    </div>

                    <h1
                        class="font-black text-4xl sm:text-5xl uppercase tracking-tight text-[#121212] dark:text-white leading-none">
                        <?= htmlspecialchars($title ?? 'Kelola Laporan Harian') ?>
                    </h1>

                    <p class="mt-3 text-sm sm:text-base font-medium text-gray-600 dark:text-gray-400 max-w-2xl">
                        Daftar rekapitulasi laporan harian SITIK Polresta Tuban.
                    </p>
                </div>

                <!-- Container Tombol -->
                <div class="flex flex-wrap lg:flex-nowrap items-center gap-4">
                    <a href="/report/add"
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#00d982] text-[#121212] font-black uppercase text-sm border-4 border-[#121212] shadow-[6px_6px_0_0_#121212] hover:shadow-none hover:translate-x-[6px] hover:translate-y-[6px] transition-all duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M12 5v14M5 12h14" />
                        </svg>
                        <span>Tambah Laporan</span>
                    </a>

                    <a href="/report/options"
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#00d982] text-[#121212] font-black uppercase text-sm border-4 border-[#121212] shadow-[6px_6px_0_0_#121212] hover:shadow-none hover:translate-x-[6px] hover:translate-y-[6px] transition-all duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span>Opsi Jawaban</span>
                    </a>

                    <a href="/report/tracking"
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#00d982] text-[#121212] font-black uppercase text-sm border-4 border-[#121212] shadow-[6px_6px_0_0_#121212] hover:shadow-none hover:translate-x-[6px] hover:translate-y-[6px] transition-all duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M3 3v18h18M7 16l4-5 3 3 5-7" />
                        </svg>
                        <span>Pelacakan Laporan</span>
                    </a>
                </div>
            </div>

            <div class="mt-8 border-b-4 border-[#121212] dark:border-white"></div>
        </div>

        <!-- Error -->
        <?php if (!empty($error)) { ?>
            <div class="mb-8 p-5 bg-yellow-300 border-4 border-[#121212] shadow-[6px_6px_0_0_#121212]" role="alert">
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

        <!-- Filter & Cetak -->
        <div class="mb-10">
            <div
                class="bg-white dark:bg-[#181818] border-4 border-[#121212] dark:border-white shadow-[6px_6px_0_0_#121212] dark:shadow-[6px_6px_0_0_#00d982]">
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
                                Pilih rentang tanggal untuk menyaring laporan atau mencetak beberapa laporan sekaligus.
                            </p>
                        </div>
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

                        <!-- Tombol Reset (sejajar dengan date, muncul hanya jika ada filter) -->
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

                        <!-- Tombol Filter -->
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-yellow-400 text-black border-4 border-black font-black uppercase text-sm shadow-[5px_5px_0_0_#121212] hover:shadow-none hover:translate-x-[5px] hover:translate-y-[5px] transition-all duration-150">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M3 4h18M6 10h12M10 16h4" />
                            </svg>
                            Tampilkan
                        </button>

                        <!-- Tombol Cetak Banyak -->
                        <a href="/report/pdf" id="printRangeButton" target="_blank"
                            class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-[#00d982] text-black border-4 border-black font-black uppercase text-sm shadow-[5px_5px_0_0_#121212] hover:shadow-none hover:translate-x-[5px] hover:translate-y-[5px] transition-all duration-150">
                            <i data-lucide="printer" class="w-5 h-5"></i>
                            Cetak PDF
                        </a>

                    </form>

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
                            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" />
                                </svg>
                            </div>
                        </div>
                        <p id="search-info" class="mt-2 text-xs font-bold text-gray-500 dark:text-gray-400 hidden">
                            Menampilkan <span id="search-count">0</span> dari <span id="search-total">0</span> laporan
                        </p>
                    </div>

                </div>
            </div>
        </div>

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

                                        <a href="/report/print/pdf/<?= $item['report']->reportDate->format('Y-m-d') ?>"
                                            target="_blank"
                                            class="flex-1 sm:flex-none text-center px-3 py-1.5 bg-[#00d982] text-black border-2 border-black font-black uppercase text-xs shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all">
                                            <span class="inline-flex items-center gap-1">
                                                <i data-lucide="printer" class="w-4 h-4"></i>
                                                Cetak
                                            </span>
                                        </a>

                                        <form action="/report/delete/<?= $item['report']->id ?>" method="POST"
                                            onsubmit="return confirm('PERINGATAN: Menghapus laporan ini akan menghapus seluruh rincian kegiatan di dalamnya! Hapus?');"
                                            class="flex-1 sm:flex-none inline">
                                            <button type="submit"
                                                class="w-full text-center px-3 py-1.5 bg-red-500 text-white border-2 border-black font-black uppercase text-xs shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all">
                                                Hapus
                                            </button>
                                        </form>

                                    </div>

                                    <a href="/report/<?= $item['report']->reportDate->format('Y-m-d') ?>"
                                        class="w-full sm:w-12 h-10 flex items-center justify-center bg-[#00d982] text-[#121212] border-2 border-[#121212] shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-x-0.5 hover:-translate-y-0.5 transition-transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                                <div class="flex items-center gap-2 text-xs font-bold text-gray-500 dark:text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span><?= $item['report']->createdAt->format('H:i:s') ?> WIB</span>
                                </div>
                            </div>

                        </div>
                    </div>

                <?php } ?>

            <?php } else { ?>

                <!-- Empty State -->
                <div
                    class="bg-white dark:bg-[#181818] border-4 border-dashed border-[#121212] dark:border-gray-600 p-10 sm:p-16 text-center">
                    <div
                        class="mx-auto w-20 h-20 flex items-center justify-center bg-[#00d982] border-4 border-[#121212] shadow-[5px_5px_0_0_#121212]">
                        <svg class="w-10 h-10 text-[#121212]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>

                    <h3 class="mt-7 font-black text-2xl uppercase text-[#121212] dark:text-white">
                        Belum Ada Laporan
                    </h3>

                    <p class="mt-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                        Silakan tambahkan laporan harian baru melalui tombol di atas.
                    </p>

                    <a href="/report/add"
                        class="inline-flex items-center gap-2 mt-6 px-5 py-3 bg-[#121212] text-white border-4 border-[#121212] font-black uppercase text-sm shadow-[5px_5px_0_0_#00d982] hover:shadow-none hover:translate-x-[5px] hover:translate-y-[5px] transition-all duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" />
                        </svg>
                        Buat Laporan
                    </a>
                </div>

            <?php } ?>

        </div>

    </div>
</div>

<script>
    // ==============================
    // Cetak PDF Range
    // ==============================
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const printRangeButton = document.getElementById('printRangeButton');

    function updatePrintRangeUrl() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;

        if (startDate && endDate) {
            printRangeButton.href = `/report/print/pdf/${startDate}/${endDate}`;
        } else {
            printRangeButton.href = '#';
        }
    }

    startDateInput.addEventListener('change', updatePrintRangeUrl);
    endDateInput.addEventListener('change', updatePrintRangeUrl);
    updatePrintRangeUrl();


    // ==============================
    // Pencarian Realtime
    // ==============================
    const searchInput = document.getElementById('search_reports');
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
</script>