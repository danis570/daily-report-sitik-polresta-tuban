<div class="min-h-screen bg-gray-50 py-12 px-4 mt-24 sm:px-6 lg:px-8">

    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-5 mb-8">

            <div>
                <div class="inline-flex items-center bg-black text-white px-3 py-1 mb-3
                            text-xs font-black uppercase tracking-widest
                            border-2 border-black shadow-brutal-dark">
                    Report Options
                </div>

                <h1 class="text-4xl sm:text-5xl font-black text-black uppercase tracking-tight">
                    <?= htmlspecialchars($title ?? 'Kelola Pilihan Laporan') ?>
                </h1>

                <p class="mt-3 text-sm sm:text-base font-medium text-gray-600 max-w-2xl">
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

        <!-- Search & Filter -->
        <div class="px-5 py-4 bg-gray-50 border-b-2 border-black">

            <div class="grid grid-cols-1 md:grid-cols-[1fr_220px_auto] gap-3">

                <!-- Search -->
                <div>
                    <label for="searchOption" class="block mb-2 text-xs font-black uppercase tracking-wider text-black">
                        Cari Opsi
                    </label>

                    <input type="text" id="searchOption" placeholder="Cari nama, deskripsi, ID..." class="w-full px-4 py-3
                       bg-white text-black
                       border-2 border-black
                       font-bold text-sm
                       outline-none
                       focus:ring-4 focus:ring-[#00d982]
                       shadow-[3px_3px_0px_0px_#000]">
                </div>


                <!-- Category -->
                <div>
                    <label for="filterCategory"
                        class="block mb-2 text-xs font-black uppercase tracking-wider text-black">
                        Kategori
                    </label>

                    <select id="filterCategory" class="w-full px-4 py-3
                       bg-white text-black
                       border-2 border-black
                       font-bold text-sm
                       outline-none
                       focus:ring-4 focus:ring-[#00d982]
                       shadow-[3px_3px_0px_0px_#000]">

                        <option value="">Semua Kategori</option>
                        <option value="target">Target</option>
                        <option value="activity">Activity</option>
                        <option value="personnel_strength">
                            Kekuatan Personel
                        </option>
                        <option value="location">Lokasi</option>
                        <option value="person_in_charge">
                            Penanggung Jawab
                        </option>
                        <option value="expected_result">
                            Hasil yang Ingin Dicapai
                        </option>

                    </select>
                </div>


                <!-- Reset -->
                <div class="flex items-end">

                    <button type="button" id="resetFilter" class="w-full md:w-auto
           px-5 py-3
           bg-white text-black
           border-2 border-black
           font-black text-sm uppercase
           shadow-[3px_3px_0px_0px_#000]
           hover:translate-x-[3px]
           hover:translate-y-[3px]
           hover:shadow-none
           transition-all duration-150">

                        Reset

                    </button>

                </div>

            </div>


            <!-- Result Counter -->
            <div class="mt-4 flex items-center justify-between">

                <p class="text-xs font-bold text-gray-600">
                    Menampilkan
                    <span id="visibleCount" class="text-black font-black">
                        <?= count($options ?? []) ?>
                    </span>
                    dari
                    <span class="font-black">
                        <?= count($options ?? []) ?>
                    </span>
                    opsi
                </p>

            </div>

        </div>


        <!-- Error Alert -->
        <?php if (!empty($error)) { ?>

            <div class="mb-6 bg-red-500 text-white
                        border-2 border-black
                        shadow-brutal
                        p-4">

                <div class="flex items-start gap-3">

                    <div class="shrink-0 w-7 h-7 bg-white text-red-500
                                border-2 border-black
                                flex items-center justify-center
                                font-black">
                        !
                    </div>

                    <div>
                        <p class="font-black uppercase text-sm">
                            Terjadi Kesalahan
                        </p>

                        <p class="text-sm font-medium mt-1">
                            <?= htmlspecialchars($error) ?>
                        </p>
                    </div>

                </div>
            </div>

        <?php } ?>


        <!-- Success Flash -->
        <?php if (!empty($_SESSION['flash_message'])) { ?>

            <div class="mb-6 bg-[#00d982] text-black
                        border-2 border-black
                        shadow-brutal
                        p-4">

                <div class="flex items-center gap-3">

                    <div class="shrink-0 w-7 h-7 bg-black text-[#00d982]
                                border-2 border-black
                                flex items-center justify-center
                                font-black">
                        ✓
                    </div>

                    <span class="font-bold text-sm">
                        <?= htmlspecialchars($_SESSION['flash_message']) ?>
                    </span>

                </div>

                <?php self::clearFlashMessage(); ?>

            </div>

        <?php } ?>


        <!-- Table Card -->
        <div class="bg-white border-2 border-black shadow-brutal overflow-hidden">

            <!-- Table Header -->
            <div class="flex items-center justify-between
                        px-5 py-4
                        bg-black text-white
                        border-b-2 border-black">

                <div>
                    <h2 class="font-black uppercase tracking-wide text-sm sm:text-base">
                        Daftar Opsi Laporan
                    </h2>

                    <p class="text-xs text-gray-300 mt-1">
                        Kelola pilihan yang tersedia untuk aktivitas laporan.
                    </p>
                </div>

                <div class="bg-[#00d982] text-black
                            border-2 border-white
                            px-3 py-1
                            font-black text-xs">
                    <?= count($options ?? []) ?> OPSI
                </div>

            </div>


            <!-- Table -->
            <div class="overflow-x-auto">

                <table class="min-w-full text-sm">

                    <thead class="bg-gray-100 border-b-2 border-black">

                        <tr class="text-left">

                            <th class="px-5 py-4
           font-black uppercase tracking-wider
           text-xs text-black">
                                ID
                            </th>

                            <th class="px-5 py-4
           font-black uppercase tracking-wider
           text-xs text-black">
                                Kategori
                            </th>

                            <th class="px-5 py-4
           font-black uppercase tracking-wider
           text-xs text-black">
                                Nama Opsi
                            </th>

                            <th class="px-5 py-4
           font-black uppercase tracking-wider
           text-xs text-black">
                                Deskripsi
                            </th>

                            <th class="px-5 py-4
           font-black uppercase tracking-wider
           text-xs text-black
           text-center">
                                Dilaksanakan
                            </th>

                            <th class="px-5 py-4
           font-black uppercase tracking-wider
           text-xs text-black
           text-center">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y-2 divide-black">

                        <?php if (!empty($options)) { ?>

                            <?php foreach ($options as $option) { ?>

                                <tr class="option-row hover:bg-[#eafff5] transition-colors duration-150"
                                    data-id="<?= $option->id ?>"
                                    data-category="<?= htmlspecialchars(strtolower($option->category)) ?>"
                                    data-name="<?= htmlspecialchars(strtolower($option->name)) ?>"
                                    data-description="<?= htmlspecialchars(strtolower($option->description ?? '')) ?>">

                                    <!-- ID -->
                                    <td class="px-5 py-4 whitespace-nowrap">

                                        <span class="inline-block
                                                     bg-black text-white
                                                     px-2 py-1
                                                     font-mono font-bold text-xs">
                                            #<?= $option->id ?>
                                        </span>

                                    </td>


                                    <!-- Category -->
                                    <td class="px-5 py-4 whitespace-nowrap">

                                        <span class="inline-flex
                                                     bg-[#00d982] text-black
                                                     border-2 border-black
                                                     px-2.5 py-1
                                                     text-xs
                                                     font-black
                                                     uppercase">
                                            <?= htmlspecialchars($option->category) ?>
                                        </span>

                                    </td>


                                    <!-- Name -->
                                    <td class="px-5 py-4">

                                        <div class="font-black text-black">
                                            <?= htmlspecialchars($option->name) ?>
                                        </div>

                                    </td>


                                    <!-- Description -->
                                    <td class="px-5 py-4">

                                        <div class="text-gray-600 font-medium max-w-md">
                                            <?= htmlspecialchars($option->description ?? '-') ?>
                                        </div>

                                    </td>

                                    <!-- Usage -->
                                    <td class="px-5 py-4 text-center">

                                        <?php $totalUsage = $usage[$option->id] ?? 0; ?>

                                        <span class="inline-flex items-center justify-center
                 min-w-[70px]
                 px-3 py-2
                 <?= $totalUsage > 0
                     ? 'bg-[#00d982]'
                     : 'bg-gray-100' ?>
                 text-black
                 border-2 border-black
                 font-black text-xs
                 shadow-[3px_3px_0px_0px_#000]">

                                            <?= $totalUsage ?>x

                                        </span>

                                    </td>


                                    <!-- Actions -->
                                    <td class="px-5 py-4">

                                        <div class="flex items-center justify-center gap-2">

                                            <!-- Edit -->
                                            <a href="/report/option/edit/<?= $option->id ?>" class="inline-flex items-center
                                                       px-3 py-2
                                                       bg-white text-black
                                                       border-2 border-black
                                                       font-black text-xs uppercase
                                                       shadow-[3px_3px_0px_0px_#000]
                                                       hover:translate-x-[3px]
                                                       hover:translate-y-[3px]
                                                       hover:shadow-none
                                                       transition-all duration-150">
                                                Edit
                                            </a>


                                            <!-- Delete -->
                                            <form action="/report/option/delete/<?= $option->id ?>" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus opsi ini?');"
                                                class="inline">

                                                <button type="submit" class="inline-flex items-center
                                                           px-3 py-2
                                                           bg-red-500 text-white
                                                           border-2 border-black
                                                           font-black text-xs uppercase
                                                           shadow-[3px_3px_0px_0px_#000]
                                                           hover:translate-x-[3px]
                                                           hover:translate-y-[3px]
                                                           hover:shadow-none
                                                           transition-all duration-150">
                                                    Hapus
                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            <?php } ?>

                        <?php } else { ?>

                            <!-- Empty State -->
                            <tr>

                                <td colspan="5" class="px-5 py-16">

                                    <div class="flex flex-col items-center justify-center text-center">

                                        <div class="w-16 h-16
                                                    bg-gray-100
                                                    border-2 border-black
                                                    flex items-center justify-center
                                                    text-3xl font-black
                                                    shadow-brutal
                                                    mb-5">
                                            ?
                                        </div>

                                        <h3 class="text-xl font-black uppercase">
                                            Belum Ada Opsi
                                        </h3>

                                        <p class="text-sm text-gray-500 font-medium mt-2">
                                            Tidak ada data opsi laporan yang dapat ditampilkan.
                                        </p>

                                        <a href="/report/option/add" class="mt-5
                                                   inline-flex
                                                   px-4 py-2
                                                   bg-[#00d982] text-black
                                                   border-2 border-black
                                                   font-black text-sm uppercase
                                                   shadow-brutal
                                                   hover:translate-x-[6px]
                                                   hover:translate-y-[6px]
                                                   hover:shadow-none
                                                   transition-all duration-150">
                                            + Tambah Opsi
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<script>

    const searchInput = document.getElementById('searchOption');
    const categoryFilter = document.getElementById('filterCategory');
    const resetButton = document.getElementById('resetFilter');

    const rows = document.querySelectorAll('.option-row');
    const visibleCount = document.getElementById('visibleCount');


    function filterOptions() {

        const search = searchInput.value
            .trim()
            .toLowerCase();

        const category = categoryFilter.value
            .toLowerCase();

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
                !category ||
                rowCategory === category;


            if (matchSearch && matchCategory) {

                row.style.display = '';
                count++;

            } else {

                row.style.display = 'none';

            }

        });


        visibleCount.textContent = count;

    }


    // Search realtime
    searchInput.addEventListener('input', filterOptions);


    // Filter kategori
    categoryFilter.addEventListener('change', filterOptions);


    // Reset
    resetButton.addEventListener('click', function (event) {

        event.preventDefault();

        searchInput.value = '';
        categoryFilter.value = '';

        filterOptions();

    });

</script>