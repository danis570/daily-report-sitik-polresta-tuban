<div class="min-h-screen bg-gray-50 dark:bg-[#121212] pt-12 pb-16 px-4 sm:px-6 lg:px-8">

    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="font-black text-4xl sm:text-5xl uppercase
                       tracking-tight leading-none
                       text-[#121212] dark:text-white">
                <?= htmlspecialchars($title ?? 'Tambah Pilihan Laporan') ?>
            </h1>

            <p class="mt-3 text-sm sm:text-base font-medium
                      text-gray-600 dark:text-gray-400">
                SITIK Polresta Tuban — Tambah kategori atau opsi dinamis.
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
                                d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                        </svg>

                    </div>

                    <div>
                        <h2 class="font-black text-xl uppercase text-[#121212]">
                            <?= htmlspecialchars($title ?? 'Tambah Pilihan Laporan') ?>
                        </h2>

                        <p class="mt-1 text-sm font-bold text-[#121212]/70">
                            SITIK Polresta Tuban — Opsi Laporan
                        </p>
                    </div>

                </div>

            </div>


            <!-- Card Body -->
            <div class="p-6 sm:p-8">

                <!-- Error -->
                <?php if (!empty($error)): ?>
                    <div id="error-message" class="mb-7 p-5
                               bg-red-100 dark:bg-red-900/30
                               border-4 border-[#121212] dark:border-white
                               shadow-[5px_5px_0_0_#121212] dark:shadow-[5px_5px_0_0_#00d982]
                               flex items-start gap-4
                               transition-all duration-200" role="alert">

                        <div class="flex-shrink-0 w-9 h-9
                                    flex items-center justify-center
                                    bg-red-500
                                    border-2 border-[#121212]">

                            <svg class="w-5 h-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>

                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="font-black uppercase text-sm text-[#121212] dark:text-white">
                                Gagal Menyimpan
                            </p>
                            <p class="mt-1 text-sm font-bold text-[#121212] dark:text-white break-words">
                                <?= htmlspecialchars($error) ?>
                            </p>
                        </div>

                        <button type="button" id="error-close" class="shrink-0 w-8 h-8 flex items-center justify-center
                                   bg-[#121212] text-white border-2 border-[#121212]
                                   hover:bg-white hover:text-[#121212]
                                   transition-colors cursor-pointer" aria-label="Tutup notifikasi">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 6l12 12M6 18L18 6" />
                            </svg>
                        </button>

                    </div>
                <?php endif; ?>


                <!-- Form -->
                <form action="/report/option/add" method="POST" class="space-y-7">

                    <!-- Kategori -->
                    <div>
                        <label for="category" class="block mb-2
                                   text-sm font-black uppercase tracking-wide
                                   text-[#121212] dark:text-white">
                            Kategori
                        </label>

                        <select id="category" name="category" required class="block w-full
                                   px-4 py-3
                                   bg-white dark:bg-[#121212]
                                   text-[#121212] dark:text-white
                                   border-4 border-[#121212] dark:border-white
                                   font-bold
                                   outline-none
                                   focus:ring-0
                                   focus:border-[#00d982]
                                   transition-colors">

                            <option value="">-- Pilih Kategori --</option>
                            <option value="target" <?= ($oldInput['category'] ?? '') === 'target' ? 'selected' : '' ?>>
                                SASARAN
                            </option>
                            <option value="activity" <?= ($oldInput['category'] ?? '') === 'activity' ? 'selected' : '' ?>>
                                KEGIATAN
                            </option>
                            <option value="personnel_strength" <?= ($oldInput['category'] ?? '') === 'personnel_strength' ? 'selected' : '' ?>>
                                KUAT PERSONIL
                            </option>
                            <option value="location" <?= ($oldInput['category'] ?? '') === 'location' ? 'selected' : '' ?>>
                                LOKASI
                            </option>
                            <option value="person_in_charge" <?= ($oldInput['category'] ?? '') === 'person_in_charge' ? 'selected' : '' ?>>
                                PENANGGUNG JAWAB
                            </option>
                            <option value="expected_result" <?= ($oldInput['category'] ?? '') === 'expected_result' ? 'selected' : '' ?>>
                                HASIL YANG INGIN DI CAPAI
                            </option>

                        </select>

                        <div class="mt-3 flex items-start gap-2">
                            <span class="mt-1 w-2 h-2 flex-shrink-0
                                         bg-[#00d982]
                                         border border-[#121212]"></span>

                            <p class="text-xs font-bold text-gray-500 dark:text-gray-400">
                                Pilih kategori yang tersedia pada sistem.
                            </p>
                        </div>
                    </div>


                    <!-- Nama Opsi -->
                    <div>
                        <label for="name" class="block mb-2
                                   text-sm font-black uppercase tracking-wide
                                   text-[#121212] dark:text-white">
                            Nama Pilihan
                        </label>

                        <input type="text" id="name" name="name" placeholder="Contoh: Patroli Sinergitas, Penjagaan"
                            value="<?= htmlspecialchars($oldInput['name'] ?? '') ?>" required class="block w-full
                                   px-4 py-3
                                   bg-white dark:bg-[#121212]
                                   text-[#121212] dark:text-white
                                   border-4 border-[#121212] dark:border-white
                                   font-bold
                                   outline-none
                                   placeholder:text-gray-400 dark:placeholder:text-gray-600
                                   focus:ring-0
                                   focus:border-[#00d982]
                                   transition-colors">

                        <div class="mt-3 flex items-start gap-2">
                            <span class="mt-1 w-2 h-2 flex-shrink-0
                                         bg-[#00d982]
                                         border border-[#121212]"></span>

                            <p class="text-xs font-bold text-gray-500 dark:text-gray-400">
                                Nama pilihan akan muncul di dropdown saat membuat laporan.
                            </p>
                        </div>
                    </div>


                    <!-- Deskripsi -->
                    <div>
                        <label for="description" class="block mb-2
                                   text-sm font-black uppercase tracking-wide
                                   text-[#121212] dark:text-white">
                            Deskripsi
                            <span class="text-gray-400 dark:text-gray-500">(Opsional)</span>
                        </label>

                        <textarea id="description" name="description" rows="4"
                            placeholder="Keterangan opsional mengenai pilihan ini..."
                            class="block w-full
                                   px-4 py-3
                                   bg-white dark:bg-[#121212]
                                   text-[#121212] dark:text-white
                                   border-4 border-[#121212] dark:border-white
                                   font-bold
                                   outline-none
                                   resize-y
                                   placeholder:text-gray-400 dark:placeholder:text-gray-600
                                   focus:ring-0
                                   focus:border-[#00d982]
                                   transition-colors"><?= htmlspecialchars($oldInput['description'] ?? '') ?></textarea>
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
                        <a href="/report/options" class="inline-flex items-center justify-center gap-2
                                   px-5 py-3
                                   bg-white dark:bg-[#181818]
                                   text-[#121212] dark:text-white
                                   border-4 border-[#121212] dark:border-white
                                   font-black uppercase text-sm
                                   shadow-[5px_5px_0_0_#121212] dark:shadow-[5px_5px_0_0_#00d982]
                                   hover:shadow-none
                                   hover:translate-x-[5px] hover:translate-y-[5px]
                                   transition-all duration-150">

                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M15 19l-7-7 7-7" />
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
                                   hover:translate-x-[5px] hover:translate-y-[5px]
                                   transition-all duration-150 cursor-pointer">

                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                    d="M5 13l4 4L19 7" />
                            </svg>

                            Simpan Opsi
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        /* ==========================================================
         * TOMBOL CLOSE — ERROR MESSAGE
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

    });
</script>