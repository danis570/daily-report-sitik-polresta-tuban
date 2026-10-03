<div class="min-h-screen bg-gray-50 dark:bg-[#121212] pt-12 pb-16 px-4 sm:px-6 lg:px-8">

    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="mb-6">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">
                <div>
                    <h1 class="font-black text-4xl sm:text-5xl uppercase tracking-tight
                               text-[#121212] dark:text-white leading-none">
                        <?= htmlspecialchars($title ?? 'Pelacakan Laporan') ?>
                    </h1>
                    <p class="mt-3 text-sm sm:text-base font-medium
                              text-gray-600 dark:text-gray-400 max-w-2xl">
                        Lacak penggunaan opsi laporan berdasarkan kategori dan periode tertentu.
                    </p>
                </div>
            </div>
        </div>

        <!-- Error -->
        <?php if (!empty($error)): ?>
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
                        <p class="font-black uppercase text-sm">Terjadi Kesalahan</p>
                        <p class="mt-1 font-bold text-sm"><?= htmlspecialchars($error) ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Filter Pelacakan -->
        <div class="mb-10">
            <div class="bg-white dark:bg-[#181818] border-4 border-[#121212] dark:border-white
                        shadow-[6px_6px_0_0_#121212] dark:shadow-[6px_6px_0_0_#00d982]">
                <div class="p-6 sm:p-7">

                    <!-- Section Header -->
                    <div class="mb-6">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="w-3 h-3 bg-[#00d982] border-2 border-black"></span>
                            <span class="text-xs font-black uppercase tracking-[0.2em]
                                         text-gray-600 dark:text-gray-400">
                                Tracking
                            </span>
                        </div>
                        <h2 class="font-black text-xl sm:text-2xl uppercase tracking-tight
                                   text-[#121212] dark:text-white">
                            Pelacakan Penggunaan Opsi
                        </h2>
                        <p class="mt-1 text-sm font-medium text-gray-500 dark:text-gray-400">
                            Pilih kategori, opsi, dan periode untuk mengetahui jumlah penggunaannya.
                        </p>
                    </div>

                    <?php
                    // Nilai terpilih: prioritas POST → GET fallback
                    $currentCategory = $_POST['category']   ?? $selectedCategory ?? '';
                    $currentOptionId = $_POST['option_id']  ?? $selectedOptionId ?? '';
                    $currentStart    = $_POST['start_date'] ?? $selectedStart    ?? '';
                    $currentEnd      = $_POST['end_date']   ?? $selectedEnd      ?? '';
                    ?>

                    <!-- Form -->
                    <form action="/report/tracking" method="POST">

                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">

                            <!-- KATEGORI (Searchable) -->
                            <div class="searchable-select" data-type="category">
                                <label class="block mb-2 text-xs font-black uppercase tracking-wider
                                              text-[#121212] dark:text-white">
                                    Kategori
                                </label>

                                <div class="relative">
                                    <input type="text" autocomplete="off"
                                        placeholder="Ketik untuk mencari kategori..."
                                        class="search-input w-full px-4 py-3 pr-12
                                               bg-white dark:bg-[#222]
                                               text-[#121212] dark:text-white
                                               border-4 border-[#121212] dark:border-white
                                               font-bold outline-none
                                               focus:ring-4 focus:ring-[#00d982]">

                                    <button type="button"
                                        class="search-clear hidden absolute right-3 top-1/2 -translate-y-1/2
                                               w-7 h-7 items-center justify-center
                                               bg-[#121212] text-white border-2 border-[#121212]
                                               hover:bg-[#00d982] hover:text-[#121212]
                                               transition-colors cursor-pointer"
                                        aria-label="Hapus kategori">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="3"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M6 6l12 12M6 18L18 6" />
                                        </svg>
                                    </button>

                                    <input type="hidden" name="category" class="selected-value"
                                        value="<?= htmlspecialchars($currentCategory) ?>" required>

                                    <div class="search-results hidden absolute z-50
                                                left-0 right-0 mt-1
                                                bg-white dark:bg-[#222]
                                                border-4 border-[#121212] dark:border-white
                                                max-h-60 overflow-y-auto
                                                shadow-[5px_5px_0_0_#121212] dark:shadow-[5px_5px_0_0_#00d982]">

                                        <?php foreach ($categories as $value => $label): ?>
                                            <button type="button"
                                                class="search-option w-full text-left px-4 py-3
                                                       border-b-2 border-[#121212] dark:border-white
                                                       font-bold
                                                       text-[#121212] dark:text-white
                                                       hover:bg-[#00d982] hover:text-black
                                                       transition-colors"
                                                data-value="<?= htmlspecialchars($value) ?>"
                                                data-label="<?= htmlspecialchars($label) ?>">
                                                <?= htmlspecialchars($label) ?>
                                            </button>
                                        <?php endforeach; ?>

                                    </div>
                                </div>
                            </div>

                            <!-- OPSI (Searchable) -->
                            <div class="searchable-select" data-type="option">
                                <label class="block mb-2 text-xs font-black uppercase tracking-wider
                                              text-[#121212] dark:text-white">
                                    Opsi
                                </label>

                                <div class="relative">
                                    <input type="text" autocomplete="off"
                                        placeholder="<?= empty($options) ? 'Pilih kategori dulu...' : 'Ketik untuk mencari opsi...' ?>"
                                        class="search-input w-full px-4 py-3 pr-12
                                               bg-white dark:bg-[#222]
                                               text-[#121212] dark:text-white
                                               border-4 border-[#121212] dark:border-white
                                               font-bold outline-none
                                               focus:ring-4 focus:ring-[#00d982]"
                                        <?= empty($options) ? 'disabled' : '' ?>>

                                    <button type="button"
                                        class="search-clear hidden absolute right-3 top-1/2 -translate-y-1/2
                                               w-7 h-7 items-center justify-center
                                               bg-[#121212] text-white border-2 border-[#121212]
                                               hover:bg-[#00d982] hover:text-[#121212]
                                               transition-colors cursor-pointer"
                                        aria-label="Hapus opsi">
                                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="3"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M6 6l12 12M6 18L18 6" />
                                        </svg>
                                    </button>

                                    <input type="hidden" name="option_id" class="selected-value"
                                        value="<?= htmlspecialchars($currentOptionId) ?>" required>

                                    <div class="search-results hidden absolute z-50
                                                left-0 right-0 mt-1
                                                bg-white dark:bg-[#222]
                                                border-4 border-[#121212] dark:border-white
                                                max-h-60 overflow-y-auto
                                                shadow-[5px_5px_0_0_#121212] dark:shadow-[5px_5px_0_0_#00d982]">

                                        <?php foreach ($options as $opt): ?>
                                            <button type="button"
                                                class="search-option w-full text-left px-4 py-3
                                                       border-b-2 border-[#121212] dark:border-white
                                                       font-bold
                                                       text-[#121212] dark:text-white
                                                       hover:bg-[#00d982] hover:text-black
                                                       transition-colors"
                                                data-value="<?= htmlspecialchars($opt->id) ?>"
                                                data-label="<?= htmlspecialchars($opt->name) ?>">
                                                <?= htmlspecialchars($opt->name) ?>
                                            </button>
                                        <?php endforeach; ?>

                                    </div>
                                </div>
                            </div>

                            <!-- Tanggal Mulai -->
                            <div>
                                <label for="start_date"
                                    class="block mb-2 text-xs font-black uppercase tracking-wider
                                           text-[#121212] dark:text-white">
                                    Tanggal Mulai
                                </label>
                                <input type="date" id="start_date" name="start_date" required
                                    value="<?= htmlspecialchars($currentStart) ?>"
                                    class="w-full px-4 py-3 bg-white dark:bg-[#222]
                                           text-[#121212] dark:text-white
                                           border-4 border-[#121212] dark:border-white
                                           font-bold outline-none focus:ring-4 focus:ring-[#00d982]">
                            </div>

                            <!-- Tanggal Akhir -->
                            <div>
                                <label for="end_date"
                                    class="block mb-2 text-xs font-black uppercase tracking-wider
                                           text-[#121212] dark:text-white">
                                    Tanggal Akhir
                                </label>
                                <input type="date" id="end_date" name="end_date" required
                                    value="<?= htmlspecialchars($currentEnd) ?>"
                                    class="w-full px-4 py-3 bg-white dark:bg-[#222]
                                           text-[#121212] dark:text-white
                                           border-4 border-[#121212] dark:border-white
                                           font-bold outline-none focus:ring-4 focus:ring-[#00d982]">
                            </div>

                        </div>

                        <!-- Tombol -->
                        <div class="mt-6 flex flex-wrap items-center gap-4">
                            <button type="submit"
                                class="inline-flex items-center justify-center gap-2 px-5 py-3
                                       bg-[#00d982] text-[#121212] border-4 border-[#121212]
                                       font-black uppercase text-sm
                                       shadow-[5px_5px_0_0_#121212]
                                       hover:shadow-none hover:translate-x-[5px] hover:translate-y-[5px]
                                       transition-all duration-150">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z" />
                                </svg>
                                Lacak Laporan
                            </button>

                            <a href="/report/tracking"
                                class="inline-flex items-center justify-center gap-2 px-5 py-3
                                       bg-white dark:bg-[#181818] text-[#121212] dark:text-white
                                       border-4 border-[#121212] dark:border-white
                                       font-black uppercase text-sm
                                       shadow-[5px_5px_0_0_#121212] dark:shadow-[5px_5px_0_0_#00d982]
                                       hover:shadow-none hover:translate-x-[5px] hover:translate-y-[5px]
                                       transition-all duration-150">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Reset
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <!-- Hasil Pelacakan -->
        <?php if (!empty($result)): ?>
            <?php
            // Helper lokal — format tanggal Indonesia
            $bulanIndo = [
                1  => 'Januari', 2  => 'Februari', 3  => 'Maret',
                4  => 'April',   5  => 'Mei',      6  => 'Juni',
                7  => 'Juli',    8  => 'Agustus',  9  => 'September',
                10 => 'Oktober', 11 => 'November', 12 => 'Desember',
            ];

            $formatIndo = function ($dateStr) use ($bulanIndo) {
                $d = new DateTimeImmutable($dateStr);
                return $d->format('d') . ' ' . $bulanIndo[(int) $d->format('n')] . ' ' . $d->format('Y');
            };
            ?>

            <div class="mb-10">
                <div class="bg-white dark:bg-[#181818] border-4 border-[#121212] dark:border-white
                            shadow-[6px_6px_0_0_#121212] dark:shadow-[6px_6px_0_0_#00d982]">
                    <div class="p-6 sm:p-7">

                        <div class="flex items-center gap-2 mb-6">
                            <span class="w-3 h-3 bg-[#00d982] border-2 border-black"></span>
                            <span class="text-xs font-black uppercase tracking-[0.2em]
                                         text-gray-600 dark:text-gray-400">
                                Hasil Pelacakan
                            </span>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-8 items-center">

                            <div>
                                <p class="text-xs font-black uppercase tracking-wider
                                          text-gray-500 dark:text-gray-400">
                                    Opsi yang dilacak
                                </p>

                                <h2 class="mt-2 text-2xl sm:text-3xl font-black uppercase
                                           text-[#121212] dark:text-white">
                                    <?= htmlspecialchars($result->optionName) ?>
                                </h2>

                                <div class="mt-5 flex flex-wrap gap-3">

                                    <span class="inline-flex items-center px-3 py-2
                                                 bg-gray-100 dark:bg-[#222]
                                                 border-2 border-[#121212] dark:border-gray-600
                                                 text-xs font-black uppercase
                                                 text-[#121212] dark:text-white">
                                        <?= htmlspecialchars($categories[$result->category] ?? $result->category) ?>
                                    </span>

                                    <span class="inline-flex items-center px-3 py-2
                                                 bg-gray-100 dark:bg-[#222]
                                                 border-2 border-[#121212] dark:border-gray-600
                                                 text-xs font-black
                                                 text-[#121212] dark:text-white">
                                        <?= htmlspecialchars($formatIndo($result->startDate)) ?>
                                        <span class="mx-2">→</span>
                                        <?= htmlspecialchars($formatIndo($result->endDate)) ?>
                                    </span>

                                </div>
                            </div>

                            <div class="min-w-[180px] bg-[#00d982] border-4 border-[#121212]
                                        shadow-[5px_5px_0_0_#121212] p-5 text-center">
                                <p class="text-xs font-black uppercase tracking-wider">Total Digunakan</p>
                                <div class="mt-2 text-6xl sm:text-7xl font-black leading-none text-[#121212]">
                                    <?= (int) $result->total ?>
                                </div>
                                <p class="mt-2 text-sm font-black uppercase text-[#121212]">Kali</p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>


<!-- ============================== -->
<!-- MODAL VALIDASI -->
<!-- ============================== -->
<div id="validationModal" class="fixed inset-0 z-[9999] hidden
           items-center justify-center
           bg-black/70 px-4">
    <div class="w-full max-w-md
               bg-white dark:bg-[#181818]
               border-4 border-black dark:border-white
               shadow-[10px_10px_0px_0px_#00d982]">

        <div class="flex items-center justify-between
                   bg-black text-white
                   dark:bg-[#00d982] dark:text-black
                   px-5 py-4
                   border-b-4 border-black dark:border-white">

            <div class="flex items-center gap-3">

                <div class="w-10 h-10
                           bg-[#00d982] text-black
                           dark:bg-black dark:text-[#00d982]
                           border-2 border-white dark:border-black
                           flex items-center justify-center
                           font-black text-xl">
                    !
                </div>

                <h3 class="font-black uppercase tracking-wide">
                    Data Belum Lengkap
                </h3>

            </div>

            <button type="button" id="closeValidationModal" class="w-9 h-9
                       bg-white text-black
                       dark:bg-[#222] dark:text-white
                       border-2 border-white dark:border-white
                       font-black
                       hover:bg-[#00d982] hover:text-black
                       transition-colors cursor-pointer">
                ×
            </button>

        </div>

        <div class="p-6">

            <p id="validationMessage"
                class="font-bold text-gray-700 dark:text-gray-300 leading-relaxed">
                Silakan lengkapi seluruh data yang diperlukan.
            </p>

        </div>

        <div class="px-6 pb-6 flex justify-end">

            <button type="button" id="confirmValidationModal" class="px-6 py-3
                       bg-[#00d982] text-black
                       border-2 border-black
                       font-black uppercase
                       shadow-[5px_5px_0px_0px_#000]
                       hover:shadow-none
                       hover:translate-x-1 hover:translate-y-1
                       transition-all cursor-pointer">
                Mengerti
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
     * 1. SEARCHABLE DROPDOWN + TOMBOL CLEAR
     * ========================================================== */
    document.querySelectorAll('.searchable-select').forEach(function (container) {

        const input       = container.querySelector('.search-input');
        const hiddenInput = container.querySelector('.selected-value');
        const results     = container.querySelector('.search-results');
        const options     = container.querySelectorAll('.search-option');
        const clearBtn    = container.querySelector('.search-clear');

        // Tampil / sembunyikan tombol clear
        function updateClearBtn() {
            if (!clearBtn) return;

            if (input.value.trim() !== '') {
                clearBtn.classList.remove('hidden');
                clearBtn.classList.add('flex');
            } else {
                clearBtn.classList.add('hidden');
                clearBtn.classList.remove('flex');
            }
        }

        // Set nilai awal dari hidden input
        const selectedValue = hiddenInput.value;
        if (selectedValue) {
            options.forEach(function (opt) {
                if (opt.dataset.value === selectedValue) {
                    input.value = opt.dataset.label;
                }
            });
        }
        updateClearBtn();

        // Fokus input → buka dropdown
        input.addEventListener('focus', function () {
            if (input.disabled) return;
            results.classList.remove('hidden');
            filterOptions();
        });

        // User mengetik → reset pilihan & filter
        input.addEventListener('input', function () {
            hiddenInput.value = '';
            input.classList.remove('border-red-600', 'bg-red-50');
            results.classList.remove('hidden');
            filterOptions();
            updateClearBtn();
        });

        // Pilih opsi dari dropdown
        options.forEach(function (opt) {
            opt.addEventListener('click', function () {
                input.value       = this.dataset.label;
                hiddenInput.value = this.dataset.value;
                results.classList.add('hidden');
                input.classList.remove('border-red-600', 'bg-red-50');
                updateClearBtn();

                // Khusus kategori: reload halaman (GET) — pertahankan tanggal
                if (container.dataset.type === 'category') {
                    const params = new URLSearchParams();
                    params.set('category', this.dataset.value);

                    const sd = document.getElementById('start_date')?.value;
                    const ed = document.getElementById('end_date')?.value;
                    if (sd) params.set('start_date', sd);
                    if (ed) params.set('end_date', ed);

                    window.location.href = '/report/tracking?' + params.toString();
                }
            });
        });

        // Klik tombol clear (×)
        if (clearBtn) {
            clearBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                input.value       = '';
                hiddenInput.value = '';

                input.classList.remove('border-red-600', 'bg-red-50');

                results.classList.add('hidden');
                updateClearBtn();

                input.focus();

                // Khusus kategori: reload halaman (reset opsi) — tetap pertahankan tanggal
                if (container.dataset.type === 'category') {
                    const params = new URLSearchParams();

                    const sd = document.getElementById('start_date')?.value;
                    const ed = document.getElementById('end_date')?.value;
                    if (sd) params.set('start_date', sd);
                    if (ed) params.set('end_date', ed);

                    window.location.href = '/report/tracking' +
                        (params.toString() ? '?' + params.toString() : '');
                }
            });
        }

        // Filter realtime
        function filterOptions() {
            const keyword = input.value.toLowerCase().trim();
            let found = false;

            options.forEach(function (opt) {
                const label = opt.dataset.label.toLowerCase();
                if (label.includes(keyword)) {
                    opt.classList.remove('hidden');
                    found = true;
                } else {
                    opt.classList.add('hidden');
                }
            });

            let emptyMessage = results.querySelector('.no-result');
            if (!found) {
                if (!emptyMessage) {
                    emptyMessage = document.createElement('div');
                    emptyMessage.className =
                        'no-result px-4 py-3 text-sm font-black text-gray-500 dark:text-gray-400';
                    emptyMessage.textContent = 'Tidak ada hasil ditemukan.';
                    results.appendChild(emptyMessage);
                }
            } else if (emptyMessage) {
                emptyMessage.remove();
            }
        }
    });

    // Tutup dropdown saat klik di luar
    document.addEventListener('click', function (e) {
        document.querySelectorAll('.searchable-select').forEach(function (container) {
            if (!container.contains(e.target)) {
                container.querySelector('.search-results').classList.add('hidden');
            }
        });
    });


    /* ==========================================================
     * 2. VALIDASI KATEGORI & OPSI WAJIB DIISI
     * ========================================================== */
    (() => {
        const form         = document.querySelector('form[action="/report/tracking"]');
        const modal        = document.getElementById('validationModal');
        const modalMessage = document.getElementById('validationMessage');
        const btnClose     = document.getElementById('closeValidationModal');
        const btnConfirm   = document.getElementById('confirmValidationModal');

        if (!form || !modal) return;

        function openModal(message) {
            modalMessage.textContent = message;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        // Tutup modal + fokus ke field invalid + BUKA dropdown
function handleModalClose(e) {
    if (e) {
        e.stopPropagation();  // ← cegah klik bubble ke document
        e.preventDefault();
    }

    closeModal();
    focusInvalid();

    // Buka dropdown field invalid (kalau ada)
    const input = document.querySelector('.search-input[data-focus-after-close="true"]');
    if (input) {
        const container = input.closest('.searchable-select');
        const results = container?.querySelector('.search-results');
        if (results && !input.disabled) {
            results.classList.remove('hidden');
        }
        delete input.dataset.focusAfterClose;
        input.focus();
    }
}

btnClose.addEventListener('click', handleModalClose);
btnConfirm.addEventListener('click', handleModalClose);

        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });

        // Validasi saat submit
        form.addEventListener('submit', function (e) {
            const categoryInput = form.querySelector('input[name="category"]');
            const optionInput   = form.querySelector('input[name="option_id"]');

            let firstInvalid = null;
            let message = '';

            // Cek kategori
            if (!categoryInput.value) {
                message = 'Silakan pilih Kategori terlebih dahulu.';
                const el = categoryInput.closest('.searchable-select')
                    .querySelector('.search-input');
                el.classList.add('border-red-600', 'bg-red-50');
                firstInvalid = el;
            }

            // Cek opsi
            if (!firstInvalid && !optionInput.value) {
                message = 'Silakan pilih Opsi terlebih dahulu.';
                const el = optionInput.closest('.searchable-select')
                    .querySelector('.search-input');
                el.classList.add('border-red-600', 'bg-red-50');
                firstInvalid = el;
            }

            if (firstInvalid) {
                e.preventDefault();
                openModal(message);
                firstInvalid.dataset.focusAfterClose = 'true';
                firstInvalid.focus();
                return;
            }

            // Semua valid → hapus border error
            form.querySelectorAll('.search-input').forEach(function (el) {
                el.classList.remove('border-red-600', 'bg-red-50');
            });
        });

        // Fokus ke field invalid setelah modal ditutup
        function focusInvalid() {
            const input = document.querySelector('.search-input[data-focus-after-close="true"]');

            if (input) {
                delete input.dataset.focusAfterClose;
                input.focus();
            }
        }

    })();

});
</script>