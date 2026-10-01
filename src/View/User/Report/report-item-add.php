<div class="min-h-screen bg-gray-50 py-12 px-4 mt-24 sm:px-6 lg:px-8">

    <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="mb-8">

            <a href="/report/<?= htmlspecialchars($date) ?>" class="inline-flex items-center gap-2 px-4 py-2 mb-6
                       bg-white border-2 border-black
                       font-bold text-black
                       shadow-[4px_4px_0px_0px_#000]
                       hover:shadow-none
                       hover:translate-x-1 hover:translate-y-1
                       transition-all">
                ← Kembali ke Detail Laporan
            </a>

            <div class="flex items-center gap-3 mb-4">
                <span class="bg-black text-white px-3 py-1 text-xs font-black uppercase">
                    Report Activity
                </span>

                <span class="text-sm font-bold text-gray-500">
                    <?= htmlspecialchars($formattedDate) ?>
                </span>
            </div>

            <h1 class="text-4xl sm:text-5xl font-black uppercase tracking-tight text-black">
                Tambah Item Giat
            </h1>

            <p class="mt-3 text-gray-600 font-medium">
                Tambahkan rincian kegiatan pada laporan tanggal
                <span class="font-black text-black">
                    <?= htmlspecialchars($formattedDate) ?>
                </span>
            </p>
        </div>


        <!-- Error -->
        <?php if (!empty($error)) { ?>

            <div class="mb-6 p-5 bg-red-100 border-2 border-black
                        shadow-[6px_6px_0px_0px_#000]">

                <p class="font-black uppercase text-red-700">
                    Terjadi Kesalahan
                </p>

                <p class="mt-1 text-sm font-bold text-black">
                    <?= htmlspecialchars($error) ?>
                </p>

            </div>

        <?php } ?>


        <!-- Form -->
        <div class="bg-white border-2 border-black
                    shadow-[8px_8px_0px_0px_#000]">

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
                            <h2 class="text-2xl font-black uppercase">
                                Rincian Giat
                            </h2>

                            <p class="text-sm text-gray-500 font-bold">
                                Pilih data untuk aktivitas laporan
                            </p>
                        </div>

                    </div>


                    <!-- Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                        <!-- TARGET -->
                        <div class="searchable-select">

                            <label class="block mb-2 text-sm font-black uppercase">
                                Sasaran
                            </label>

                            <div class="relative">

                                <input type="text" autocomplete="off" placeholder="Ketik untuk mencari sasaran..."
                                    class="search-input w-full px-4 py-3
                                           bg-gray-50
                                           border-2 border-black
                                           font-bold text-black
                                           outline-none
                                           focus:bg-white
                                           focus:shadow-[4px_4px_0px_0px_#00d982]" data-name="target_option_id">

                                <input type="hidden" name="target_option_id" class="selected-value" required>

                                <div class="search-results hidden absolute z-50
                                           left-0 right-0 mt-1
                                           bg-white border-2 border-black
                                           max-h-60 overflow-y-auto
                                           shadow-[5px_5px_0px_0px_#000]">

                                    <?php foreach ($options as $option) { ?>

                                        <?php
                                        if (
                                            $option->category !== 'target' &&
                                            $option->category !== 'Sasaran'
                                        ) {
                                            continue;
                                        }
                                        ?>

                                        <button type="button" class="search-option w-full text-left
                                                   px-4 py-3
                                                   border-b-2 border-black
                                                   font-bold
                                                   hover:bg-[#00d982]
                                                   transition-colors" data-value="<?= htmlspecialchars($option->id) ?>"
                                            data-label="<?= htmlspecialchars($option->name) ?>">
                                            <?= htmlspecialchars($option->name) ?>
                                        </button>

                                    <?php } ?>

                                </div>

                            </div>

                        </div>


                        <!-- ACTIVITY -->
                        <div class="searchable-select">

                            <label class="block mb-2 text-sm font-black uppercase">
                                Giat / Aktivitas
                            </label>

                            <div class="relative">

                                <input type="text" autocomplete="off" placeholder="Ketik untuk mencari giat..." class="search-input w-full px-4 py-3
                                           bg-gray-50
                                           border-2 border-black
                                           font-bold text-black
                                           outline-none
                                           focus:bg-white
                                           focus:shadow-[4px_4px_0px_0px_#00d982]" data-name="activity_option_id">

                                <input type="hidden" name="activity_option_id" class="selected-value" required>

                                <div class="search-results hidden absolute z-50
                                           left-0 right-0 mt-1
                                           bg-white border-2 border-black
                                           max-h-60 overflow-y-auto
                                           shadow-[5px_5px_0px_0px_#000]">

                                    <?php foreach ($options as $option) { ?>

                                        <?php
                                        if (
                                            $option->category !== 'activity' &&
                                            $option->category !== 'Kegiatan' &&
                                            $option->category !== 'Giat'
                                        ) {
                                            continue;
                                        }
                                        ?>

                                        <button type="button" class="search-option w-full text-left
                                                   px-4 py-3
                                                   border-b-2 border-black
                                                   font-bold
                                                   hover:bg-[#00d982]
                                                   transition-colors" data-value="<?= htmlspecialchars($option->id) ?>"
                                            data-label="<?= htmlspecialchars($option->name) ?>">
                                            <?= htmlspecialchars($option->name) ?>
                                        </button>

                                    <?php } ?>

                                </div>

                            </div>

                        </div>


                        <!-- PERSONNEL -->
                        <div class="searchable-select">

                            <label class="block mb-2 text-sm font-black uppercase">
                                Kuat Personel
                            </label>

                            <div class="relative">

                                <input type="text" autocomplete="off" placeholder="Ketik untuk mencari personel..."
                                    class="search-input w-full px-4 py-3
                                           bg-gray-50
                                           border-2 border-black
                                           font-bold text-black
                                           outline-none
                                           focus:bg-white
                                           focus:shadow-[4px_4px_0px_0px_#00d982]"
                                    data-name="personnel_strength_option_id">

                                <input type="hidden" name="personnel_strength_option_id" class="selected-value"
                                    required>

                                <div class="search-results hidden absolute z-50
                                           left-0 right-0 mt-1
                                           bg-white border-2 border-black
                                           max-h-60 overflow-y-auto
                                           shadow-[5px_5px_0px_0px_#000]">

                                    <?php foreach ($options as $option) { ?>

                                        <?php
                                        if (
                                            $option->category !== 'personnel_strength' &&
                                            $option->category !== 'Personel' &&
                                            $option->category !== 'Kuat Personel'
                                        ) {
                                            continue;
                                        }
                                        ?>

                                        <button type="button" class="search-option w-full text-left
                                                   px-4 py-3
                                                   border-b-2 border-black
                                                   font-bold
                                                   hover:bg-[#00d982]
                                                   transition-colors" data-value="<?= htmlspecialchars($option->id) ?>"
                                            data-label="<?= htmlspecialchars($option->name) ?>">
                                            <?= htmlspecialchars($option->name) ?>
                                        </button>

                                    <?php } ?>

                                </div>

                            </div>

                        </div>


                        <!-- LOCATION -->
                        <div class="searchable-select">

                            <label class="block mb-2 text-sm font-black uppercase">
                                Lokasi
                            </label>

                            <div class="relative">

                                <input type="text" autocomplete="off" placeholder="Ketik untuk mencari lokasi..." class="search-input w-full px-4 py-3
                                           bg-gray-50
                                           border-2 border-black
                                           font-bold text-black
                                           outline-none
                                           focus:bg-white
                                           focus:shadow-[4px_4px_0px_0px_#00d982]" data-name="location_option_id">

                                <input type="hidden" name="location_option_id" class="selected-value" required>

                                <div class="search-results hidden absolute z-50
                                           left-0 right-0 mt-1
                                           bg-white border-2 border-black
                                           max-h-60 overflow-y-auto
                                           shadow-[5px_5px_0px_0px_#000]">

                                    <?php foreach ($options as $option) { ?>

                                        <?php
                                        if (
                                            $option->category !== 'location' &&
                                            $option->category !== 'Lokasi'
                                        ) {
                                            continue;
                                        }
                                        ?>

                                        <button type="button" class="search-option w-full text-left
                                                   px-4 py-3
                                                   border-b-2 border-black
                                                   font-bold
                                                   hover:bg-[#00d982]
                                                   transition-colors" data-value="<?= htmlspecialchars($option->id) ?>"
                                            data-label="<?= htmlspecialchars($option->name) ?>">
                                            <?= htmlspecialchars($option->name) ?>
                                        </button>

                                    <?php } ?>

                                </div>

                            </div>

                        </div>



                        <!-- PIC -->
                        <div class="searchable-select">

                            <label class="block mb-2 text-sm font-black uppercase">
                                Penanggung Jawab
                            </label>

                            <div class="relative">

                                <input type="text" autocomplete="off" placeholder="Ketik untuk mencari PIC..." class="search-input w-full px-4 py-3
                   bg-gray-50
                   border-2 border-black
                   font-bold text-black
                   outline-none
                   focus:bg-white
                   focus:shadow-[4px_4px_0px_0px_#00d982]" data-name="person_in_charge_option_id">

                                <input type="hidden" name="person_in_charge_option_id" class="selected-value" required>

                                <div class="search-results hidden absolute z-50
                   left-0 right-0 mt-1
                   bg-white border-2 border-black
                   max-h-60 overflow-y-auto
                   shadow-[5px_5px_0px_0px_#000]">

                                    <?php foreach ($options as $option) { ?>

                                        <?php
                                        if (
                                            $option->category !== 'person_in_charge' &&
                                            $option->category !== 'Penanggung Jawab'
                                        ) {
                                            continue;
                                        }
                                        ?>

                                        <button type="button" class="search-option w-full text-left
                           px-4 py-3
                           border-b-2 border-black
                           font-bold
                           hover:bg-[#00d982]
                           transition-colors" data-value="<?= htmlspecialchars($option->id) ?>"
                                            data-label="<?= htmlspecialchars($option->name) ?>">
                                            <?= htmlspecialchars($option->name) ?>
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

                                <input type="text" autocomplete="off" placeholder="Ketik untuk mencari hasil..." class="search-input w-full px-4 py-3
                   bg-gray-50
                   border-2 border-black
                   font-bold text-black
                   outline-none
                   focus:bg-white
                   focus:shadow-[4px_4px_0px_0px_#00d982]" data-name="expected_result_option_id">

                                <input type="hidden" name="expected_result_option_id" class="selected-value" required>

                                <div class="search-results hidden absolute z-50
                   left-0 right-0 mt-1
                   bg-white border-2 border-black
                   max-h-60 overflow-y-auto
                   shadow-[5px_5px_0px_0px_#000]">

                                    <?php foreach ($options as $option) { ?>

                                        <?php
                                        if (
                                            $option->category !== 'expected_result' &&
                                            $option->category !== 'Hasil' &&
                                            $option->category !== 'Hasil Diharapkan'
                                        ) {
                                            continue;
                                        }
                                        ?>

                                        <button type="button" class="search-option w-full text-left
                           px-4 py-3
                           border-b-2 border-black
                           font-bold
                           hover:bg-[#00d982]
                           transition-colors" data-value="<?= htmlspecialchars($option->id) ?>"
                                            data-label="<?= htmlspecialchars($option->name) ?>">
                                            <?= htmlspecialchars($option->name) ?>
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

                            <div class="w-10 h-10
                                        bg-black text-white
                                        flex items-center justify-center
                                        font-black">
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

                        <textarea id="remarks" name="remarks" rows="4"
                            placeholder="Tuliskan keterangan atau uraian aktivitas..." class="w-full px-4 py-3
                                   bg-gray-50
                                   border-2 border-black
                                   font-bold text-black
                                   outline-none resize-y
                                   focus:bg-white
                                   focus:shadow-[4px_4px_0px_0px_#00d982]"></textarea>

                    </div>


                    <!-- Actions -->
                    <div class="mt-8 pt-6 border-t-4 border-black
                                flex flex-col sm:flex-row
                                justify-end gap-4">

                        <a href="/report/<?= htmlspecialchars($date) ?>" class="px-6 py-3
                                   bg-white
                                   border-2 border-black
                                   font-black uppercase text-center
                                   shadow-[5px_5px_0px_0px_#000]
                                   hover:shadow-none
                                   hover:translate-x-1 hover:translate-y-1
                                   transition-all">
                            Batal
                        </a>

                        <button type="submit" class="px-6 py-3
                                   bg-[#00d982]
                                   border-2 border-black
                                   font-black uppercase
                                   shadow-[5px_5px_0px_0px_#000]
                                   hover:shadow-none
                                   hover:translate-x-1 hover:translate-y-1
                                   transition-all">
                            Simpan Rincian Giat
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<div id="validationModal" class="fixed inset-0 z-[9999] hidden
           items-center justify-center
           bg-black/70 px-4">

    <div class="w-full max-w-md
               bg-white
               border-4 border-black
               shadow-[8px_8px_0px_0px_#00d982]">

        <!-- Header -->
        <div class="bg-black text-white
                   px-5 py-4
                   flex items-center justify-between">

            <h3 class="font-black uppercase">
                Data Belum Lengkap
            </h3>

            <button type="button" onclick="closeValidationModal()" class="text-2xl font-black
                       hover:text-[#00d982]">
                ×
            </button>

        </div>


        <!-- Body -->
        <div class="p-6">

            <p class="font-bold text-gray-700">
                Data belum lengkap.
                Silakan lengkapi seluruh pilihan
                sebelum menyimpan rincian giat.
            </p>

        </div>


        <!-- Footer -->
        <div class="px-6 pb-6 flex justify-end">

            <button type="button" onclick="closeValidationModal()" class="px-6 py-3
                       bg-[#00d982]
                       border-2 border-black
                       font-black uppercase
                       shadow-[4px_4px_0px_0px_#000]
                       hover:shadow-none
                       hover:translate-x-1
                       hover:translate-y-1
                       transition-all">
                Mengerti
            </button>

        </div>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const searchableSelects = document.querySelectorAll('.searchable-select');

        searchableSelects.forEach(function (container) {

            const input = container.querySelector('.search-input');
            const hiddenInput = container.querySelector('.selected-value');
            const results = container.querySelector('.search-results');
            const options = container.querySelectorAll('.search-option');

            /*
             * Buka dropdown ketika input mendapatkan fokus
             */
            input.addEventListener('focus', function () {

                results.classList.remove('hidden');

                filterOptions();

            });


            /*
             * Realtime search
             */
            input.addEventListener('input', function () {

                // Ketika user mengetik ulang,
                // pilihan sebelumnya dianggap batal.
                hiddenInput.value = '';

                results.classList.remove('hidden');

                filterOptions();

            });


            /*
             * Pilih option
             */
            options.forEach(function (option) {

                option.addEventListener('click', function () {

                    const value = this.dataset.value;
                    const label = this.dataset.label;

                    input.value = label;
                    hiddenInput.value = value;

                    results.classList.add('hidden');

                });

            });


            /*
             * Filter option berdasarkan teks input
             */
            function filterOptions() {

                const keyword = input.value
                    .toLowerCase()
                    .trim();

                let found = false;

                options.forEach(function (option) {

                    const label = option.dataset.label
                        .toLowerCase();

                    if (label.includes(keyword)) {

                        option.classList.remove('hidden');

                        found = true;

                    } else {

                        option.classList.add('hidden');

                    }

                });


                /*
                 * Kalau tidak ada hasil
                 */
                let emptyMessage = results.querySelector('.no-result');

                if (!found) {

                    if (!emptyMessage) {

                        emptyMessage = document.createElement('div');

                        emptyMessage.className =
                            'no-result px-4 py-3 text-sm font-black text-gray-500';

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
         * Tutup semua dropdown ketika klik di luar
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
         * Submit validation
         *
         * Pastikan user benar-benar memilih option,
         * bukan hanya mengetik teks.
         */
        document.querySelector('form').addEventListener('submit', function (event) {

            const selectedInputs =
                this.querySelectorAll('.selected-value');

            let valid = true;

            selectedInputs.forEach(function (input) {

                if (!input.value) {

                    valid = false;

                    const container =
                        input.closest('.searchable-select');

                    const searchInput =
                        container.querySelector('.search-input');

                    searchInput.focus();

                    searchInput.classList.add(
                        'border-red-600'
                    );

                }

            });

            if (!valid) {

                event.preventDefault();

                openValidationModal();

            }

        });

    });

    function openValidationModal() {

        const modal =
            document.getElementById('validationModal');

        modal.classList.remove('hidden');

        modal.classList.add('flex');

    }

    function closeValidationModal() {

        const modal =
            document.getElementById('validationModal');

        modal.classList.add('hidden');

        modal.classList.remove('flex');

    }
</script>

