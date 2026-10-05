<div class="min-h-screen bg-gray-50 dark:bg-[#121212] pt-12 pb-16 px-4 sm:px-6 lg:px-8">

    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="mb-6">

            <h1 class="font-black text-4xl sm:text-5xl uppercase
                       tracking-tight leading-none
                       text-[#121212] dark:text-white">
                Tambah Report
            </h1>

            <p class="mt-3 text-sm sm:text-base font-medium
                      text-gray-600 dark:text-gray-400">
                Buat laporan harian baru untuk SITIK Polresta Tuban.
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

                    <div class="w-12 h-12
                                flex items-center justify-center
                                bg-[#121212]
                                border-4 border-[#121212]
                                text-[#00d982]">

                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>

                        </svg>

                    </div>

                    <div>
                        <h2 class="font-black text-xl uppercase text-[#121212]">
                            <?= htmlspecialchars($title ?? 'Buat Laporan Baru') ?>
                        </h2>

                        <p class="mt-1 text-sm font-bold text-[#121212]/70">
                            SITIK Polresta Tuban — Laporan Harian
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
                                    Gagal menyimpan
                                </p>

                                <p class="mt-1 text-sm font-bold text-[#121212]">
                                    <?= htmlspecialchars($error) ?>
                                </p>
                            </div>

                        </div>

                    </div>

                <?php } ?>


                <!-- Form -->
                <form action="/report/add" method="POST" class="space-y-7">


                    <!-- Tanggal Laporan (Pelaksanaan) -->
                    <div>
                        <label for="report_date" class="block mb-2
               text-sm font-black uppercase tracking-wide
               text-[#121212] dark:text-white">
                            Tanggal Laporan (Pelaksanaan)
                        </label>

                        <input type="date" id="report_date" name="report_date"
                            value="<?= htmlspecialchars($_POST['report_date'] ?? date('Y-m-d')) ?>" required class="block w-full px-4 py-3
               bg-white dark:bg-[#121212]
               text-[#121212] dark:text-white
               border-4 border-[#121212] dark:border-white
               font-bold outline-none
               focus:border-[#00d982] transition-colors">

                        <div class="mt-3 flex items-start gap-2">
                            <span class="mt-1 w-2 h-2 flex-shrink-0 bg-[#00d982] border border-[#121212]"></span>
                            <p class="text-xs font-bold text-gray-500 dark:text-gray-400">
                                Tanggal pelaksanaan kegiatan.
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

                        <input type="date" id="created_at" name="created_at"
                            value="<?= htmlspecialchars($_POST['created_at'] ?? '') ?>" class="block w-full px-4 py-3
           bg-white dark:bg-[#121212]
           text-[#121212] dark:text-white
           border-4 border-[#121212] dark:border-white
           font-bold outline-none
           focus:border-[#00d982] transition-colors">

                        <div class="mt-3 flex items-start gap-2">
                            <span class="mt-1 w-2 h-2 flex-shrink-0 bg-[#00d982] border border-[#121212]"></span>
                            <p class="text-xs font-bold text-gray-500 dark:text-gray-400">
                                Kosongkan untuk otomatis pakai tanggal & jam saat ini.
                            </p>
                        </div>
                    </div>


                    <!-- Divider -->
                    <div class="border-t-4 border-dashed
                                border-gray-300 dark:border-gray-700">
                    </div>


                    <!-- Actions -->
                    <div class="flex flex-col-reverse sm:flex-row
                                sm:items-center sm:justify-between
                                gap-4">

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

                            Simpan Laporan

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>