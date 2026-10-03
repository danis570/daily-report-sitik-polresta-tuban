<div class="min-h-screen bg-gray-50 dark:bg-[#121212] py-12 px-4 sm:px-6 lg:px-8">

    <div class="max-w-6xl mx-auto">

        <!-- Breadcrumb -->
        <nav aria-label="Breadcrumb" class="mb-6 pl-2">
            <ol class="flex flex-wrap items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300">

                <!-- Laporan -->
                <li>
                    <a href="/reports" class="hover:text-[#00d982] transition-colors">
                        Laporan
                    </a>
                </li>

                <!-- Separator -->
                <li aria-hidden="true" class="text-gray-400 dark:text-gray-600 flex items-center">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </li>

                <!-- Tanggal Laporan (detail) -->
                <li>
                    <a href="/report/<?= htmlspecialchars($date) ?>" class="hover:text-[#00d982] transition-colors">
                        <?= htmlspecialchars($formattedDate) ?>
                    </a>
                </li>

                <!-- Separator -->
                <li aria-hidden="true" class="text-gray-400 dark:text-gray-600 flex items-center">
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </li>

                <!-- Current Page -->
                <li>
                    <span aria-current="page" class="text-black dark:text-white">
                        Giat no <?= htmlspecialchars($reportItem->itemNo) ?> · Edit
                    </span>
                </li>

            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-8">

            <h1 class="text-4xl sm:text-5xl
                       font-black uppercase tracking-tight
                       text-black dark:text-white">
                Ubah Item Giat
            </h1>

            <p class="mt-3 text-gray-600 dark:text-gray-400 font-medium">
                Mengedit rincian
                <span class="font-black text-black dark:text-white">
                    Giat #<?= htmlspecialchars($reportItem->itemNo) ?>
                </span>
                untuk laporan tanggal
                <span class="font-black text-black dark:text-white">
                    <?= htmlspecialchars($formattedDate) ?>
                </span>
            </p>

        </div>


        <!-- Error -->
        <?php if (!empty($error)) { ?>

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

        <?php } ?>


        <!-- Form Card -->
        <div class="bg-white dark:bg-[#181818]
                   border-2 border-black dark:border-white
                   shadow-[8px_8px_0px_0px_#000] dark:shadow-[8px_8px_0px_0px_#00d982]">

            <div class="p-6 sm:p-8">

                <form action="/report/item/edit/<?= htmlspecialchars($reportItem->id) ?>" method="POST">

                    <!-- Section 01 -->
                    <div class="flex items-center gap-4 mb-8">

                        <div class="w-12 h-12 shrink-0
                                   bg-[#00d982]
                                   border-4 border-black
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
                                Ubah data aktivitas laporan
                            </p>

                        </div>

                    </div>


                    <!-- Grid Searchable -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- TARGET -->
                        <div class="searchable-select">
                            <label class="block mb-2 text-sm font-black uppercase text-black dark:text-white">
                                Sasaran / Target
                            </label>

                            <div class="relative">
                                <input type="text" autocomplete="off" value=""
                                    placeholder="Ketik untuk mencari sasaran..." class="search-input w-full px-4 py-3 pr-12
                       bg-gray-50 dark:bg-[#222]
                       border-2 border-black dark:border-white
                       font-bold text-black dark:text-white
                       outline-none
                       focus:bg-white dark:focus:bg-[#2a2a2a]
                       focus:shadow-[4px_4px_0px_0px_#00d982]">

                                <button type="button" class="search-clear hidden absolute right-3 top-1/2 -translate-y-1/2
                       w-7 h-7 items-center justify-center
                       bg-black dark:bg-[#00d982] text-white dark:text-black
                       border-2 border-black dark:border-[#00d982]
                       hover:bg-[#00d982] hover:text-black
                       dark:hover:bg-white dark:hover:text-black
                       transition-colors cursor-pointer" aria-label="Hapus sasaran">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 6l12 12M6 18L18 6" />
                                    </svg>
                                </button>

                                <input type="hidden" name="target_option_id" class="selected-value"
                                    value="<?= htmlspecialchars($reportItem->targetOptionId) ?>" required>

                                <div class="search-results hidden absolute z-50
                       left-0 right-0 mt-1
                       bg-white dark:bg-[#222]
                       border-2 border-black dark:border-white
                       max-h-60 overflow-y-auto
                       shadow-[5px_5px_0px_0px_#000] dark:shadow-[5px_5px_0px_0px_#00d982]">
                                    <?php foreach ($options as $opt) { ?>
                                        <?php if (
                                            strcasecmp($opt->category, 'Target') !== 0 &&
                                            strcasecmp($opt->category, 'Sasaran') !== 0
                                        ) {
                                            continue;
                                        } ?>

                                        <button type="button" class="search-option w-full text-left
                               px-4 py-3
                               border-b-2 border-black dark:border-white
                               font-bold
                               text-black dark:text-white
                               hover:bg-[#00d982] hover:text-black
                               transition-colors" data-value="<?= htmlspecialchars($opt->id) ?>"
                                            data-label="<?= htmlspecialchars($opt->name) ?>">
                                            <?= htmlspecialchars($opt->name) ?>
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>


                        <!-- ACTIVITY -->
                        <div class="searchable-select">
                            <label class="block mb-2 text-sm font-black uppercase text-black dark:text-white">
                                Jenis Kegiatan (Giat)
                            </label>

                            <div class="relative">
                                <input type="text" autocomplete="off" placeholder="Ketik untuk mencari giat..." class="search-input w-full px-4 py-3 pr-12
                       bg-gray-50 dark:bg-[#222]
                       border-2 border-black dark:border-white
                       font-bold text-black dark:text-white
                       outline-none
                       focus:bg-white dark:focus:bg-[#2a2a2a]
                       focus:shadow-[4px_4px_0px_0px_#00d982]">

                                <button type="button" class="search-clear hidden absolute right-3 top-1/2 -translate-y-1/2
                       w-7 h-7 items-center justify-center
                       bg-black dark:bg-[#00d982] text-white dark:text-black
                       border-2 border-black dark:border-[#00d982]
                       hover:bg-[#00d982] hover:text-black
                       dark:hover:bg-white dark:hover:text-black
                       transition-colors cursor-pointer" aria-label="Hapus giat">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 6l12 12M6 18L18 6" />
                                    </svg>
                                </button>

                                <input type="hidden" name="activity_option_id" class="selected-value"
                                    value="<?= htmlspecialchars($reportItem->activityOptionId) ?>" required>

                                <div class="search-results hidden absolute z-50
                       left-0 right-0 mt-1
                       bg-white dark:bg-[#222]
                       border-2 border-black dark:border-white
                       max-h-60 overflow-y-auto
                       shadow-[5px_5px_0px_0px_#000] dark:shadow-[5px_5px_0px_0px_#00d982]">
                                    <?php foreach ($options as $opt) { ?>
                                        <?php if (
                                            strcasecmp($opt->category, 'Activity') !== 0 &&
                                            strcasecmp($opt->category, 'Kegiatan') !== 0 &&
                                            strcasecmp($opt->category, 'Giat') !== 0
                                        ) {
                                            continue;
                                        } ?>

                                        <button type="button" class="search-option w-full text-left
                               px-4 py-3
                               border-b-2 border-black dark:border-white
                               font-bold
                               text-black dark:text-white
                               hover:bg-[#00d982] hover:text-black
                               transition-colors" data-value="<?= htmlspecialchars($opt->id) ?>"
                                            data-label="<?= htmlspecialchars($opt->name) ?>">
                                            <?= htmlspecialchars($opt->name) ?>
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>


                        <!-- PERSONNEL -->
                        <div class="searchable-select">
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
                       focus:shadow-[4px_4px_0px_0px_#00d982]">

                                <button type="button" class="search-clear hidden absolute right-3 top-1/2 -translate-y-1/2
                       w-7 h-7 items-center justify-center
                       bg-black dark:bg-[#00d982] text-white dark:text-black
                       border-2 border-black dark:border-[#00d982]
                       hover:bg-[#00d982] hover:text-black
                       dark:hover:bg-white dark:hover:text-black
                       transition-colors cursor-pointer" aria-label="Hapus personel">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 6l12 12M6 18L18 6" />
                                    </svg>
                                </button>

                                <input type="hidden" name="personnel_strength_option_id" class="selected-value"
                                    value="<?= htmlspecialchars($reportItem->personnelStrengthOptionId) ?>" required>

                                <div class="search-results hidden absolute z-50
                       left-0 right-0 mt-1
                       bg-white dark:bg-[#222]
                       border-2 border-black dark:border-white
                       max-h-60 overflow-y-auto
                       shadow-[5px_5px_0px_0px_#000] dark:shadow-[5px_5px_0px_0px_#00d982]">
                                    <?php foreach ($options as $opt) { ?>
                                        <?php if (
                                            strcasecmp($opt->category, 'personnel_strength') !== 0 &&
                                            strcasecmp($opt->category, 'Personnel') !== 0 &&
                                            strcasecmp($opt->category, 'Personel') !== 0 &&
                                            strcasecmp($opt->category, 'Kuat Personel') !== 0
                                        ) {
                                            continue;
                                        } ?>

                                        <button type="button" class="search-option w-full text-left
                               px-4 py-3
                               border-b-2 border-black dark:border-white
                               font-bold
                               text-black dark:text-white
                               hover:bg-[#00d982] hover:text-black
                               transition-colors" data-value="<?= htmlspecialchars($opt->id) ?>"
                                            data-label="<?= htmlspecialchars($opt->name) ?>">
                                            <?= htmlspecialchars($opt->name) ?>
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>


                        <!-- LOCATION -->
                        <div class="searchable-select">
                            <label class="block mb-2 text-sm font-black uppercase text-black dark:text-white">
                                Lokasi Pelaksanaan
                            </label>

                            <div class="relative">
                                <input type="text" autocomplete="off" placeholder="Ketik untuk mencari lokasi..." class="search-input w-full px-4 py-3 pr-12
                       bg-gray-50 dark:bg-[#222]
                       border-2 border-black dark:border-white
                       font-bold text-black dark:text-white
                       outline-none
                       focus:bg-white dark:focus:bg-[#2a2a2a]
                       focus:shadow-[4px_4px_0px_0px_#00d982]">

                                <button type="button" class="search-clear hidden absolute right-3 top-1/2 -translate-y-1/2
                       w-7 h-7 items-center justify-center
                       bg-black dark:bg-[#00d982] text-white dark:text-black
                       border-2 border-black dark:border-[#00d982]
                       hover:bg-[#00d982] hover:text-black
                       dark:hover:bg-white dark:hover:text-black
                       transition-colors cursor-pointer" aria-label="Hapus lokasi">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 6l12 12M6 18L18 6" />
                                    </svg>
                                </button>

                                <input type="hidden" name="location_option_id" class="selected-value"
                                    value="<?= htmlspecialchars($reportItem->locationOptionId) ?>" required>

                                <div class="search-results hidden absolute z-50
                       left-0 right-0 mt-1
                       bg-white dark:bg-[#222]
                       border-2 border-black dark:border-white
                       max-h-60 overflow-y-auto
                       shadow-[5px_5px_0px_0px_#000] dark:shadow-[5px_5px_0px_0px_#00d982]">
                                    <?php foreach ($options as $opt) { ?>
                                        <?php if (
                                            strcasecmp($opt->category, 'Location') !== 0 &&
                                            strcasecmp($opt->category, 'Lokasi') !== 0
                                        ) {
                                            continue;
                                        } ?>

                                        <button type="button" class="search-option w-full text-left
                               px-4 py-3
                               border-b-2 border-black dark:border-white
                               font-bold
                               text-black dark:text-white
                               hover:bg-[#00d982] hover:text-black
                               transition-colors" data-value="<?= htmlspecialchars($opt->id) ?>"
                                            data-label="<?= htmlspecialchars($opt->name) ?>">
                                            <?= htmlspecialchars($opt->name) ?>
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>


                        <!-- PIC -->
                        <div class="searchable-select">
                            <label class="block mb-2 text-sm font-black uppercase text-black dark:text-white">
                                Perwira Penanggung Jawab
                            </label>

                            <div class="relative">
                                <input type="text" autocomplete="off" placeholder="Ketik untuk mencari PIC..." class="search-input w-full px-4 py-3 pr-12
                       bg-gray-50 dark:bg-[#222]
                       border-2 border-black dark:border-white
                       font-bold text-black dark:text-white
                       outline-none
                       focus:bg-white dark:focus:bg-[#2a2a2a]
                       focus:shadow-[4px_4px_0px_0px_#00d982]">

                                <button type="button" class="search-clear hidden absolute right-3 top-1/2 -translate-y-1/2
                       w-7 h-7 items-center justify-center
                       bg-black dark:bg-[#00d982] text-white dark:text-black
                       border-2 border-black dark:border-[#00d982]
                       hover:bg-[#00d982] hover:text-black
                       dark:hover:bg-white dark:hover:text-black
                       transition-colors cursor-pointer" aria-label="Hapus PIC">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 6l12 12M6 18L18 6" />
                                    </svg>
                                </button>

                                <input type="hidden" name="person_in_charge_option_id" class="selected-value"
                                    value="<?= htmlspecialchars($reportItem->personInChargeOptionId) ?>" required>

                                <div class="search-results hidden absolute z-50
                       left-0 right-0 mt-1
                       bg-white dark:bg-[#222]
                       border-2 border-black dark:border-white
                       max-h-60 overflow-y-auto
                       shadow-[5px_5px_0px_0px_#000] dark:shadow-[5px_5px_0px_0px_#00d982]">
                                    <?php foreach ($options as $opt) { ?>
                                        <?php if (
                                            strcasecmp($opt->category, 'PIC') !== 0 &&
                                            strcasecmp($opt->category, 'Penanggung Jawab') !== 0 &&
                                            strcasecmp($opt->category, 'person_in_charge') !== 0
                                        ) {
                                            continue;
                                        } ?>

                                        <button type="button" class="search-option w-full text-left
                               px-4 py-3
                               border-b-2 border-black dark:border-white
                               font-bold
                               text-black dark:text-white
                               hover:bg-[#00d982] hover:text-black
                               transition-colors" data-value="<?= htmlspecialchars($opt->id) ?>"
                                            data-label="<?= htmlspecialchars($opt->name) ?>">
                                            <?= htmlspecialchars($opt->name) ?>
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>


                        <!-- EXPECTED RESULT -->
                        <div class="searchable-select">
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
                       focus:shadow-[4px_4px_0px_0px_#00d982]">

                                <button type="button" class="search-clear hidden absolute right-3 top-1/2 -translate-y-1/2
                       w-7 h-7 items-center justify-center
                       bg-black dark:bg-[#00d982] text-white dark:text-black
                       border-2 border-black dark:border-[#00d982]
                       hover:bg-[#00d982] hover:text-black
                       dark:hover:bg-white dark:hover:text-black
                       transition-colors cursor-pointer" aria-label="Hapus hasil">
                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M6 6l12 12M6 18L18 6" />
                                    </svg>
                                </button>

                                <input type="hidden" name="expected_result_option_id" class="selected-value"
                                    value="<?= htmlspecialchars($reportItem->expectedResultOptionId) ?>" required>

                                <div class="search-results hidden absolute z-50
                       left-0 right-0 mt-1
                       bg-white dark:bg-[#222]
                       border-2 border-black dark:border-white
                       max-h-60 overflow-y-auto
                       shadow-[5px_5px_0px_0px_#000] dark:shadow-[5px_5px_0px_0px_#00d982]">
                                    <?php foreach ($options as $opt) { ?>
                                        <?php if (
                                            strcasecmp($opt->category, 'Result') !== 0 &&
                                            strcasecmp($opt->category, 'Hasil') !== 0 &&
                                            strcasecmp($opt->category, 'Hasil Diharapkan') !== 0 &&
                                            strcasecmp($opt->category, 'expected_result') !== 0
                                        ) {
                                            continue;
                                        } ?>

                                        <button type="button" class="search-option w-full text-left
                               px-4 py-3
                               border-b-2 border-black dark:border-white
                               font-bold
                               text-black dark:text-white
                               hover:bg-[#00d982] hover:text-black
                               transition-colors" data-value="<?= htmlspecialchars($opt->id) ?>"
                                            data-label="<?= htmlspecialchars($opt->name) ?>">
                                            <?= htmlspecialchars($opt->name) ?>
                                        </button>
                                    <?php } ?>
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
                                       bg-black dark:bg-[#00d982] text-white dark:text-black
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
                            placeholder="Tuliskan keterangan atau uraian aktivitas..."
                            class="w-full px-4 py-3
                                   bg-gray-50 dark:bg-[#222]
                                   border-2 border-black dark:border-white
                                   font-bold text-black dark:text-white
                                   outline-none resize-y
                                   focus:bg-white dark:focus:bg-[#2a2a2a]
                                   focus:shadow-[4px_4px_0px_0px_#00d982]"><?= htmlspecialchars($_POST['remarks'] ?? $reportItem->remarks ?? '') ?></textarea>

                    </div>


                    <!-- Actions -->
                    <div class="mt-8 pt-6
                               border-t-4 border-black dark:border-white
                               flex flex-col sm:flex-row
                               justify-end gap-4">

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
                                   bg-[#00d982]
                                   text-black
                                   border-2 border-black
                                   font-black uppercase
                                   shadow-[5px_5px_0px_0px_#000]
                                   hover:shadow-none
                                   hover:translate-x-1 hover:translate-y-1
                                   transition-all">
                            Simpan Perubahan Giat
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>


<!-- Validation Modal -->
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
                       transition-colors">
                ×
            </button>

        </div>

        <div class="p-6">

            <p class="font-bold text-gray-700 dark:text-gray-300 leading-relaxed">
                Silakan lengkapi seluruh data yang diperlukan
                sebelum menyimpan perubahan rincian giat.
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
                       transition-all">
                Mengerti
            </button>

        </div>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        /* ==========================================================
         * 1. SEARCHABLE DROPDOWN + TOMBOL CLEAR
         * ========================================================== */

        const searchableSelects = document.querySelectorAll('.searchable-select');

        searchableSelects.forEach(function (container) {

            const input       = container.querySelector('.search-input');
            const hiddenInput = container.querySelector('.selected-value');
            const results     = container.querySelector('.search-results');
            const options     = container.querySelectorAll('.search-option');
            const clearBtn    = container.querySelector('.search-clear');

            // --------------------------------------------------
            // Tampilkan / sembunyikan tombol clear
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

            // --------------------------------------------------
            // Set nilai nama berdasarkan ID saat halaman pertama kali dibuka
            // --------------------------------------------------
            const selectedValue = hiddenInput.value;
            if (selectedValue) {
                options.forEach(function (option) {
                    if (option.dataset.value === selectedValue) {
                        input.value = option.dataset.label;
                    }
                });
            }
            updateClearBtn();

            // --------------------------------------------------
            // Buka dropdown saat focus
            // --------------------------------------------------
            input.addEventListener('focus', function () {
                results.classList.remove('hidden');
                filterOptions();
            });

            // --------------------------------------------------
            // User mengetik → reset pilihan & filter
            // --------------------------------------------------
            input.addEventListener('input', function () {
                hiddenInput.value = '';
                input.classList.remove('border-red-600', 'bg-red-50');
                results.classList.remove('hidden');
                filterOptions();
                updateClearBtn();
            });

            // --------------------------------------------------
            // Pilih opsi dari dropdown
            // --------------------------------------------------
            options.forEach(function (option) {
                option.addEventListener('click', function () {
                    input.value       = this.dataset.label;
                    hiddenInput.value = this.dataset.value;

                    input.classList.remove('border-red-600', 'bg-red-50');

                    results.classList.add('hidden');

                    updateClearBtn();
                });
            });

            // --------------------------------------------------
            // Klik tombol clear (×)
            // --------------------------------------------------
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
                });
            }

            // --------------------------------------------------
            // Filter realtime
            // --------------------------------------------------
            function filterOptions() {
                const keyword = input.value.toLowerCase().trim();
                let found = false;

                options.forEach(function (option) {
                    const label = option.dataset.label.toLowerCase();

                    if (label.includes(keyword)) {
                        option.classList.remove('hidden');
                        found = true;
                    } else {
                        option.classList.add('hidden');
                    }
                });

                // Pesan kosong saat tidak ada hasil
                let emptyMessage = results.querySelector('.no-result');

                if (!found) {
                    if (!emptyMessage) {
                        emptyMessage = document.createElement('div');
                        emptyMessage.className = 'no-result px-4 py-3 text-sm font-black text-gray-500 dark:text-gray-400';
                        emptyMessage.textContent = 'Tidak ada hasil ditemukan.';
                        results.appendChild(emptyMessage);
                    }
                } else {
                    if (emptyMessage) {
                        emptyMessage.remove();
                    }
                }
            }

        });

        // --------------------------------------------------
        // Tutup dropdown saat klik di luar
        // --------------------------------------------------
        document.addEventListener('click', function (event) {
            searchableSelects.forEach(function (container) {
                if (!container.contains(event.target)) {
                    container.querySelector('.search-results').classList.add('hidden');
                }
            });
        });


        /* ==========================================================
         * 2. VALIDATION MODAL
         * ========================================================== */

        const form                   = document.querySelector('form');
        const validationModal        = document.getElementById('validationModal');
        const closeValidationModal   = document.getElementById('closeValidationModal');
        const confirmValidationModal = document.getElementById('confirmValidationModal');

        function openValidationModal() {
            validationModal.classList.remove('hidden');
            validationModal.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function closeModal() {
            validationModal.classList.add('hidden');
            validationModal.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
        }

        closeValidationModal.addEventListener('click', closeModal);
        confirmValidationModal.addEventListener('click', closeModal);

        validationModal.addEventListener('click', function (event) {
            if (event.target === validationModal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !validationModal.classList.contains('hidden')) {
                closeModal();
            }
        });


        /* ==========================================================
         * 3. FORM VALIDATION
         * ========================================================== */

        form.addEventListener('submit', function (event) {
            const selectedInputs = this.querySelectorAll('.selected-value');
            let valid = true;
            let firstInvalidInput = null;

            selectedInputs.forEach(function (input) {
                const container   = input.closest('.searchable-select');
                const searchInput = container.querySelector('.search-input');

                if (!input.value) {
                    valid = false;
                    searchInput.classList.add('border-red-600', 'bg-red-50');

                    if (!firstInvalidInput) {
                        firstInvalidInput = searchInput;
                    }
                } else {
                    searchInput.classList.remove('border-red-600', 'bg-red-50');
                }
            });

            if (!valid) {
                event.preventDefault();
                openValidationModal();

                if (firstInvalidInput) {
                    firstInvalidInput.dataset.focusAfterClose = 'true';
                }
            }
        });


        // --------------------------------------------------
        // Setelah modal ditutup, fokus ke field pertama yang kosong
        // --------------------------------------------------
        function focusInvalidInput() {
            const input = document.querySelector('.search-input[data-focus-after-close="true"]');

            if (input) {
                delete input.dataset.focusAfterClose;
                input.focus();
            }
        }

        closeValidationModal.addEventListener('click', focusInvalidInput);
        confirmValidationModal.addEventListener('click', focusInvalidInput);

    });
</script>