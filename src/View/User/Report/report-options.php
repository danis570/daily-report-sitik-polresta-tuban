<?php
/**
 * View: report-options.php
 * Variabel dari controller:
 *   $title   (string)
 *   $options (array of object)  — { id, category, name, description }
 *   $usage   (array)            — [optionId => totalUsage]
 *   $error   (string|null)
 */

use Unirow2026\DailyReportSitikPolrestaTuban\App\View;

$categories = [
    'target' => 'SASARAN',
    'activity' => 'KEGIATAN',
    'personnel_strength' => 'KUAT PERSONEL',
    'location' => 'LOKASI',
    'person_in_charge' => 'PENANGGUNG JAWAB',
    'expected_result' => 'HASIL YANG INGIN DICAPAI',
];
?>

<div class="min-h-screen bg-gray-50 dark:bg-[#121212] py-12 px-4 sm:px-6 lg:px-8">

    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-5 mb-8">
            <div>
                <h1 class="text-4xl sm:text-5xl font-black uppercase tracking-tight
                           text-black dark:text-white">
                    <?= htmlspecialchars($title ?? 'Kelola Pilihan Laporan') ?>
                </h1>
                <p class="mt-3 text-sm sm:text-base font-medium
                          text-gray-600 dark:text-gray-400 max-w-2xl">
                    Daftar kategori dan opsi dinamis untuk kebutuhan laporan kegiatan.
                </p>
            </div>

            <a href="/report/option/add" class="inline-flex items-center justify-center gap-2
                       px-5 py-3
                       bg-[#00d982] text-black
                       border-2 border-black
                       font-black uppercase text-sm
                       shadow-brutal
                       hover:translate-x-[6px] hover:translate-y-[6px]
                       hover:shadow-none
                       transition-all duration-150">
                <span class="text-lg leading-none">+</span>
                Tambah Opsi
            </a>
        </div>


        <!-- Error Alert -->
        <?php if (!empty($error)): ?>
            <div id="error-message" class="mb-6 p-4
                       bg-red-500 text-white
                       border-2 border-black dark:border-white
                       shadow-brutal dark:shadow-[6px_6px_0px_0px_#00d982]
                       flex items-start gap-3
                       transition-all duration-200">
                <div class="shrink-0 w-7 h-7
                            bg-white text-red-500
                            border-2 border-black
                            flex items-center justify-center
                            font-black">
                    !
                </div>

                <div class="flex-1 min-w-0">
                    <p class="font-black uppercase text-sm">Terjadi Kesalahan</p>
                    <p class="text-sm font-medium mt-1 break-words">
                        <?= htmlspecialchars($error) ?>
                    </p>
                </div>

                <button type="button" id="error-close" class="shrink-0 w-7 h-7 flex items-center justify-center
                           bg-black text-white border-2 border-black
                           hover:bg-white hover:text-red-500
                           transition-colors cursor-pointer" aria-label="Tutup notifikasi">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 6l12 12M6 18L18 6" />
                    </svg>
                </button>
            </div>
        <?php endif; ?>


        <!-- Success Flash -->
        <?php if (!empty($_SESSION['flash_message'])): ?>
            <div id="flash-message" class="mb-6 p-4
                       bg-[#00d982] text-black
                       border-2 border-black dark:border-white
                       shadow-brutal dark:shadow-[6px_6px_0px_0px_#00d982]
                       flex items-start gap-3
                       transition-all duration-200">
                <div class="shrink-0 w-7 h-7
                            bg-black text-[#00d982]
                            border-2 border-black
                            flex items-center justify-center
                            font-black">
                    ✓
                </div>

                <div class="flex-1 min-w-0">
                    <p class="font-black uppercase text-sm">Berhasil</p>
                    <p class="text-sm font-bold mt-1 break-words">
                        <?= htmlspecialchars($_SESSION['flash_message']) ?>
                    </p>
                </div>

                <button type="button" id="flash-close" class="shrink-0 w-7 h-7 flex items-center justify-center
                           bg-black text-[#00d982] border-2 border-black
                           hover:bg-white hover:text-black
                           transition-colors cursor-pointer" aria-label="Tutup notifikasi">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 6l12 12M6 18L18 6" />
                    </svg>
                </button>
            </div>
            <?php View::clearFlashMessage(); ?>
        <?php endif; ?>


        <!-- Search & Filter -->
        <div class="mb-6 px-5 py-4
                    bg-white dark:bg-[#181818]
                    border-2 border-black dark:border-white
                    shadow-brutal dark:shadow-[6px_6px_0px_0px_#00d982]">

            <div class="grid grid-cols-1 md:grid-cols-[1fr_220px_auto] gap-3">

                <!-- Search -->
                <div>
                    <label for="searchOption" class="block mb-2 text-xs font-black uppercase tracking-wider
                               text-black dark:text-white">
                        Cari Opsi
                    </label>

                    <input type="text" id="searchOption" placeholder="Cari nama, deskripsi, ID..." class="w-full px-4 py-3
                               bg-white dark:bg-[#222]
                               text-black dark:text-white
                               border-2 border-black dark:border-white
                               font-bold text-sm
                               outline-none
                               focus:ring-4 focus:ring-[#00d982]
                               shadow-[3px_3px_0px_0px_#000] dark:shadow-[3px_3px_0px_0px_#00d982]">
                </div>

                <!-- Category -->
                <div>
                    <label for="filterCategory" class="block mb-2 text-xs font-black uppercase tracking-wider
                               text-black dark:text-white">
                        Kategori
                    </label>

                    <select id="filterCategory" class="w-full px-4 py-3
                               bg-white dark:bg-[#222]
                               text-black dark:text-white
                               border-2 border-black dark:border-white
                               font-bold text-sm
                               outline-none
                               focus:ring-4 focus:ring-[#00d982]
                               shadow-[3px_3px_0px_0px_#000] dark:shadow-[3px_3px_0px_0px_#00d982]">
                        <option value="">Semua Kategori</option>
                        <?php foreach ($categories as $value => $label): ?>
                            <option value="<?= htmlspecialchars($value) ?>">
                                <?= htmlspecialchars($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Reset -->
                <div class="flex items-end">
                    <button type="button" id="resetFilter" class="w-full md:w-auto
                               px-5 py-3
                               bg-white dark:bg-[#222]
                               text-black dark:text-white
                               border-2 border-black dark:border-white
                               font-black text-sm uppercase
                               shadow-[3px_3px_0px_0px_#000] dark:shadow-[3px_3px_0px_0px_#00d982]
                               hover:translate-x-[3px] hover:translate-y-[3px] hover:shadow-none
                               transition-all duration-150 cursor-pointer">
                        Reset
                    </button>
                </div>

            </div>

            <!-- Counter -->
            <div class="mt-4 flex items-center justify-between">
                <p class="text-xs font-bold text-gray-600 dark:text-gray-400">
                    Menampilkan
                    <span id="visibleCount" class="text-black dark:text-white font-black">
                        <?= count($options ?? []) ?>
                    </span>
                    dari
                    <span class="font-black text-black dark:text-white">
                        <?= count($options ?? []) ?>
                    </span>
                    opsi
                </p>
            </div>

        </div>


        <!-- Table Card -->
        <div class="bg-white dark:bg-[#181818]
                    border-2 border-black dark:border-white
                    shadow-brutal dark:shadow-[6px_6px_0px_0px_#00d982]
                    overflow-hidden">

            <!-- Table Header -->
            <div class="flex items-center justify-between
                        px-5 py-4
                        bg-black text-white
                        dark:bg-[#00d982] dark:text-black
                        border-b-2 border-black dark:border-white">

                <div>
                    <h2 class="font-black uppercase tracking-wide text-sm sm:text-base">
                        Daftar Opsi Laporan
                    </h2>
                    <p class="text-xs text-gray-300 dark:text-black/70 mt-1">
                        Kelola pilihan yang tersedia untuk aktivitas laporan.
                    </p>
                </div>

                <div class="bg-[#00d982] text-black
                            dark:bg-black dark:text-[#00d982]
                            border-2 border-white dark:border-black
                            px-3 py-1 font-black text-xs">
                    <?= count($options ?? []) ?> OPSI
                </div>

            </div>


            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">

                    <thead class="bg-gray-100 dark:bg-[#222]
                                  border-b-2 border-black dark:border-white">
                        <tr class="text-left">
                            <th class="px-5 py-4 font-black uppercase tracking-wider text-xs
                                       text-black dark:text-white">
                                ID
                            </th>
                            <th class="px-5 py-4 font-black uppercase tracking-wider text-xs
                                       text-black dark:text-white">
                                Kategori
                            </th>
                            <th class="px-5 py-4 font-black uppercase tracking-wider text-xs
                                       text-black dark:text-white">
                                Nama Opsi
                            </th>
                            <th class="px-5 py-4 font-black uppercase tracking-wider text-xs
                                       text-black dark:text-white">
                                Deskripsi
                            </th>
                            <th class="px-5 py-4 font-black uppercase tracking-wider text-xs
                                       text-black dark:text-white text-center">
                                Dilaksanakan
                            </th>
                            <th class="px-5 py-4 font-black uppercase tracking-wider text-xs
                                       text-black dark:text-white text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>


                    <tbody class="divide-y-2 divide-black dark:divide-white">

                        <?php if (!empty($options)): ?>

                            <?php foreach ($options as $option): ?>

                                <tr class="option-row
                                           hover:bg-[#eafff5] dark:hover:bg-[#00d982]/10
                                           transition-colors duration-150" data-id="<?= $option->id ?>"
                                    data-category="<?= htmlspecialchars(strtolower($option->category)) ?>"
                                    data-name="<?= htmlspecialchars(strtolower($option->name)) ?>"
                                    data-description="<?= htmlspecialchars(strtolower($option->description ?? '')) ?>">

                                    <!-- ID -->
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <span class="inline-block
                                                     bg-black text-white
                                                     dark:bg-white dark:text-black
                                                     px-2 py-1
                                                     font-mono font-bold text-xs">
                                            #<?= $option->id ?>
                                        </span>
                                    </td>

                                    <!-- Kategori (Indonesia) -->
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <span class="inline-flex
                                                     bg-[#00d982] text-black
                                                     border-2 border-black
                                                     px-2.5 py-1
                                                     text-xs font-black uppercase">
                                            <?= htmlspecialchars($categories[$option->category] ?? $option->category) ?>
                                        </span>
                                    </td>

                                    <!-- Nama Opsi -->
                                    <td class="px-5 py-4">
                                        <div class="font-black text-black dark:text-white">
                                            <?= htmlspecialchars($option->name) ?>
                                        </div>
                                    </td>

                                    <!-- Deskripsi -->
                                    <td class="px-5 py-4">
                                        <div class="text-gray-600 dark:text-gray-400
                                                    font-medium max-w-md">
                                            <?= htmlspecialchars($option->description ?? '-') ?>
                                        </div>
                                    </td>

                                    <!-- Dilaksanakan -->
                                    <td class="px-5 py-4 text-center">
                                        <?php $totalUsage = $usage[$option->id] ?? 0; ?>
                                        <span
                                            class="inline-flex items-center justify-center
                                                     min-w-[70px]
                                                     px-3 py-2
                                                     <?= $totalUsage > 0
                                                         ? 'bg-[#00d982]'
                                                         : 'bg-gray-100 dark:bg-[#222]' ?>
                                                     text-black dark:text-white
                                                     border-2 border-black dark:border-white
                                                     font-black text-xs
                                                     shadow-[3px_3px_0px_0px_#000] dark:shadow-[3px_3px_0px_0px_#00d982]">
                                            <?= $totalUsage ?>x
                                        </span>
                                    </td>

                                    <!-- Aksi -->
                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-center gap-2">

                                            <a href="/report/option/edit/<?= $option->id ?>" class="inline-flex items-center
                                                       px-3 py-2
                                                       bg-white dark:bg-[#222]
                                                       text-black dark:text-white
                                                       border-2 border-black dark:border-white
                                                       font-black text-xs uppercase
                                                       shadow-[3px_3px_0px_0px_#000] dark:shadow-[3px_3px_0px_0px_#00d982]
                                                       hover:translate-x-[3px] hover:translate-y-[3px]
                                                       hover:shadow-none
                                                       transition-all duration-150">
                                                Edit
                                            </a>

                                            <form action="/report/option/delete/<?= $option->id ?>" method="POST"
                                                class="delete-form inline">
                                                <button type="button" class="delete-btn inline-flex items-center
                                                           px-3 py-2
                                                           bg-red-500 text-white
                                                           border-2 border-black dark:border-white
                                                           font-black text-xs uppercase
                                                           shadow-[3px_3px_0px_0px_#000] dark:shadow-[3px_3px_0px_0px_#00d982]
                                                           hover:translate-x-[3px] hover:translate-y-[3px]
                                                           hover:shadow-none
                                                           transition-all duration-150 cursor-pointer"
                                                    data-id="<?= $option->id ?>"
                                                    data-name="<?= htmlspecialchars($option->name) ?>"
                                                    data-category="<?= htmlspecialchars($categories[$option->category] ?? $option->category) ?>">
                                                    Hapus
                                                </button>
                                            </form>

                                        </div>
                                    </td>

                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <!-- Empty State -->
                            <tr>
                                <td colspan="6" class="px-5 py-16">
                                    <div class="flex flex-col items-center justify-center text-center">

                                        <div class="w-16 h-16
                                                    bg-gray-100 dark:bg-[#222]
                                                    border-2 border-black dark:border-white
                                                    flex items-center justify-center
                                                    text-3xl font-black
                                                    text-black dark:text-white
                                                    shadow-brutal dark:shadow-[6px_6px_0px_0px_#00d982]
                                                    mb-5">
                                            ?
                                        </div>

                                        <h3 class="text-xl font-black uppercase
                                                   text-black dark:text-white">
                                            Belum Ada Opsi
                                        </h3>

                                        <p class="text-sm text-gray-500 dark:text-gray-400 font-medium mt-2">
                                            Tidak ada data opsi laporan yang dapat ditampilkan.
                                        </p>

                                        <a href="/report/option/add" class="mt-5 inline-flex px-4 py-2
                                                   bg-[#00d982] text-black
                                                   border-2 border-black
                                                   font-black text-sm uppercase
                                                   shadow-brutal
                                                   hover:translate-x-[6px] hover:translate-y-[6px]
                                                   hover:shadow-none
                                                   transition-all duration-150">
                                            + Tambah Opsi
                                        </a>

                                    </div>
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>
            </div>

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
        <div class="flex items-center gap-3 p-5
                    border-b-4 border-[#121212] dark:border-white">
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
                Yakin ingin menghapus opsi:
            </p>

            <p id="delete-modal-name" class="mt-2 px-3 py-2
                       bg-gray-100 dark:bg-[#222]
                       border-2 border-[#121212] dark:border-white
                       font-black text-sm
                       text-[#121212] dark:text-white">
                -
            </p>

            <p id="delete-modal-category" class="mt-2 text-xs font-black uppercase
                       text-gray-500 dark:text-gray-400">
                -
            </p>

            <p class="mt-3 text-xs font-bold text-red-600 dark:text-red-400 uppercase">
                Peringatan: opsi yang masih dipakai di rincian giat tidak dapat dihapus.
            </p>
        </div>

        <!-- Actions -->
        <div class="flex gap-3 p-5 border-t-4 border-[#121212] dark:border-white">
            <button type="button" id="delete-cancel" class="flex-1 px-4 py-3
                       bg-gray-200 dark:bg-[#222]
                       text-[#121212] dark:text-white
                       border-4 border-[#121212] dark:border-white
                       font-black uppercase text-sm
                       shadow-[4px_4px_0_0_#121212] dark:shadow-[4px_4px_0_0_#00d982]
                       hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px]
                       transition-all cursor-pointer">
                Batal
            </button>
            <button type="button" id="delete-confirm" class="flex-1 px-4 py-3
                       bg-red-500 text-white
                       border-4 border-[#121212]
                       font-black uppercase text-sm
                       shadow-[4px_4px_0_0_#121212]
                       hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px]
                       transition-all cursor-pointer">
                Ya, Hapus
            </button>
        </div>

    </div>
</div>


<!-- ============================== -->
<!-- SCRIPT -->
<!-- ============================== -->
<script>
    document.addEventListener('DOMContentLoaded', function () {

        /* ==========================================================
         * 1. FILTER & SEARCH
         * ========================================================== */
        const searchInput = document.getElementById('searchOption');
        const categoryFilter = document.getElementById('filterCategory');
        const resetButton = document.getElementById('resetFilter');

        const rows = document.querySelectorAll('.option-row');
        const visibleCount = document.getElementById('visibleCount');

        function filterOptions() {
            const search = searchInput.value.trim().toLowerCase();
            const category = categoryFilter.value.toLowerCase();

            let count = 0;

            rows.forEach(row => {
                const id = row.dataset.id;
                const rowCategory = row.dataset.category;
                const name = row.dataset.name;
                const description = row.dataset.description;

                const matchSearch =
                    id.includes(search) ||
                    rowCategory.includes(search) ||
                    name.includes(search) ||
                    description.includes(search);

                const matchCategory =
                    !category || rowCategory === category;

                if (matchSearch && matchCategory) {
                    row.style.display = '';
                    count++;
                } else {
                    row.style.display = 'none';
                }
            });

            visibleCount.textContent = count;
        }

        searchInput.addEventListener('input', filterOptions);
        categoryFilter.addEventListener('change', filterOptions);

        resetButton.addEventListener('click', function (event) {
            event.preventDefault();
            searchInput.value = '';
            categoryFilter.value = '';
            filterOptions();
        });


        /* ==========================================================
         * 2. TOMBOL CLOSE — FLASH MESSAGE
         * ========================================================== */
        (() => {
            const flash = document.getElementById('flash-message');
            const flashClose = document.getElementById('flash-close');

            if (!flash || !flashClose) return;

            flashClose.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                flash.style.opacity = '0';
                flash.style.transform = 'translateY(-10px)';

                setTimeout(() => flash.remove(), 200);
            });
        })();


        /* ==========================================================
         * 3. TOMBOL CLOSE — ERROR MESSAGE
         * ========================================================== */
        (() => {
            const error = document.getElementById('error-message');
            const errorClose = document.getElementById('error-close');

            if (!error || !errorClose) return;

            errorClose.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                error.style.opacity = '0';
                error.style.transform = 'translateY(-10px)';

                setTimeout(() => error.remove(), 200);
            });
        })();


        /* ==========================================================
         * 4. MODAL KONFIRMASI HAPUS
         * ========================================================== */
        (() => {
            const modal = document.getElementById('delete-modal');
            const modalContent = document.getElementById('delete-modal-content');
            const modalName = document.getElementById('delete-modal-name');
            const modalCategory = document.getElementById('delete-modal-category');
            const btnCancel = document.getElementById('delete-cancel');
            const btnConfirm = document.getElementById('delete-confirm');

            if (!modal) return;

            let pendingForm = null;

            function openModal(form, name, category) {
                pendingForm = form;
                modalName.textContent = name;
                modalCategory.textContent = category;

                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';

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

                setTimeout(() => modal.classList.add('hidden'), 150);
            }

            // Buka modal saat tombol hapus diklik
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const form = this.closest('.delete-form');
                    const name = this.dataset.name || 'Opsi ini';
                    const category = this.dataset.category || '';

                    openModal(form, name, category);
                });
            });

            // Batal
            btnCancel.addEventListener('click', closeModal);

            // Klik backdrop → tutup
            modal.addEventListener('click', function (e) {
                if (e.target === modal) closeModal();
            });

            // ESC → tutup
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });

            // Konfirmasi hapus
            btnConfirm.addEventListener('click', function () {
                if (pendingForm) pendingForm.submit();
            });
        })();

    });
</script>