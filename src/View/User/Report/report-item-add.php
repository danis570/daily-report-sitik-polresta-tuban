<div class="min-h-screen bg-gray-50 dark:bg-[#121212] py-12 px-4 sm:px-6 lg:px-8">

    <div class="max-w-4xl mx-auto">

        <!-- Breadcrumb -->
        <nav aria-label="Breadcrumb" class="mb-6 pl-2">
            <ol class="flex flex-wrap items-center gap-2 text-sm font-bold
                       text-gray-700 dark:text-gray-300">

                <li>
                    <a href="/reports" class="hover:text-[#00d982] transition-colors">
                        Laporan
                    </a>
                </li>

                <li aria-hidden="true" class="text-gray-400 dark:text-gray-600 flex items-center">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </li>

                <li>
                    <a href="/report/<?= htmlspecialchars($date) ?>" class="hover:text-[#00d982] transition-colors">
                        <?= htmlspecialchars($formattedDate) ?>
                    </a>
                </li>

                <li aria-hidden="true" class="text-gray-400 dark:text-gray-600 flex items-center">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </li>

                <li>
                    <span aria-current="page" class="text-black dark:text-white">
                        Tambah Giat
                    </span>
                </li>

            </ol>
        </nav>


        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-4xl sm:text-5xl font-black uppercase tracking-tight
                       text-black dark:text-white">
                Tambah Item Giat
            </h1>

            <p class="mt-3 text-gray-600 dark:text-gray-400 font-medium">
                Menambahkan rincian kegiatan baru untuk laporan tanggal
                <span class="font-black text-black dark:text-white">
                    <?= htmlspecialchars($formattedDate) ?>
                </span>
            </p>
        </div>


        <!-- Error -->
        <?php if (!empty($error)): ?>
            <div class="mb-6 p-5
                        bg-red-100 dark:bg-red-900/30
                        border-2 border-black dark:border-white
                        shadow-[6px_6px_0px_0px_#000] dark:shadow-[6px_6px_0px_0px_#00d982]">
                <p class="font-black uppercase text-red-700 dark:text-red-400">
                    Terjadi Kesalahan
                </p>
                <p class="mt-1 text-sm font-bold text-black dark:text-white">
                    <?= htmlspecialchars($error) ?>
                </p>
            </div>
        <?php endif; ?>


        <!-- Form Card -->
        <div class="bg-white dark:bg-[#181818]
                    border-2 border-black dark:border-white
                    shadow-[8px_8px_0px_0px_#000] dark:shadow-[8px_8px_0px_0px_#00d982]">

            <div class="p-6 sm:p-8">

                <form action="/report/item/<?= htmlspecialchars($date) ?>/add" method="POST">

                    <input type="hidden" name="report_id" value="<?= htmlspecialchars($report->id) ?>">


                    <!-- Section Header -->
                    <div class="flex items-center gap-4 mb-8">

                        <div class="w-12 h-12 shrink-0
                                    bg-[#00d982] border-4 border-black
                                    flex items-center justify-center
                                    font-black text-black
                                    shadow-[4px_4px_0px_0px_#000]">
                            01
                        </div>

                        <div>
                            <h2 class="text-2xl font-black uppercase text-black dark:text-white">
                                Rincian Giat
                            </h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400 font-bold">
                                Pilih data untuk aktivitas laporan
                            </p>
                        </div>

                    </div>


                    <!-- Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Nomor Giat -->
                        <div>
                            <label for="item_no"
                                class="block mb-2 text-sm font-black uppercase text-black dark:text-white">
                                Nomor Giat <span class="text-red-500">*</span>
                            </label>

                            <input type="number" id="item_no" name="item_no" min="1"
                                placeholder="Masukkan nomor urut giat."
                                value="<?= htmlspecialchars($nextItemNo ?? 1) ?>" required class="w-full px-4 py-3
                                       bg-gray-50 dark:bg-[#222]
                                       text-black dark:text-white
                                       border-4 border-black dark:border-white
                                       font-bold text-lg
                                       outline-none
                                       focus:shadow-[4px_4px_0_0_#00d982]
                                       transition-shadow">

                            <p class="mt-2 text-xs font-bold text-gray-500 dark:text-gray-400">
                                Nomor urut giat. Harus unik dalam 1 laporan.
                            </p>
                        </div>


                        <!-- TARGET -->
                        <div class="searchable-select" data-category="target">
                            <label class="block mb-2 text-sm font-black uppercase text-black dark:text-white">
                                Sasaran
                            </label>

                            <div class="relative">
                                <input type="text" autocomplete="off" placeholder="Ketik untuk mencari sasaran..."
                                    class="search-input w-full px-4 py-3 pr-12
                                           bg-gray-50 dark:bg-[#222]
                                           border-2 border-black dark:border-white
                                           font-bold text-black dark:text-white
                                           outline-none
                                           focus:bg-white dark:focus:bg-[#2a2a2a]
                                           focus:shadow-[4px_4px_0px_0px_#00d982]
                                           transition-shadow">

                                <button type="button" class="search-clear hidden absolute right-3 top-1/2 -translate-y-1/2
                                           w-7 h-7 items-center justify-center
                                           bg-black dark:bg-[#00d982]
                                           text-white dark:text-black
                                           border-2 border-black dark:border-[#00d982]
                                           hover:bg-[#00d982] hover:text-black
                                           transition-colors cursor-pointer" aria-label="Hapus sasaran">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 6l12 12M6 18L18 6" />
                                    </svg>
                                </button>

                                <input type="hidden" name="target_option_id" class="selected-value" required>

                                <div class="search-results hidden absolute z-50
                                            left-0 right-0 mt-1
                                            bg-white dark:bg-[#222]
                                            border-2 border-black dark:border-white
                                            max-h-60 overflow-y-auto
                                            shadow-[5px_5px_0px_0px_#000] dark:shadow-[5px_5px_0px_0px_#00d982]">

                                    <?php foreach ($options as $option): ?>
                                        <?php if (
                                            $option->category !== 'target' &&
                                            $option->category !== 'Sasaran'
                                        )
                                            continue; ?>

                                        <button type="button" class="search-option w-full text-left px-4 py-3
                                                   border-b-2 border-black dark:border-white
                                                   font-bold text-black dark:text-white
                                                   hover:bg-[#00d982] hover:text-black
                                                   transition-colors" data-value="<?= htmlspecialchars($option->id) ?>"
                                            data-label="<?= htmlspecialchars($option->name) ?>">
                                            <?= htmlspecialchars($option->name) ?>
                                        </button>
                                    <?php endforeach; ?>

                                </div>
                            </div>
                        </div>


                        <!-- ACTIVITY -->
                        <div class="searchable-select" data-category="activity">
                            <label class="block mb-2 text-sm font-black uppercase text-black dark:text-white">
                                Giat / Aktivitas
                            </label>

                            <div class="relative">
                                <input type="text" autocomplete="off" placeholder="Ketik untuk mencari giat..." class="search-input w-full px-4 py-3 pr-12
                                           bg-gray-50 dark:bg-[#222]
                                           border-2 border-black dark:border-white
                                           font-bold text-black dark:text-white
                                           outline-none
                                           focus:bg-white dark:focus:bg-[#2a2a2a]
                                           focus:shadow-[4px_4px_0px_0px_#00d982]
                                           transition-shadow">

                                <button type="button" class="search-clear hidden absolute right-3 top-1/2 -translate-y-1/2
                                           w-7 h-7 items-center justify-center
                                           bg-black dark:bg-[#00d982]
                                           text-white dark:text-black
                                           border-2 border-black dark:border-[#00d982]
                                           hover:bg-[#00d982] hover:text-black
                                           transition-colors cursor-pointer" aria-label="Hapus giat">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 6l12 12M6 18L18 6" />
                                    </svg>
                                </button>

                                <input type="hidden" name="activity_option_id" class="selected-value" required>

                                <div class="search-results hidden absolute z-50
                                            left-0 right-0 mt-1
                                            bg-white dark:bg-[#222]
                                            border-2 border-black dark:border-white
                                            max-h-60 overflow-y-auto
                                            shadow-[5px_5px_0px_0px_#000] dark:shadow-[5px_5px_0px_0px_#00d982]">

                                    <?php foreach ($options as $option): ?>
                                        <?php if (
                                            $option->category !== 'activity' &&
                                            $option->category !== 'Kegiatan' &&
                                            $option->category !== 'Giat'
                                        )
                                            continue; ?>

                                        <button type="button" class="search-option w-full text-left px-4 py-3
                                                   border-b-2 border-black dark:border-white
                                                   font-bold text-black dark:text-white
                                                   hover:bg-[#00d982] hover:text-black
                                                   transition-colors" data-value="<?= htmlspecialchars($option->id) ?>"
                                            data-label="<?= htmlspecialchars($option->name) ?>">
                                            <?= htmlspecialchars($option->name) ?>
                                        </button>
                                    <?php endforeach; ?>

                                </div>
                            </div>
                        </div>


                        <!-- PERSONNEL -->
                        <div class="searchable-select" data-category="personnel_strength">
                            <label class="block mb-2 text-sm font-black uppercase text-black dark:text-white">
                                Kuat Personel
                            </label>

                            <div class="relative">
                                <input type="text" autocomplete="off" placeholder="Ketik untuk mencari personel..."
                                    class="search-input w-full px-4 py-3 pr-12
                                           bg-gray-50 dark:bg-[#222]
                                           border-2 border-black dark:border-white
                                           font-bold text-black dark:text-white
                                           outline-none
                                           focus:bg-white dark:focus:bg-[#2a2a2a]
                                           focus:shadow-[4px_4px_0px_0px_#00d982]
                                           transition-shadow">

                                <button type="button" class="search-clear hidden absolute right-3 top-1/2 -translate-y-1/2
                                           w-7 h-7 items-center justify-center
                                           bg-black dark:bg-[#00d982]
                                           text-white dark:text-black
                                           border-2 border-black dark:border-[#00d982]
                                           hover:bg-[#00d982] hover:text-black
                                           transition-colors cursor-pointer" aria-label="Hapus personel">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 6l12 12M6 18L18 6" />
                                    </svg>
                                </button>

                                <input type="hidden" name="personnel_strength_option_id" class="selected-value"
                                    required>

                                <div class="search-results hidden absolute z-50
                                            left-0 right-0 mt-1
                                            bg-white dark:bg-[#222]
                                            border-2 border-black dark:border-white
                                            max-h-60 overflow-y-auto
                                            shadow-[5px_5px_0px_0px_#000] dark:shadow-[5px_5px_0px_0px_#00d982]">

                                    <?php foreach ($options as $option): ?>
                                        <?php if (
                                            $option->category !== 'personnel_strength' &&
                                            $option->category !== 'Personel' &&
                                            $option->category !== 'Kuat Personel'
                                        )
                                            continue; ?>

                                        <button type="button" class="search-option w-full text-left px-4 py-3
                                                   border-b-2 border-black dark:border-white
                                                   font-bold text-black dark:text-white
                                                   hover:bg-[#00d982] hover:text-black
                                                   transition-colors" data-value="<?= htmlspecialchars($option->id) ?>"
                                            data-label="<?= htmlspecialchars($option->name) ?>">
                                            <?= htmlspecialchars($option->name) ?>
                                        </button>
                                    <?php endforeach; ?>

                                </div>
                            </div>
                        </div>


                        <!-- LOCATION -->
                        <div class="searchable-select" data-category="location">
                            <label class="block mb-2 text-sm font-black uppercase text-black dark:text-white">
                                Lokasi
                            </label>

                            <div class="relative">
                                <input type="text" autocomplete="off" placeholder="Ketik untuk mencari lokasi..." class="search-input w-full px-4 py-3 pr-12
                                           bg-gray-50 dark:bg-[#222]
                                           border-2 border-black dark:border-white
                                           font-bold text-black dark:text-white
                                           outline-none
                                           focus:bg-white dark:focus:bg-[#2a2a2a]
                                           focus:shadow-[4px_4px_0px_0px_#00d982]
                                           transition-shadow">

                                <button type="button" class="search-clear hidden absolute right-3 top-1/2 -translate-y-1/2
                                           w-7 h-7 items-center justify-center
                                           bg-black dark:bg-[#00d982]
                                           text-white dark:text-black
                                           border-2 border-black dark:border-[#00d982]
                                           hover:bg-[#00d982] hover:text-black
                                           transition-colors cursor-pointer" aria-label="Hapus lokasi">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 6l12 12M6 18L18 6" />
                                    </svg>
                                </button>

                                <input type="hidden" name="location_option_id" class="selected-value" required>

                                <div class="search-results hidden absolute z-50
                                            left-0 right-0 mt-1
                                            bg-white dark:bg-[#222]
                                            border-2 border-black dark:border-white
                                            max-h-60 overflow-y-auto
                                            shadow-[5px_5px_0px_0px_#000] dark:shadow-[5px_5px_0px_0px_#00d982]">

                                    <?php foreach ($options as $option): ?>
                                        <?php if (
                                            $option->category !== 'location' &&
                                            $option->category !== 'Lokasi'
                                        )
                                            continue; ?>

                                        <button type="button" class="search-option w-full text-left px-4 py-3
                                                   border-b-2 border-black dark:border-white
                                                   font-bold text-black dark:text-white
                                                   hover:bg-[#00d982] hover:text-black
                                                   transition-colors" data-value="<?= htmlspecialchars($option->id) ?>"
                                            data-label="<?= htmlspecialchars($option->name) ?>">
                                            <?= htmlspecialchars($option->name) ?>
                                        </button>
                                    <?php endforeach; ?>

                                </div>
                            </div>
                        </div>


                        <!-- PIC -->
                        <div class="searchable-select" data-category="person_in_charge">
                            <label class="block mb-2 text-sm font-black uppercase text-black dark:text-white">
                                Penanggung Jawab
                            </label>

                            <div class="relative">
                                <input type="text" autocomplete="off" placeholder="Ketik untuk mencari PIC..." class="search-input w-full px-4 py-3 pr-12
                                           bg-gray-50 dark:bg-[#222]
                                           border-2 border-black dark:border-white
                                           font-bold text-black dark:text-white
                                           outline-none
                                           focus:bg-white dark:focus:bg-[#2a2a2a]
                                           focus:shadow-[4px_4px_0px_0px_#00d982]
                                           transition-shadow">

                                <button type="button" class="search-clear hidden absolute right-3 top-1/2 -translate-y-1/2
                                           w-7 h-7 items-center justify-center
                                           bg-black dark:bg-[#00d982]
                                           text-white dark:text-black
                                           border-2 border-black dark:border-[#00d982]
                                           hover:bg-[#00d982] hover:text-black
                                           transition-colors cursor-pointer" aria-label="Hapus PIC">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 6l12 12M6 18L18 6" />
                                    </svg>
                                </button>

                                <input type="hidden" name="person_in_charge_option_id" class="selected-value" required>

                                <div class="search-results hidden absolute z-50
                                            left-0 right-0 mt-1
                                            bg-white dark:bg-[#222]
                                            border-2 border-black dark:border-white
                                            max-h-60 overflow-y-auto
                                            shadow-[5px_5px_0px_0px_#000] dark:shadow-[5px_5px_0px_0px_#00d982]">

                                    <?php foreach ($options as $option): ?>
                                        <?php if (
                                            $option->category !== 'person_in_charge' &&
                                            $option->category !== 'Penanggung Jawab'
                                        )
                                            continue; ?>

                                        <button type="button" class="search-option w-full text-left px-4 py-3
                                                   border-b-2 border-black dark:border-white
                                                   font-bold text-black dark:text-white
                                                   hover:bg-[#00d982] hover:text-black
                                                   transition-colors" data-value="<?= htmlspecialchars($option->id) ?>"
                                            data-label="<?= htmlspecialchars($option->name) ?>">
                                            <?= htmlspecialchars($option->name) ?>
                                        </button>
                                    <?php endforeach; ?>

                                </div>
                            </div>
                        </div>


                        <!-- EXPECTED RESULT -->
                        <div class="searchable-select" data-category="expected_result">
                            <label class="block mb-2 text-sm font-black uppercase text-black dark:text-white">
                                Hasil yang Diharapkan
                            </label>

                            <div class="relative">
                                <input type="text" autocomplete="off" placeholder="Ketik untuk mencari hasil..." class="search-input w-full px-4 py-3 pr-12
                                           bg-gray-50 dark:bg-[#222]
                                           border-2 border-black dark:border-white
                                           font-bold text-black dark:text-white
                                           outline-none
                                           focus:bg-white dark:focus:bg-[#2a2a2a]
                                           focus:shadow-[4px_4px_0px_0px_#00d982]
                                           transition-shadow">

                                <button type="button" class="search-clear hidden absolute right-3 top-1/2 -translate-y-1/2
                                           w-7 h-7 items-center justify-center
                                           bg-black dark:bg-[#00d982]
                                           text-white dark:text-black
                                           border-2 border-black dark:border-[#00d982]
                                           hover:bg-[#00d982] hover:text-black
                                           transition-colors cursor-pointer" aria-label="Hapus hasil">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 6l12 12M6 18L18 6" />
                                    </svg>
                                </button>

                                <input type="hidden" name="expected_result_option_id" class="selected-value" required>

                                <div class="search-results hidden absolute z-50
                                            left-0 right-0 mt-1
                                            bg-white dark:bg-[#222]
                                            border-2 border-black dark:border-white
                                            max-h-60 overflow-y-auto
                                            shadow-[5px_5px_0px_0px_#000] dark:shadow-[5px_5px_0px_0px_#00d982]">

                                    <?php foreach ($options as $option): ?>
                                        <?php if (
                                            $option->category !== 'expected_result' &&
                                            $option->category !== 'Hasil' &&
                                            $option->category !== 'Hasil Diharapkan'
                                        )
                                            continue; ?>

                                        <button type="button" class="search-option w-full text-left px-4 py-3
                                                   border-b-2 border-black dark:border-white
                                                   font-bold text-black dark:text-white
                                                   hover:bg-[#00d982] hover:text-black
                                                   transition-colors" data-value="<?= htmlspecialchars($option->id) ?>"
                                            data-label="<?= htmlspecialchars($option->name) ?>">
                                            <?= htmlspecialchars($option->name) ?>
                                        </button>
                                    <?php endforeach; ?>

                                </div>
                            </div>
                        </div>

                    </div>


                    <!-- Divider -->
                    <div class="my-8 border-t-4 border-black dark:border-white"></div>


                    <!-- Remarks -->
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10
                                        bg-black dark:bg-[#00d982]
                                        text-white dark:text-black
                                        flex items-center justify-center
                                        font-black">
                                02
                            </div>

                            <div>
                                <h2 class="text-xl font-black uppercase text-black dark:text-white">
                                    Uraian Aktivitas
                                </h2>
                                <p class="text-sm text-gray-500 dark:text-gray-400 font-bold">
                                    Tambahkan keterangan kegiatan
                                </p>
                            </div>
                        </div>

                        <textarea id="remarks" name="remarks" rows="4"
                            placeholder="Tuliskan keterangan atau uraian aktivitas..." class="w-full px-4 py-3
                                   bg-gray-50 dark:bg-[#222]
                                   border-2 border-black dark:border-white
                                   font-bold text-black dark:text-white
                                   outline-none resize-y
                                   focus:bg-white dark:focus:bg-[#2a2a2a]
                                   focus:shadow-[4px_4px_0px_0px_#00d982]
                                   transition-shadow"></textarea>
                    </div>


                    <!-- Actions -->
                    <div class="mt-8 pt-6 border-t-4 border-black dark:border-white
                                flex flex-col sm:flex-row justify-end gap-4">

                        <a href="/report/<?= htmlspecialchars($date) ?>" class="px-6 py-3
                                   bg-white dark:bg-[#222]
                                   text-black dark:text-white
                                   border-2 border-black dark:border-white
                                   font-black uppercase text-center
                                   shadow-[5px_5px_0px_0px_#000] dark:shadow-[5px_5px_0px_0px_#00d982]
                                   hover:shadow-none
                                   hover:translate-x-1 hover:translate-y-1
                                   transition-all">
                            Batal
                        </a>

                        <button type="submit" class="px-6 py-3
                                   bg-[#00d982] text-black
                                   border-2 border-black
                                   font-black uppercase
                                   shadow-[5px_5px_0px_0px_#000]
                                   hover:shadow-none
                                   hover:translate-x-1 hover:translate-y-1
                                   transition-all cursor-pointer">
                            Simpan Rincian Giat
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>


<!-- ============================== -->
<!-- MODAL TAMBAH OPSI CEPAT -->
<!-- ============================== -->
<div id="quick-add-modal" class="hidden fixed inset-0 z-[200] flex items-center justify-center p-4
           bg-black/70 backdrop-blur-sm">

    <div id="quick-add-modal-content" class="w-full max-w-md
               bg-white dark:bg-[#181818]
               border-4 border-[#121212] dark:border-white
               shadow-[8px_8px_0_0_#121212] dark:shadow-[8px_8px_0_0_#00d982]
               transform scale-95 transition-transform duration-200">

        <!-- Header -->
        <div class="flex items-center gap-3 p-5
                    border-b-4 border-[#121212] dark:border-white">
            <div class="w-10 h-10 flex items-center justify-center
                        bg-[#00d982] border-2 border-[#121212]">
                <i data-lucide="plus" class="w-5 h-5 text-black"></i>
            </div>
            <h3 class="font-black uppercase text-lg
                       text-[#121212] dark:text-white">
                Tambah Opsi Baru
            </h3>
        </div>

        <!-- Body -->
        <div class="p-5 space-y-4">
            <p class="text-sm font-bold text-[#121212] dark:text-white">
                Opsi baru akan ditambahkan ke kategori:
                <span id="quick-add-category-label" class="px-2 py-0.5
                           bg-[#00d982] text-black
                           border-2 border-black
                           font-black text-xs uppercase">
                    -
                </span>
            </p>

            <div>
                <label for="quick-add-name" class="block mb-2 text-xs font-black uppercase
                           text-[#121212] dark:text-white">
                    Nama Opsi <span class="text-red-500">*</span>
                </label>

                <input type="text" id="quick-add-name" placeholder="Ketik nama opsi..." required class="w-full px-4 py-3
                           bg-white dark:bg-[#222]
                           text-[#121212] dark:text-white
                           border-4 border-[#121212] dark:border-white
                           font-bold outline-none
                           focus:ring-4 focus:ring-[#00d982]">

                <p id="quick-add-error" class="mt-2 text-xs font-bold text-red-500 hidden"></p>
            </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-3 p-5
                    border-t-4 border-[#121212] dark:border-white">

            <button type="button" id="quick-add-cancel" class="flex-1 px-4 py-3
                       bg-gray-200 dark:bg-[#222]
                       text-[#121212] dark:text-white
                       border-4 border-[#121212] dark:border-white
                       font-black uppercase text-sm
                       shadow-[4px_4px_0_0_#121212] dark:shadow-[4px_4px_0_0_#00d982]
                       hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px]
                       transition-all cursor-pointer">
                Batal
            </button>

            <button type="button" id="quick-add-save" class="flex-1 px-4 py-3
                       bg-[#00d982] text-black
                       border-4 border-[#121212]
                       font-black uppercase text-sm
                       shadow-[4px_4px_0_0_#121212]
                       hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px]
                       transition-all cursor-pointer">
                Simpan
            </button>

        </div>

    </div>
</div>


<!-- ============================== -->
<!-- VALIDATION MODAL -->
<!-- ============================== -->
<div id="validationModal" class="fixed inset-0 z-[9999] hidden items-center justify-center
           bg-black/70 px-4">

    <div class="w-full max-w-md
               bg-white dark:bg-[#181818]
               border-4 border-black dark:border-white
               shadow-[8px_8px_0px_0px_#00d982]">

        <div class="bg-black text-white
                   dark:bg-[#00d982] dark:text-black
                   px-5 py-4 flex items-center justify-between">
            <h3 class="font-black uppercase">Data Belum Lengkap</h3>
            <button type="button" onclick="closeValidationModal()"
                class="text-2xl font-black hover:text-[#00d982] dark:hover:text-white cursor-pointer">
                ×
            </button>
        </div>

        <div class="p-6">
            <p class="font-bold text-gray-700 dark:text-gray-300">
                Data belum lengkap. Silakan lengkapi seluruh pilihan
                sebelum menyimpan rincian giat.
            </p>
        </div>

        <div class="px-6 pb-6 flex justify-end">
            <button type="button" onclick="closeValidationModal()" class="px-6 py-3 bg-[#00d982] text-black
                       border-2 border-black font-black uppercase
                       shadow-[4px_4px_0px_0px_#000]
                       hover:shadow-none hover:translate-x-1 hover:translate-y-1
                       transition-all cursor-pointer">
                Mengerti
            </button>
        </div>

    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        let pendingContainer = null;

        /* ==========================================================
         * 1. SEARCHABLE DROPDOWN + CLEAR + QUICK-ADD
         * ========================================================== */
        const searchableSelects = document.querySelectorAll('.searchable-select');

        searchableSelects.forEach(function (container) {

            const input = container.querySelector('.search-input');
            const hiddenInput = container.querySelector('.selected-value');
            const results = container.querySelector('.search-results');
            const clearBtn = container.querySelector('.search-clear');

            // --------------------------------------------------
            // Tampil / sembunyikan tombol clear
            // --------------------------------------------------
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
            updateClearBtn();

            // --------------------------------------------------
            // Focus → buka dropdown
            // --------------------------------------------------
            input.addEventListener('focus', function () {
                results.classList.remove('hidden');
                filterOptions();
            });

            // --------------------------------------------------
            // Input → reset pilihan & filter
            // --------------------------------------------------
            input.addEventListener('input', function () {
                hiddenInput.value = '';
                input.classList.remove('border-red-600', 'bg-red-50');
                results.classList.remove('hidden');
                filterOptions();
                updateClearBtn();
            });

            // --------------------------------------------------
            // EVENT DELEGATION — klik option (lama & baru)
            // --------------------------------------------------
            results.addEventListener('click', function (e) {
                const option = e.target.closest('.search-option');
                if (!option) return;

                input.value = option.dataset.label;
                hiddenInput.value = option.dataset.value;
                results.classList.add('hidden');
                input.classList.remove('border-red-600', 'bg-red-50');
                updateClearBtn();
            });

            // --------------------------------------------------
            // Tombol clear (×)
            // --------------------------------------------------
            if (clearBtn) {
                clearBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    input.value = '';
                    hiddenInput.value = '';
                    input.classList.remove('border-red-600', 'bg-red-50');
                    results.classList.add('hidden');
                    updateClearBtn();
                    input.focus();
                });
            }

            // --------------------------------------------------
            // Simpan referensi filter ke container (opsional)
            // --------------------------------------------------
            container._filterOptions = filterOptions;

            // --------------------------------------------------
            // Filter options — RE-QUERY setiap kali
            // --------------------------------------------------
            function filterOptions() {
                // ✅ Re-query — include option baru yang di-insert via JS
                const options = results.querySelectorAll('.search-option');

                const keyword = input.value.toLowerCase().trim();
                let found = false;

                options.forEach(function (option) {
                    const label = (option.dataset.label || '').toLowerCase();

                    if (label.includes(keyword)) {
                        option.classList.remove('hidden');
                        found = true;
                    } else {
                        option.classList.add('hidden');
                    }
                });

                // --------------------------------------------------
                // Pesan kosong + tombol "+ Tambah"
                // --------------------------------------------------
                let emptyMessage = results.querySelector('.no-result');

                if (!found) {
                    const searchTerm = input.value.trim();

                    if (!emptyMessage) {
                        emptyMessage = document.createElement('div');
                        emptyMessage.className = 'no-result';
                        emptyMessage.innerHTML = `
                        <p class="px-4 py-3 text-sm font-black text-gray-500 dark:text-gray-400
                                  border-b-2 border-black dark:border-white">
                            Tidak ada hasil ditemukan.
                        </p>
                        <button type="button"
                            class="quick-add-btn w-full text-left px-4 py-3
                                   bg-[#00d982] text-black
                                   font-black uppercase text-xs
                                   hover:bg-black hover:text-[#00d982]
                                   transition-colors cursor-pointer">
                            + Tambah "${searchTerm}"
                        </button>
                    `;

                        // Bind event ke tombol quick-add
                        emptyMessage.querySelector('.quick-add-btn')
                            .addEventListener('click', function () {
                                openQuickAddModal(container);
                            });

                        results.appendChild(emptyMessage);
                    } else {
                        // Update teks tombol kalau keyword berubah
                        const btn = emptyMessage.querySelector('.quick-add-btn');
                        if (btn) btn.textContent = `+ Tambah "${searchTerm}"`;
                    }
                } else if (emptyMessage) {
                    emptyMessage.remove();
                }
            }

        });

        // --------------------------------------------------
        // Tutup dropdown saat klik di luar
        // --------------------------------------------------
        document.addEventListener('click', function (event) {
            searchableSelects.forEach(function (container) {
                if (!container.contains(event.target)) {
                    const results = container.querySelector('.search-results');
                    results.classList.add('hidden');
                }
            });
        });


        /* ==========================================================
         * 2. MODAL QUICK-ADD
         * ========================================================== */
        const quickAddModal = document.getElementById('quick-add-modal');
        const quickAddModalContent = document.getElementById('quick-add-modal-content');
        const quickAddName = document.getElementById('quick-add-name');
        const quickAddCategoryLbl = document.getElementById('quick-add-category-label');
        const quickAddError = document.getElementById('quick-add-error');
        const btnQuickCancel = document.getElementById('quick-add-cancel');
        const btnQuickSave = document.getElementById('quick-add-save');

        const categoryLabels = {
            'target': 'Sasaran',
            'activity': 'Giat / Aktivitas',
            'personnel_strength': 'Kuat Personel',
            'location': 'Lokasi',
            'person_in_charge': 'Penanggung Jawab',
            'expected_result': 'Hasil yang Diharapkan',
        };

        // --------------------------------------------------
        // Buka modal quick-add
        // --------------------------------------------------
        function openQuickAddModal(container) {
            pendingContainer = container;

            const category = container.dataset.category || '';
            quickAddCategoryLbl.textContent = categoryLabels[category] || category;

            const input = container.querySelector('.search-input');
            quickAddName.value = input.value.trim();

            quickAddError.classList.add('hidden');
            quickAddError.textContent = '';

            quickAddModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            requestAnimationFrame(() => {
                quickAddModalContent.classList.remove('scale-95');
                quickAddModalContent.classList.add('scale-100');
            });

            setTimeout(() => quickAddName.focus(), 100);
        }

        // --------------------------------------------------
        // Tutup modal
        // --------------------------------------------------
        function closeQuickAddModal() {
            quickAddModalContent.classList.remove('scale-100');
            quickAddModalContent.classList.add('scale-95');
            document.body.style.overflow = '';

            setTimeout(() => {
                quickAddModal.classList.add('hidden');
                pendingContainer = null;
            }, 150);
        }

        // --------------------------------------------------
        // Event — batal, backdrop, ESC
        // --------------------------------------------------
        btnQuickCancel.addEventListener('click', closeQuickAddModal);

        quickAddModal.addEventListener('click', function (e) {
            if (e.target === quickAddModal) closeQuickAddModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !quickAddModal.classList.contains('hidden')) {
                closeQuickAddModal();
            }
        });

        // --------------------------------------------------
        // Event — Simpan via AJAX
        // --------------------------------------------------
        btnQuickSave.addEventListener('click', function () {

            const name = quickAddName.value.trim();
            const category = pendingContainer?.dataset.category;

            if (!name) {
                quickAddError.textContent = 'Nama opsi wajib diisi.';
                quickAddError.classList.remove('hidden');
                quickAddName.focus();
                return;
            }

            btnQuickSave.disabled = true;
            btnQuickSave.textContent = 'Menyimpan...';

            fetch('/report/option/quick-add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: new URLSearchParams({ name, category }).toString(),
            })
                .then(async (r) => {
                    const text = await r.text();

                    // Debug — log response asli
                    console.log('RAW RESPONSE:', text);

                    try {
                        return JSON.parse(text);
                    } catch (err) {
                        throw new Error('Server response tidak valid. Cek Console.');
                    }
                })
                .then(data => {
                    if (!data.success) {
                        throw new Error(data.message || 'Gagal menyimpan.');
                    }

                    const input = pendingContainer.querySelector('.search-input');
                    const hiddenInput = pendingContainer.querySelector('.selected-value');
                    const results = pendingContainer.querySelector('.search-results');

                    // 1. Hapus pesan ".no-result" jika ada
                    const noResult = results.querySelector('.no-result');
                    if (noResult) noResult.remove();

                    // 2. Insert option baru ke list (paling atas)
                    const newBtn = document.createElement('button');
                    newBtn.type = 'button';
                    newBtn.className = 'search-option w-full text-left px-4 py-3 border-b-2 border-black dark:border-white font-bold text-black dark:text-white hover:bg-[#00d982] hover:text-black transition-colors';
                    newBtn.dataset.value = data.data.id;
                    newBtn.dataset.label = data.data.name;
                    newBtn.textContent = data.data.name;

                    results.insertBefore(newBtn, results.firstChild);

                    // 3. Isi input + hidden value
                    input.value = data.data.name;
                    hiddenInput.value = data.data.id;
                    input.classList.remove('border-red-600', 'bg-red-50');

                    // 4. Tutup dropdown
                    results.classList.add('hidden');

                    // 5. Update tombol clear
                    const clearBtn = pendingContainer.querySelector('.search-clear');
                    if (clearBtn) {
                        clearBtn.classList.remove('hidden');
                        clearBtn.classList.add('flex');
                    }

                    // 6. Tutup modal
                    closeQuickAddModal();
                })
                .catch(err => {
                    console.error('Quick-add error:', err);
                    quickAddError.textContent = err.message;
                    quickAddError.classList.remove('hidden');
                })
                .finally(() => {
                    btnQuickSave.disabled = false;
                    btnQuickSave.textContent = 'Simpan';
                });
        });


        /* ==========================================================
         * 3. VALIDASI SUBMIT FORM
         * ========================================================== */
        document.querySelector('form').addEventListener('submit', function (event) {

            const selectedInputs = this.querySelectorAll('.selected-value');
            let valid = true;
            let firstInvalid = null;

            selectedInputs.forEach(function (input) {
                if (!input.value) {
                    valid = false;

                    const container = input.closest('.searchable-select');
                    const searchInput = container.querySelector('.search-input');

                    searchInput.classList.add('border-red-600');

                    if (!firstInvalid) firstInvalid = searchInput;
                }
            });

            if (!valid) {
                event.preventDefault();
                if (firstInvalid) firstInvalid.focus();
                openValidationModal();
            }
        });


        /* ==========================================================
         * 4. VALIDATION MODAL
         * ========================================================== */
        window.openValidationModal = function () {
            const modal = document.getElementById('validationModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        };

        window.closeValidationModal = function () {
            const modal = document.getElementById('validationModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        };

    });
</script>