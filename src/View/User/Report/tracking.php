<div class="min-h-screen bg-gray-50 dark:bg-[#121212] pt-24 pb-16 px-4 sm:px-6 lg:px-8">

    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="mb-10">

            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">

                <div>

                    <!-- Label -->
                    <div class="inline-flex items-center gap-2 mb-4">

                        <span class="w-3 h-3
                                     bg-[#00d982]
                                     border-2 border-black">
                        </span>

                        <span class="text-xs font-black uppercase
                                     tracking-[0.2em]
                                     text-gray-600
                                     dark:text-gray-400">

                            Daily Report

                        </span>

                    </div>


                    <!-- Title -->
                    <h1 class="font-black text-4xl sm:text-5xl
                               uppercase tracking-tight
                               text-[#121212] dark:text-white
                               leading-none">

                        <?= htmlspecialchars($title ?? 'Pelacakan Laporan') ?>

                    </h1>


                    <!-- Description -->
                    <p class="mt-3 text-sm sm:text-base
                              font-medium
                              text-gray-600 dark:text-gray-400
                              max-w-2xl">

                        Lacak penggunaan opsi laporan berdasarkan kategori
                        dan periode tertentu.

                    </p>

                </div>


                <!-- Tombol Kembali -->
                <div class="flex items-center">

                    <a href="/reports"
                        class="inline-flex items-center justify-center gap-2
                               px-5 py-3
                               bg-white dark:bg-[#181818]
                               text-[#121212] dark:text-white
                               font-black uppercase text-sm
                               border-4 border-[#121212]
                               dark:border-white
                               shadow-[6px_6px_0_0_#121212]
                               dark:shadow-[6px_6px_0_0_#00d982]
                               hover:shadow-none
                               hover:translate-x-[6px]
                               hover:translate-y-[6px]
                               transition-all duration-150">

                        <svg class="w-5 h-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">

                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="3"
                                d="M15 19l-7-7 7-7">
                            </path>

                        </svg>

                        <span>Kembali</span>

                    </a>

                </div>

            </div>


            <!-- Garis Brutalist -->
            <div class="mt-8 border-b-4
                        border-[#121212]
                        dark:border-white">
            </div>

        </div>


        <!-- Error -->
        <?php if (!empty($error)) { ?>

            <div class="mb-8 p-5
                        bg-yellow-300
                        border-4 border-[#121212]
                        shadow-[6px_6px_0_0_#121212]"
                 role="alert">

                <div class="flex items-start gap-4">

                    <div class="flex-shrink-0">

                        <svg class="w-6 h-6 text-[#121212]"
                            viewBox="0 0 20 20"
                            fill="currentColor">

                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0
                                   8 8 0 0116 0zm-7-4a1 1 0 11-2 0
                                   1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1
                                   0 001 1h1a1 1 0 100-2v-3a1 1 0
                                   00-1-1H9z"
                                clip-rule="evenodd" />

                        </svg>

                    </div>


                    <div>

                        <p class="font-black uppercase text-sm">
                            Terjadi Kesalahan
                        </p>

                        <p class="mt-1 font-bold text-sm">
                            <?= htmlspecialchars($error) ?>
                        </p>

                    </div>

                </div>

            </div>

        <?php } ?>


        <!-- Filter Pelacakan -->
        <div class="mb-10">

            <div class="bg-white dark:bg-[#181818]
                        border-4 border-[#121212]
                        dark:border-white
                        shadow-[6px_6px_0_0_#121212]
                        dark:shadow-[6px_6px_0_0_#00d982]">

                <div class="p-6 sm:p-7">

                    <!-- Section Header -->
                    <div class="mb-6">

                        <div class="flex items-center gap-2 mb-2">

                            <span class="w-3 h-3
                                         bg-[#00d982]
                                         border-2 border-black">
                            </span>

                            <span class="text-xs font-black
                                         uppercase tracking-[0.2em]
                                         text-gray-600
                                         dark:text-gray-400">

                                Tracking

                            </span>

                        </div>


                        <h2 class="font-black text-xl sm:text-2xl
                                   uppercase tracking-tight
                                   text-[#121212]
                                   dark:text-white">

                            Pelacakan Penggunaan Opsi

                        </h2>


                        <p class="mt-1 text-sm font-medium
                                  text-gray-500
                                  dark:text-gray-400">

                            Pilih kategori, opsi, dan periode untuk
                            mengetahui jumlah penggunaannya.

                        </p>

                    </div>


                    <!-- Form -->
                    <form action="/report/tracking" method="POST">

                        <div class="grid grid-cols-1
                                    md:grid-cols-2
                                    xl:grid-cols-4
                                    gap-4">


                            <!-- Kategori -->
                            <div>

                                <label
                                    for="category"
                                    class="block mb-2
                                           text-xs font-black
                                           uppercase tracking-wider
                                           text-[#121212]
                                           dark:text-white">

                                    Kategori

                                </label>


                                <select
                                    id="category"
                                    name="category"
                                    required
                                    class="w-full px-4 py-3
                                           bg-white dark:bg-[#222]
                                           text-[#121212]
                                           dark:text-white
                                           border-4 border-[#121212]
                                           dark:border-white
                                           font-bold
                                           focus:outline-none
                                           focus:ring-4
                                           focus:ring-[#00d982]">

                                    <option value="">
                                        -- Pilih Kategori --
                                    </option>

                                    <?php foreach ($categories as $value => $label) { ?>

                                        <option
                                            value="<?= htmlspecialchars($value) ?>"
                                            <?= ($selectedCategory ?? '') === $value
                                                ? 'selected'
                                                : '' ?>>

                                            <?= htmlspecialchars($label) ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>


                            <!-- Opsi -->
                            <div>

                                <label
                                    for="option_id"
                                    class="block mb-2
                                           text-xs font-black
                                           uppercase tracking-wider
                                           text-[#121212]
                                           dark:text-white">

                                    Opsi

                                </label>


                                <select
                                    id="option_id"
                                    name="option_id"
                                    required
                                    class="w-full px-4 py-3
                                           bg-white dark:bg-[#222]
                                           text-[#121212]
                                           dark:text-white
                                           border-4 border-[#121212]
                                           dark:border-white
                                           font-bold
                                           focus:outline-none
                                           focus:ring-4
                                           focus:ring-[#00d982]">

                                    <option value="">
                                        -- Pilih Opsi --
                                    </option>

                                    <?php foreach ($options as $option) { ?>

                                        <option
                                            value="<?= $option->id ?>"
                                            <?= isset($_POST['option_id']) &&
                                                (int) $_POST['option_id'] === $option->id
                                                ? 'selected'
                                                : '' ?>>

                                            <?= htmlspecialchars($option->name) ?>

                                        </option>

                                    <?php } ?>

                                </select>

                            </div>


                            <!-- Tanggal Mulai -->
                            <div>

                                <label
                                    for="start_date"
                                    class="block mb-2
                                           text-xs font-black
                                           uppercase tracking-wider
                                           text-[#121212]
                                           dark:text-white">

                                    Tanggal Mulai

                                </label>


                                <input
                                    type="date"
                                    id="start_date"
                                    name="start_date"
                                    required
                                    value="<?= htmlspecialchars(
                                        $_POST['start_date'] ?? ''
                                    ) ?>"
                                    class="w-full px-4 py-3
                                           bg-white dark:bg-[#222]
                                           text-[#121212]
                                           dark:text-white
                                           border-4 border-[#121212]
                                           dark:border-white
                                           font-bold
                                           focus:outline-none
                                           focus:ring-4
                                           focus:ring-[#00d982]">

                            </div>


                            <!-- Tanggal Akhir -->
                            <div>

                                <label
                                    for="end_date"
                                    class="block mb-2
                                           text-xs font-black
                                           uppercase tracking-wider
                                           text-[#121212]
                                           dark:text-white">

                                    Tanggal Akhir

                                </label>


                                <input
                                    type="date"
                                    id="end_date"
                                    name="end_date"
                                    required
                                    value="<?= htmlspecialchars(
                                        $_POST['end_date'] ?? ''
                                    ) ?>"
                                    class="w-full px-4 py-3
                                           bg-white dark:bg-[#222]
                                           text-[#121212]
                                           dark:text-white
                                           border-4 border-[#121212]
                                           dark:border-white
                                           font-bold
                                           focus:outline-none
                                           focus:ring-4
                                           focus:ring-[#00d982]">

                            </div>

                        </div>


                        <!-- Tombol -->
                        <div class="mt-6 flex flex-wrap items-center gap-4">

                            <!-- Lacak -->
                            <button
                                type="submit"
                                class="inline-flex items-center
                                       justify-center gap-2
                                       px-5 py-3
                                       bg-[#00d982]
                                       text-[#121212]
                                       border-4 border-[#121212]
                                       font-black uppercase text-sm
                                       shadow-[5px_5px_0_0_#121212]
                                       hover:shadow-none
                                       hover:translate-x-[5px]
                                       hover:translate-y-[5px]
                                       transition-all duration-150">

                                <svg class="w-5 h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="3"
                                        d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z">
                                    </path>

                                </svg>

                                Lacak Laporan

                            </button>


                            <!-- Reset -->
                            <a
                                href="/report/tracking"
                                class="inline-flex items-center
                                       justify-center gap-2
                                       px-5 py-3
                                       bg-white dark:bg-[#181818]
                                       text-[#121212]
                                       dark:text-white
                                       border-4 border-[#121212]
                                       dark:border-white
                                       font-black uppercase text-sm
                                       shadow-[5px_5px_0_0_#121212]
                                       dark:shadow-[5px_5px_0_0_#00d982]
                                       hover:shadow-none
                                       hover:translate-x-[5px]
                                       hover:translate-y-[5px]
                                       transition-all duration-150">

                                <svg class="w-5 h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">

                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="3"
                                        d="M6 18L18 6M6 6l12 12">
                                    </path>

                                </svg>

                                Reset

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>


        <!-- Hasil Pelacakan -->
        <?php if ($result !== null) { ?>

            <div class="mb-10">

                <div class="bg-white dark:bg-[#181818]
                            border-4 border-[#121212]
                            dark:border-white
                            shadow-[6px_6px_0_0_#121212]
                            dark:shadow-[6px_6px_0_0_#00d982]">

                    <div class="p-6 sm:p-7">

                        <!-- Header Hasil -->
                        <div class="flex items-center gap-2 mb-6">

                            <span class="w-3 h-3
                                         bg-[#00d982]
                                         border-2 border-black">
                            </span>

                            <span class="text-xs font-black
                                         uppercase tracking-[0.2em]
                                         text-gray-600
                                         dark:text-gray-400">

                                Hasil Pelacakan

                            </span>

                        </div>


                        <div class="grid grid-cols-1
                                    lg:grid-cols-[1fr_auto]
                                    gap-8
                                    items-center">


                            <!-- Informasi -->
                            <div>

                                <p class="text-xs font-black
                                          uppercase tracking-wider
                                          text-gray-500
                                          dark:text-gray-400">

                                    Opsi yang dilacak

                                </p>


                                <h2 class="mt-2
                                           text-2xl sm:text-3xl
                                           font-black uppercase
                                           text-[#121212]
                                           dark:text-white">

                                    <?= htmlspecialchars(
                                        $result->optionName
                                    ) ?>

                                </h2>


                                <div class="mt-5 flex flex-wrap gap-3">

                                    <span
                                        class="inline-flex items-center
                                               px-3 py-2
                                               bg-gray-100
                                               dark:bg-[#222]
                                               border-2
                                               border-[#121212]
                                               dark:border-gray-600
                                               text-xs font-black
                                               uppercase
                                               text-[#121212]
                                               dark:text-white">

                                        <?= htmlspecialchars(
                                            $result->category
                                        ) ?>

                                    </span>


                                    <span
                                        class="inline-flex items-center
                                               px-3 py-2
                                               bg-gray-100
                                               dark:bg-[#222]
                                               border-2
                                               border-[#121212]
                                               dark:border-gray-600
                                               text-xs font-black
                                               text-[#121212]
                                               dark:text-white">

                                        <?= htmlspecialchars(
                                            $result->startDate
                                        ) ?>

                                        <span class="mx-2">
                                            →
                                        </span>

                                        <?= htmlspecialchars(
                                            $result->endDate
                                        ) ?>

                                    </span>

                                </div>

                            </div>


                            <!-- Total -->
                            <div
                                class="min-w-[180px]
                                       bg-[#00d982]
                                       border-4 border-[#121212]
                                       shadow-[5px_5px_0_0_#121212]
                                       p-5
                                       text-center">

                                <p class="text-xs font-black
                                          uppercase tracking-wider">

                                    Total Digunakan

                                </p>


                                <div
                                    class="mt-2
                                           text-6xl
                                           sm:text-7xl
                                           font-black
                                           leading-none
                                           text-[#121212]">

                                    <?= (int) $result->total ?>

                                </div>


                                <p class="mt-2
                                          text-sm font-black
                                          uppercase
                                          text-[#121212]">

                                    Kali

                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        <?php } ?>


    </div>

</div>


<script>

    const categorySelect =
        document.getElementById('category');

    categorySelect.addEventListener('change', function () {

        const category = this.value;

        if (!category) {

            window.location.href =
                '/report/tracking';

            return;
        }

        window.location.href =
            '/report/tracking?category=' +
            encodeURIComponent(category);

    });

</script>