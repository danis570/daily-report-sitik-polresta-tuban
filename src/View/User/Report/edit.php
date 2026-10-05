<div class="min-h-screen bg-gray-50 dark:bg-[#121212] pt-12 pb-16 px-4 sm:px-6 lg:px-8">

    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="mb-6">

            <?php
            $bulanIndo = [
                1 => 'Januari',
                2 => 'Februari',
                3 => 'Maret',
                4 => 'April',
                5 => 'Mei',
                6 => 'Juni',
                7 => 'Juli',
                8 => 'Agustus',
                9 => 'September',
                10 => 'Oktober',
                11 => 'November',
                12 => 'Desember',
            ];
            $hariIndo = [
                'Sunday' => 'Minggu',
                'Monday' => 'Senin',
                'Tuesday' => 'Selasa',
                'Wednesday' => 'Rabu',
                'Thursday' => 'Kamis',
                'Friday' => 'Jumat',
                'Saturday' => 'Sabtu',
            ];

            $tanggalIndo = $hariIndo[$report->reportDate->format('l')] . ', '
                . $report->reportDate->format('d') . ' '
                . $bulanIndo[(int) $report->reportDate->format('n')] . ' '
                . $report->reportDate->format('Y');
            ?>

            <!-- Breadcrumb -->
            <nav aria-label="Breadcrumb" class="mb-6 pl-2">
                <ol class="flex flex-wrap items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                    <li>
                        <a href="/reports" class="hover:text-[#00d982] transition-colors">Laporan</a>
                    </li>
                    <li aria-hidden="true" class="text-gray-400 dark:text-gray-600 flex items-center">
                        <i data-lucide="chevron-right" class="w-4 h-4"></i>
                    </li>
                    <li>
                        <span aria-current="page" class="text-black dark:text-white">
                            <?= htmlspecialchars($tanggalIndo) ?> · Edit
                        </span>
                    </li>
                </ol>
            </nav>

            <h1 class="font-black text-4xl sm:text-5xl uppercase
                       tracking-tight leading-none
                       text-[#121212] dark:text-white">
                Edit Report
            </h1>


            <p class="mt-3 text-sm sm:text-base font-medium
                      text-gray-600 dark:text-gray-400">
                Ubah tanggal laporan harian SITIK Polresta Tuban.
            </p>

        </div>


        <!-- Card -->
        <div class="bg-white dark:bg-[#181818]
                    border-4 border-[#121212] dark:border-white
                    shadow-[7px_7px_0_0_#121212]
                    dark:shadow-[7px_7px_0_0_#00d982]">


            <!-- Card Header -->
            <div class="p-6 sm:p-8
                        bg-[#00d982]
                        border-b-4 border-[#121212]">

                <div class="flex items-center gap-4">

                    <!-- Icon -->
                    <div class="w-12 h-12
                                flex items-center justify-center
                                bg-[#121212]
                                border-4 border-[#121212]
                                text-[#00d982]">

                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.586-9.414a2 2 0 112.828 2.828L11 15l-4 1 1-4 7.414-7.414z">
                            </path>

                        </svg>

                    </div>


                    <!-- Title -->
                    <div>

                        <h2 class="font-black text-xl uppercase text-[#121212]">
                            <?= htmlspecialchars($title ?? 'Ubah Tanggal Laporan') ?>
                        </h2>

                        <p class="mt-1 text-sm font-bold text-[#121212]/70">
                            SITIK Polresta Tuban — Laporan ID #<?= $report->id ?>
                        </p>

                    </div>

                </div>

            </div>


            <!-- Card Body -->
            <div class="p-6 sm:p-8">

                <!-- Success -->
                <?php if (!empty($success)) { ?>

                    <div class="mb-7 p-5
                        bg-green-100 dark:bg-green-900/30
                        border-4 border-[#121212] dark:border-white
                        shadow-[5px_5px_0_0_#121212] dark:shadow-[5px_5px_0_0_#00d982]" role="alert">

                        <div class="flex items-start gap-4">

                            <div class="flex-shrink-0
                        w-9 h-9
                        flex items-center justify-center
                        bg-[#00d982]
                        border-2 border-[#121212]">

                                <svg class="w-5 h-5 text-[#121212]" viewBox="0 0 20 20" fill="currentColor">

                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />

                                </svg>

                            </div>

                            <div>
                                <p class="font-black uppercase text-sm text-[#121212] dark:text-white">
                                    Berhasil
                                </p>

                                <p class="mt-1 text-sm font-bold text-[#121212] dark:text-white">
                                    <?= htmlspecialchars($success) ?>
                                </p>
                            </div>

                        </div>

                    </div>

                <?php } ?>

                <!-- Error -->
                <?php if (!empty($error)) { ?>

                    <div class="mb-7 p-5
                                bg-red-100
                                border-4 border-[#121212]
                                shadow-[5px_5px_0_0_#121212]" role="alert">

                        <div class="flex items-start gap-4">

                            <div class="flex-shrink-0
                                        w-9 h-9
                                        flex items-center justify-center
                                        bg-red-500
                                        border-2 border-[#121212]">

                                <svg class="w-5 h-5 text-white" viewBox="0 0 20 20" fill="currentColor">

                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />

                                </svg>

                            </div>

                            <div>
                                <p class="font-black uppercase text-sm text-[#121212]">
                                    Gagal memperbarui
                                </p>

                                <p class="mt-1 text-sm font-bold text-[#121212]">
                                    <?= htmlspecialchars($error) ?>
                                </p>
                            </div>

                        </div>

                    </div>

                <?php } ?>


                <!-- Form -->
                <form action="/report/edit/<?= $report->id ?>" method="POST" class="space-y-7">

                    <!-- Tanggal Laporan (Pelaksanaan) -->
                    <div>
                        <label for="report_date" class="block mb-2
                   text-sm font-black uppercase tracking-wide
                   text-[#121212] dark:text-white">
                            Tanggal Laporan
                        </label>

                        <div class="relative">
                            <input type="date" id="report_date" name="report_date" value="<?= htmlspecialchars(
                                $_POST['report_date']
                                ?? $report->reportDate->format('Y-m-d')
                            ) ?>" required class="block w-full px-4 py-3
                       bg-white dark:bg-[#121212]
                       text-[#121212] dark:text-white
                       border-4 border-[#121212] dark:border-white
                       font-bold outline-none
                       focus:ring-0
                       focus:border-[#00d982]
                       transition-colors">
                        </div>

                        <div class="mt-3 flex items-start gap-2">
                            <span class="mt-1 w-2 h-2 flex-shrink-0 bg-[#00d982] border border-[#121212]"></span>
                            <p class="text-xs font-bold text-gray-500 dark:text-gray-400">
                                Sistem akan memvalidasi agar tanggal laporan tidak ganda dengan laporan lainnya.
                            </p>
                        </div>
                    </div>


                    <!-- Tanggal Pembuatan (Opsional) — BARU -->
                    <div>
                        <label for="created_at" class="block mb-2
                   text-sm font-black uppercase tracking-wide
                   text-[#121212] dark:text-white">
                            Tanggal Pembuatan
                            <span class="text-gray-400 dark:text-gray-500">(Opsional)</span>
                        </label>

                        <div class="relative">
                            <input type="date" id="created_at" name="created_at" value="<?= htmlspecialchars(
                                $_POST['created_at']
                                ?? $report->createdAt->format('Y-m-d')
                            ) ?>" class="block w-full px-4 py-3
                       bg-white dark:bg-[#121212]
                       text-[#121212] dark:text-white
                       border-4 border-[#121212] dark:border-white
                       font-bold outline-none
                       focus:ring-0
                       focus:border-[#00d982]
                       transition-colors">
                        </div>

                        <div class="mt-3 flex items-start gap-2">
                            <span class="mt-1 w-2 h-2 flex-shrink-0 bg-[#00d982] border border-[#121212]"></span>
                            <p class="text-xs font-bold text-gray-500 dark:text-gray-400">
                                Kosongkan untuk memakai tanggal pembuatan asli.
                            </p>
                        </div>
                    </div>


                    <!-- Divider -->
                    <div class="border-t-4 border-dashed border-gray-300 dark:border-gray-700"></div>

                    <!-- Actions — tidak berubah -->
                    <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-4">
                        <!-- Kembali -->
                        <a href="/reports" class="inline-flex items-center justify-center gap-2
                                   px-5 py-3
                                   bg-white dark:bg-[#181818]
                                   text-[#121212] dark:text-white
                                   border-4 border-[#121212] dark:border-white
                                   font-black uppercase text-sm
                                   shadow-[5px_5px_0_0_#121212]
                                   dark:shadow-[5px_5px_0_0_#00d982]
                                   hover:shadow-none
                                   hover:translate-x-[5px]
                                   hover:translate-y-[5px]
                                   transition-all duration-150">


                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M15 19l-7-7 7-7">
                                </path>

                            </svg>


                            Kembali

                        </a>
                        <!-- Simpan -->
                        <button type="submit" class="inline-flex items-center justify-center gap-2
                                   px-6 py-3
                                   bg-[#00d982]
                                   text-[#121212]
                                   border-4 border-[#121212]
                                   font-black uppercase text-sm
                                   shadow-[5px_5px_0_0_#121212]
                                   hover:shadow-none
                                   hover:translate-x-[5px]
                                   hover:translate-y-[5px]
                                   transition-all duration-150">


                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7">
                                </path>

                            </svg>


                            Simpan Perubahan

                        </button>
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>