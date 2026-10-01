<div class="min-h-screen bg-gray-50 py-12 px-4 mt-24 sm:px-6 lg:px-8">

    <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="mb-8">

            <a
                href="/report/<?= htmlspecialchars($date) ?>"
                class="inline-flex items-center gap-2 px-4 py-2 mb-6
                       bg-white border-2 border-black
                       font-bold text-black
                       shadow-[4px_4px_0px_0px_#000]
                       hover:shadow-none
                       hover:translate-x-1 hover:translate-y-1
                       transition-all"
            >
                ← Kembali ke Detail Laporan
            </a>

            <div class="flex items-center gap-3 mb-4">

                <span
                    class="bg-black text-white
                           px-3 py-1
                           text-xs font-black uppercase"
                >
                    Report Activity
                </span>

                <span class="text-sm font-bold text-gray-500">
                    <?= htmlspecialchars($formattedDate) ?>
                </span>

            </div>

            <h1 class="text-4xl sm:text-5xl
                       font-black uppercase tracking-tight text-black">
                Ubah Item Giat
            </h1>

            <p class="mt-3 text-gray-600 font-medium">
                Mengedit rincian
                <span class="font-black text-black">
                    Giat #<?= htmlspecialchars($reportItem->itemNo) ?>
                </span>
                untuk laporan tanggal
                <span class="font-black text-black">
                    <?= htmlspecialchars($formattedDate) ?>
                </span>
            </p>

        </div>


        <!-- Error -->
        <?php if (!empty($error)) { ?>

            <div
                class="mb-6 p-5
                       bg-red-100
                       border-2 border-black
                       shadow-[6px_6px_0px_0px_#000]"
            >

                <p class="font-black uppercase text-red-700">
                    Terjadi Kesalahan
                </p>

                <p class="mt-1 text-sm font-bold text-black">
                    <?= htmlspecialchars($error) ?>
                </p>

            </div>

        <?php } ?>


        <!-- Form Card -->
        <div
            class="bg-white
                   border-2 border-black
                   shadow-[8px_8px_0px_0px_#000]"
        >

            <div class="p-6 sm:p-8">

                <form
                    action="/report/item/edit/<?= htmlspecialchars($reportItem->id) ?>"
                    method="POST"
                >

                    <!-- Section 01 -->
                    <div class="flex items-center gap-4 mb-8">

                        <div
                            class="w-12 h-12 shrink-0
                                   bg-[#00d982]
                                   border-4 border-black
                                   flex items-center justify-center
                                   font-black text-black
                                   shadow-[4px_4px_0px_0px_#000]"
                        >
                            01
                        </div>

                        <div>

                            <h2 class="text-2xl font-black uppercase">
                                Rincian Giat
                            </h2>

                            <p class="text-sm text-gray-500 font-bold">
                                Ubah data aktivitas laporan
                            </p>

                        </div>

                    </div>


                    <!-- Grid Searchable -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                        <!-- TARGET -->
                        <div class="searchable-select">

                            <label class="block mb-2 text-sm font-black uppercase">
                                Sasaran / Target
                            </label>

                            <div class="relative">

                                <input
                                    type="text"
                                    autocomplete="off"
                                    value=""
                                    placeholder="Ketik untuk mencari sasaran..."
                                    class="search-input w-full px-4 py-3
                                           bg-gray-50
                                           border-2 border-black
                                           font-bold text-black
                                           outline-none
                                           focus:bg-white
                                           focus:shadow-[4px_4px_0px_0px_#00d982]"
                                >

                                <input
                                    type="hidden"
                                    name="target_option_id"
                                    class="selected-value"
                                    value="<?= htmlspecialchars($reportItem->targetOptionId) ?>"
                                    required
                                >

                                <div
                                    class="search-results hidden absolute z-50
                                           left-0 right-0 mt-1
                                           bg-white border-2 border-black
                                           max-h-60 overflow-y-auto
                                           shadow-[5px_5px_0px_0px_#000]"
                                >

                                    <?php foreach ($options as $opt) { ?>

                                        <?php
                                        if (
                                            strcasecmp($opt->category, 'Target') !== 0 &&
                                            strcasecmp($opt->category, 'Sasaran') !== 0
                                        ) {
                                            continue;
                                        }
                                        ?>

                                        <button
                                            type="button"
                                            class="search-option w-full text-left
                                                   px-4 py-3
                                                   border-b-2 border-black
                                                   font-bold
                                                   hover:bg-[#00d982]
                                                   transition-colors"
                                            data-value="<?= htmlspecialchars($opt->id) ?>"
                                            data-label="<?= htmlspecialchars($opt->name) ?>"
                                        >
                                            <?= htmlspecialchars($opt->name) ?>
                                        </button>

                                    <?php } ?>

                                </div>

                            </div>

                        </div>


                        <!-- ACTIVITY -->
                        <div class="searchable-select">

                            <label class="block mb-2 text-sm font-black uppercase">
                                Jenis Kegiatan (Giat)
                            </label>

                            <div class="relative">

                                <input
                                    type="text"
                                    autocomplete="off"
                                    placeholder="Ketik untuk mencari giat..."
                                    class="search-input w-full px-4 py-3
                                           bg-gray-50
                                           border-2 border-black
                                           font-bold text-black
                                           outline-none
                                           focus:bg-white
                                           focus:shadow-[4px_4px_0px_0px_#00d982]"
                                >

                                <input
                                    type="hidden"
                                    name="activity_option_id"
                                    class="selected-value"
                                    value="<?= htmlspecialchars($reportItem->activityOptionId) ?>"
                                    required
                                >

                                <div
                                    class="search-results hidden absolute z-50
                                           left-0 right-0 mt-1
                                           bg-white border-2 border-black
                                           max-h-60 overflow-y-auto
                                           shadow-[5px_5px_0px_0px_#000]"
                                >

                                    <?php foreach ($options as $opt) { ?>

                                        <?php
                                        if (
                                            strcasecmp($opt->category, 'Activity') !== 0 &&
                                            strcasecmp($opt->category, 'Kegiatan') !== 0 &&
                                            strcasecmp($opt->category, 'Giat') !== 0
                                        ) {
                                            continue;
                                        }
                                        ?>

                                        <button
                                            type="button"
                                            class="search-option w-full text-left
                                                   px-4 py-3
                                                   border-b-2 border-black
                                                   font-bold
                                                   hover:bg-[#00d982]
                                                   transition-colors"
                                            data-value="<?= htmlspecialchars($opt->id) ?>"
                                            data-label="<?= htmlspecialchars($opt->name) ?>"
                                        >
                                            <?= htmlspecialchars($opt->name) ?>
                                        </button>

                                    <?php } ?>

                                </div>

                            </div>

                        </div>


                        <!-- PERSONNEL -->
                        <!-- PERSONNEL -->
<div class="searchable-select">

    <label class="block mb-2 text-sm font-black uppercase">
        Kuat Personel
    </label>

    <div class="relative">

        <input
            type="text"
            autocomplete="off"
            placeholder="Ketik untuk mencari personel..."
            class="search-input w-full px-4 py-3
                   bg-gray-50
                   border-2 border-black
                   font-bold text-black
                   outline-none
                   focus:bg-white
                   focus:shadow-[4px_4px_0px_0px_#00d982]"
        >

        <input
            type="hidden"
            name="personnel_strength_option_id"
            class="selected-value"
            value="<?= htmlspecialchars($reportItem->personnelStrengthOptionId) ?>"
            required
        >

        <div
            class="search-results hidden absolute z-50
                   left-0 right-0 mt-1
                   bg-white border-2 border-black
                   max-h-60 overflow-y-auto
                   shadow-[5px_5px_0px_0px_#000]"
        >

            <?php foreach ($options as $opt) { ?>

                <?php
                if (
                    strcasecmp($opt->category, 'personnel_strength') !== 0 &&
                    strcasecmp($opt->category, 'Personnel') !== 0 &&
                    strcasecmp($opt->category, 'Personel') !== 0 &&
                    strcasecmp($opt->category, 'Kuat Personel') !== 0
                ) {
                    continue;
                }
                ?>

                <button
                    type="button"
                    class="search-option w-full text-left
                           px-4 py-3
                           border-b-2 border-black
                           font-bold
                           hover:bg-[#00d982]
                           transition-colors"
                    data-value="<?= htmlspecialchars($opt->id) ?>"
                    data-label="<?= htmlspecialchars($opt->name) ?>"
                >
                    <?= htmlspecialchars($opt->name) ?>
                </button>

            <?php } ?>

        </div>

    </div>

</div>


                        <!-- LOCATION -->
                        <div class="searchable-select">

                            <label class="block mb-2 text-sm font-black uppercase">
                                Lokasi Pelaksanaan
                            </label>

                            <div class="relative">

                                <input
                                    type="text"
                                    autocomplete="off"
                                    placeholder="Ketik untuk mencari lokasi..."
                                    class="search-input w-full px-4 py-3
                                           bg-gray-50
                                           border-2 border-black
                                           font-bold text-black
                                           outline-none
                                           focus:bg-white
                                           focus:shadow-[4px_4px_0px_0px_#00d982]"
                                >

                                <input
                                    type="hidden"
                                    name="location_option_id"
                                    class="selected-value"
                                    value="<?= htmlspecialchars($reportItem->locationOptionId) ?>"
                                    required
                                >

                                <div
                                    class="search-results hidden absolute z-50
                                           left-0 right-0 mt-1
                                           bg-white border-2 border-black
                                           max-h-60 overflow-y-auto
                                           shadow-[5px_5px_0px_0px_#000]"
                                >

                                    <?php foreach ($options as $opt) { ?>

                                        <?php
                                        if (
                                            strcasecmp($opt->category, 'Location') !== 0 &&
                                            strcasecmp($opt->category, 'Lokasi') !== 0
                                        ) {
                                            continue;
                                        }
                                        ?>

                                        <button
                                            type="button"
                                            class="search-option w-full text-left
                                                   px-4 py-3
                                                   border-b-2 border-black
                                                   font-bold
                                                   hover:bg-[#00d982]
                                                   transition-colors"
                                            data-value="<?= htmlspecialchars($opt->id) ?>"
                                            data-label="<?= htmlspecialchars($opt->name) ?>"
                                        >
                                            <?= htmlspecialchars($opt->name) ?>
                                        </button>

                                    <?php } ?>

                                </div>

                            </div>

                        </div>


                        <!-- PIC -->
                        <div class="searchable-select">

                            <label class="block mb-2 text-sm font-black uppercase">
                                Perwira Penanggung Jawab
                            </label>

                            <div class="relative">

                                <input
                                    type="text"
                                    autocomplete="off"
                                    placeholder="Ketik untuk mencari PIC..."
                                    class="search-input w-full px-4 py-3
                                           bg-gray-50
                                           border-2 border-black
                                           font-bold text-black
                                           outline-none
                                           focus:bg-white
                                           focus:shadow-[4px_4px_0px_0px_#00d982]"
                                >

                                <input
                                    type="hidden"
                                    name="person_in_charge_option_id"
                                    class="selected-value"
                                    value="<?= htmlspecialchars($reportItem->personInChargeOptionId) ?>"
                                    required
                                >

                                <div
                                    class="search-results hidden absolute z-50
                                           left-0 right-0 mt-1
                                           bg-white border-2 border-black
                                           max-h-60 overflow-y-auto
                                           shadow-[5px_5px_0px_0px_#000]"
                                >

                                    <?php foreach ($options as $opt) { ?>

                                        <?php
                                        if (
                                            strcasecmp($opt->category, 'PIC') !== 0 &&
                                            strcasecmp($opt->category, 'Penanggung Jawab') !== 0 &&
                                            strcasecmp($opt->category, 'person_in_charge') !== 0
                                        ) {
                                            continue;
                                        }
                                        ?>

                                        <button
                                            type="button"
                                            class="search-option w-full text-left
                                                   px-4 py-3
                                                   border-b-2 border-black
                                                   font-bold
                                                   hover:bg-[#00d982]
                                                   transition-colors"
                                            data-value="<?= htmlspecialchars($opt->id) ?>"
                                            data-label="<?= htmlspecialchars($opt->name) ?>"
                                        >
                                            <?= htmlspecialchars($opt->name) ?>
                                        </button>

                                    <?php } ?>

                                </div>

                            </div>

                        </div>


                        <!-- EXPECTED RESULT -->
                        <div class="searchable-select">

                            <label class="block mb-2 text-sm font-black uppercase">
                                Hasil yang Diharapkan
                            </label>

                            <div class="relative">

                                <input
                                    type="text"
                                    autocomplete="off"
                                    placeholder="Ketik untuk mencari hasil..."
                                    class="search-input w-full px-4 py-3
                                           bg-gray-50
                                           border-2 border-black
                                           font-bold text-black
                                           outline-none
                                           focus:bg-white
                                           focus:shadow-[4px_4px_0px_0px_#00d982]"
                                >

                                <input
                                    type="hidden"
                                    name="expected_result_option_id"
                                    class="selected-value"
                                    value="<?= htmlspecialchars($reportItem->expectedResultOptionId) ?>"
                                    required
                                >

                                <div
                                    class="search-results hidden absolute z-50
                                           left-0 right-0 mt-1
                                           bg-white border-2 border-black
                                           max-h-60 overflow-y-auto
                                           shadow-[5px_5px_0px_0px_#000]"
                                >

                                    <?php foreach ($options as $opt) { ?>

                                        <?php
                                        if (
                                            strcasecmp($opt->category, 'Result') !== 0 &&
                                            strcasecmp($opt->category, 'Hasil') !== 0 &&
                                            strcasecmp($opt->category, 'Hasil Diharapkan') !== 0 &&
                                            strcasecmp($opt->category, 'expected_result') !== 0
                                        ) {
                                            continue;
                                        }
                                        ?>

                                        <button
                                            type="button"
                                            class="search-option w-full text-left
                                                   px-4 py-3
                                                   border-b-2 border-black
                                                   font-bold
                                                   hover:bg-[#00d982]
                                                   transition-colors"
                                            data-value="<?= htmlspecialchars($opt->id) ?>"
                                            data-label="<?= htmlspecialchars($opt->name) ?>"
                                        >
                                            <?= htmlspecialchars($opt->name) ?>
                                        </button>

                                    <?php } ?>

                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- Divider -->
                    <div class="my-8 border-t-4 border-black"></div>


                    <!-- Remarks -->
                    <div>

                        <div class="flex items-center gap-3 mb-4">

                            <div
                                class="w-10 h-10
                                       bg-black text-white
                                       flex items-center justify-center
                                       font-black"
                            >
                                02
                            </div>

                            <div>

                                <h2 class="text-xl font-black uppercase">
                                    Uraian Aktivitas
                                </h2>

                                <p class="text-sm text-gray-500 font-bold">
                                    Tambahkan keterangan kegiatan
                                </p>

                            </div>

                        </div>

                        <textarea
                            id="remarks"
                            name="remarks"
                            rows="4"
                            placeholder="Tuliskan keterangan atau uraian aktivitas..."
                            class="w-full px-4 py-3
                                   bg-gray-50
                                   border-2 border-black
                                   font-bold text-black
                                   outline-none resize-y
                                   focus:bg-white
                                   focus:shadow-[4px_4px_0px_0px_#00d982]"
                        ><?= htmlspecialchars($_POST['remarks'] ?? $reportItem->remarks ?? '') ?></textarea>

                    </div>


                    <!-- Actions -->
                    <div
                        class="mt-8 pt-6
                               border-t-4 border-black
                               flex flex-col sm:flex-row
                               justify-end gap-4"
                    >

                        <a
                            href="/report/<?= htmlspecialchars($date) ?>"
                            class="px-6 py-3
                                   bg-white
                                   border-2 border-black
                                   font-black uppercase text-center
                                   shadow-[5px_5px_0px_0px_#000]
                                   hover:shadow-none
                                   hover:translate-x-1 hover:translate-y-1
                                   transition-all"
                        >
                            Batal
                        </a>

                        <button
                            type="submit"
                            class="px-6 py-3
                                   bg-[#00d982]
                                   border-2 border-black
                                   font-black uppercase
                                   shadow-[5px_5px_0px_0px_#000]
                                   hover:shadow-none
                                   hover:translate-x-1 hover:translate-y-1
                                   transition-all"
                        >
                            Simpan Perubahan Giat
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>


<!-- Validation Modal -->
<div
    id="validationModal"
    class="fixed inset-0 z-[9999] hidden
           items-center justify-center
           bg-black/70 px-4"
>
    <div
        class="w-full max-w-md
               bg-white
               border-4 border-black
               shadow-[10px_10px_0px_0px_#00d982]"
    >

        <div
            class="flex items-center justify-between
                   bg-black text-white
                   px-5 py-4
                   border-b-4 border-black"
        >

            <div class="flex items-center gap-3">

                <div
                    class="w-10 h-10
                           bg-[#00d982]
                           border-2 border-white
                           flex items-center justify-center
                           text-black font-black text-xl"
                >
                    !
                </div>

                <h3 class="font-black uppercase tracking-wide">
                    Data Belum Lengkap
                </h3>

            </div>

            <button
                type="button"
                id="closeValidationModal"
                class="w-9 h-9
                       bg-white text-black
                       border-2 border-white
                       font-black
                       hover:bg-[#00d982]
                       transition-colors"
            >
                ×
            </button>

        </div>

        <div class="p-6">

            <p class="font-bold text-gray-700 leading-relaxed">
                Silakan lengkapi seluruh data yang diperlukan
                sebelum menyimpan perubahan rincian giat.
            </p>

        </div>

        <div class="px-6 pb-6 flex justify-end">

            <button
                type="button"
                id="confirmValidationModal"
                class="px-6 py-3
                       bg-[#00d982]
                       border-2 border-black
                       font-black uppercase
                       shadow-[5px_5px_0px_0px_#000]
                       hover:shadow-none
                       hover:translate-x-1 hover:translate-y-1
                       transition-all"
            >
                Mengerti
            </button>

        </div>

    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function () {

    /*
     * ==========================================
     * SEARCHABLE DROPDOWN
     * ==========================================
     */

    const searchableSelects =
        document.querySelectorAll('.searchable-select');


    searchableSelects.forEach(function (container) {

        const input =
            container.querySelector('.search-input');

        const hiddenInput =
            container.querySelector('.selected-value');

        const results =
            container.querySelector('.search-results');

        const options =
            container.querySelectorAll('.search-option');


        /*
         * Set nilai nama berdasarkan ID
         * ketika halaman edit pertama kali dibuka.
         */
        const selectedValue =
            hiddenInput.value;

        if (selectedValue) {

            options.forEach(function (option) {

                if (option.dataset.value === selectedValue) {

                    input.value =
                        option.dataset.label;

                }

            });

        }


        /*
         * Buka dropdown
         */
        input.addEventListener('focus', function () {

            results.classList.remove('hidden');

            filterOptions();

        });


        /*
         * Realtime search
         */
        input.addEventListener('input', function () {

            /*
             * User mulai mengetik ulang,
             * maka pilihan sebelumnya dibatalkan.
             */
            hiddenInput.value = '';

            /*
             * Hapus status error
             */
            input.classList.remove(
                'border-red-600',
                'bg-red-50'
            );

            results.classList.remove('hidden');

            filterOptions();

        });


        /*
         * Pilih option
         */
        options.forEach(function (option) {

            option.addEventListener('click', function () {

                const value =
                    this.dataset.value;

                const label =
                    this.dataset.label;


                input.value = label;

                hiddenInput.value = value;


                input.classList.remove(
                    'border-red-600',
                    'bg-red-50'
                );


                results.classList.add('hidden');

            });

        });


        /*
         * Filter realtime
         */
        function filterOptions() {

            const keyword =
                input.value
                    .toLowerCase()
                    .trim();

            let found = false;


            options.forEach(function (option) {

                const label =
                    option.dataset.label
                        .toLowerCase();


                if (label.includes(keyword)) {

                    option.classList.remove('hidden');

                    found = true;

                } else {

                    option.classList.add('hidden');

                }

            });


            /*
             * Pesan jika tidak ditemukan
             */
            let emptyMessage =
                results.querySelector('.no-result');


            if (!found) {

                if (!emptyMessage) {

                    emptyMessage =
                        document.createElement('div');

                    emptyMessage.className =
                        'no-result px-4 py-3 ' +
                        'text-sm font-black text-gray-500';

                    emptyMessage.textContent =
                        'Tidak ada hasil ditemukan.';

                    results.appendChild(emptyMessage);

                }

            } else {

                if (emptyMessage) {

                    emptyMessage.remove();

                }

            }

        }

    });


    /*
     * ==========================================
     * TUTUP DROPDOWN KETIKA KLIK DI LUAR
     * ==========================================
     */

    document.addEventListener('click', function (event) {

        searchableSelects.forEach(function (container) {

            if (!container.contains(event.target)) {

                const results =
                    container.querySelector('.search-results');

                results.classList.add('hidden');

            }

        });

    });


    /*
     * ==========================================
     * VALIDATION MODAL
     * ==========================================
     */

    const form =
        document.querySelector('form');

    const validationModal =
        document.getElementById('validationModal');

    const closeValidationModal =
        document.getElementById('closeValidationModal');

    const confirmValidationModal =
        document.getElementById('confirmValidationModal');


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


    closeValidationModal.addEventListener(
        'click',
        closeModal
    );


    confirmValidationModal.addEventListener(
        'click',
        closeModal
    );


    validationModal.addEventListener(
        'click',
        function (event) {

            if (event.target === validationModal) {

                closeModal();

            }

        }
    );


    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape' &&
                !validationModal.classList.contains('hidden')
            ) {

                closeModal();

            }

        }
    );


    /*
     * ==========================================
     * FORM VALIDATION
     * ==========================================
     */

    form.addEventListener('submit', function (event) {

        const selectedInputs =
            this.querySelectorAll('.selected-value');

        let valid = true;

        let firstInvalidInput = null;


        selectedInputs.forEach(function (input) {

            const container =
                input.closest('.searchable-select');

            const searchInput =
                container.querySelector('.search-input');


            if (!input.value) {

                valid = false;


                searchInput.classList.add(
                    'border-red-600',
                    'bg-red-50'
                );


                if (!firstInvalidInput) {

                    firstInvalidInput =
                        searchInput;

                }

            } else {

                searchInput.classList.remove(
                    'border-red-600',
                    'bg-red-50'
                );

            }

        });


        if (!valid) {

            event.preventDefault();

            openValidationModal();


            if (firstInvalidInput) {

                firstInvalidInput.dataset.focusAfterClose =
                    'true';

            }

        }

    });


    /*
     * Setelah modal ditutup,
     * fokus ke field pertama yang kosong.
     */
    function focusInvalidInput() {

        const input =
            document.querySelector(
                '.search-input[data-focus-after-close="true"]'
            );


        if (input) {

            delete input.dataset.focusAfterClose;

            input.focus();

        }

    }


    closeValidationModal.addEventListener(
        'click',
        focusInvalidInput
    );


    confirmValidationModal.addEventListener(
        'click',
        focusInvalidInput
    );

});
</script>