<div class="min-h-screen bg-gray-50 dark:bg-[#121212] pt-12 pb-16 px-4 sm:px-6 lg:px-8">

    <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="mb-6">
            <h1 class="font-black text-4xl sm:text-5xl uppercase
                       tracking-tight leading-none
                       text-[#121212] dark:text-white">
                Generate Laporan
            </h1>
            <p class="mt-3 text-sm sm:text-base font-medium
                      text-gray-600 dark:text-gray-400">
                Buat laporan harian otomatis untuk periode tertentu. Hanya hari kerja (Senin–Jumat) yang akan
                di-generate.
            </p>
        </div>

        <!-- Card -->
        <div class="bg-white dark:bg-[#181818]
                    border-4 border-[#121212] dark:border-white
                    shadow-[7px_7px_0_0_#121212]
                    dark:shadow-[7px_7px_0_0_#00d982]">

            <!-- Card Header -->
            <div class="p-6 sm:p-8 bg-[#00d982] border-b-4 border-[#121212]">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 flex items-center justify-center
                                bg-[#121212] border-4 border-[#121212] text-[#00d982]">
                        <i data-lucide="sparkles" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="font-black text-xl uppercase text-[#121212]">
                            Generate Otomatis
                        </h2>
                        <p class="mt-1 text-sm font-bold text-[#121212]/70">
                            Rentang maksimal 31 hari per generate
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="p-6 sm:p-8">

                <!-- Error -->
                <?php if (!empty($error)) { ?>
                    <div class="mb-7 p-5 bg-red-100
                                border-4 border-[#121212]
                                shadow-[5px_5px_0_0_#121212]" role="alert">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-9 h-9
                                        flex items-center justify-center
                                        bg-red-500 border-2 border-[#121212]">
                                <svg class="w-5 h-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <p class="font-black uppercase text-sm text-[#121212]">
                                    Gagal generate
                                </p>
                                <p class="mt-1 text-sm font-bold text-[#121212]">
                                    <?= htmlspecialchars($error) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <!-- Info Box -->
                <div class="mb-7 p-5 bg-yellow-300 border-4 border-[#121212]
            shadow-[5px_5px_0_0_#121212]">
                    <div class="flex items-start gap-3">
                        <i data-lucide="info" class="w-5 h-5 shrink-0 mt-0.5 text-[#121212]"></i>
                        <div class="text-sm font-bold text-[#121212] space-y-1">
                            <p>• Hanya hari <strong>Senin–Jumat</strong> yang di-generate.</p>
                            <p>• <strong>Sabtu &amp; Minggu</strong> otomatis di-skip.</p>
                            <p>• <strong>Tanggal merah nasional</strong> (1 Jan, 17 Agt, 25 Des) otomatis di-skip.</p>
                            <p>• Tanggal merah lain (cuti bersama / libur lokal) isi manual di bawah.</p>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <form action="/report/generate" method="POST" class="space-y-7">

                    <!-- Rentang Tanggal -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                        <div>
                            <label for="start_date" class="block mb-2
                                       text-sm font-black uppercase tracking-wide
                                       text-[#121212] dark:text-white">
                                Tanggal Mulai
                            </label>
                            <input type="date" id="start_date" name="start_date"
                                value="<?= htmlspecialchars($oldInput['start_date'] ?? '') ?>" required class="block w-full px-4 py-3
                                       bg-white dark:bg-[#121212]
                                       text-[#121212] dark:text-white
                                       border-4 border-[#121212] dark:border-white
                                       font-bold outline-none
                                       focus:border-[#00d982] transition-colors">
                        </div>

                        <div>
                            <label for="end_date" class="block mb-2
                                       text-sm font-black uppercase tracking-wide
                                       text-[#121212] dark:text-white">
                                Tanggal Akhir
                            </label>
                            <input type="date" id="end_date" name="end_date"
                                value="<?= htmlspecialchars($oldInput['end_date'] ?? '') ?>" required class="block w-full px-4 py-3
                                       bg-white dark:bg-[#121212]
                                       text-[#121212] dark:text-white
                                       border-4 border-[#121212] dark:border-white
                                       font-bold outline-none
                                       focus:border-[#00d982] transition-colors">
                        </div>

                    </div>

                    <!-- Hint rentang -->
                    <p id="range-hint" class="text-xs font-bold text-gray-500 dark:text-gray-400">
                        Maksimal <span class="font-black text-[#121212] dark:text-white">31 hari</span> per generate.
                    </p>

                    <!-- Tanggal Merah Manual -->
                    <div>
                        <label for="manual_holidays" class="block mb-2
                                   text-sm font-black uppercase tracking-wide
                                   text-[#121212] dark:text-white">
                            Tanggal Merah Manual
                            <span class="text-gray-400 dark:text-gray-500">(Opsional)</span>
                        </label>
                        <textarea id="manual_holidays" name="manual_holidays" rows="3"
                            placeholder="Contoh: 2026-10-05, 2026-10-12"
                            class="block w-full px-4 py-3
                                   bg-white dark:bg-[#121212]
                                   text-[#121212] dark:text-white
                                   border-4 border-[#121212] dark:border-white
                                   font-bold outline-none
                                   focus:border-[#00d982] transition-colors"><?= htmlspecialchars($oldInput['manual_holidays'] ?? '') ?></textarea>
                        <div class="mt-3 flex items-start gap-2">
                            <span class="mt-1 w-2 h-2 flex-shrink-0 bg-[#00d982] border border-[#121212]"></span>
                            <p class="text-xs font-bold text-gray-500 dark:text-gray-400">
                                Pisahkan dengan koma. Format: <code class="font-mono">YYYY-MM-DD</code>.
                                Kosongkan jika tidak ada.
                            </p>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="border-t-4 border-dashed border-gray-300 dark:border-gray-700"></div>

                    <!-- Actions -->
                    <div class="flex flex-col-reverse sm:flex-row sm:items-center sm:justify-between gap-4">

                        <a href="/reports" class="inline-flex items-center justify-center gap-2 px-5 py-3
                                   bg-white dark:bg-[#181818]
                                   text-[#121212] dark:text-white
                                   border-4 border-[#121212] dark:border-white
                                   font-black uppercase text-sm
                                   shadow-[5px_5px_0_0_#121212]
                                   dark:shadow-[5px_5px_0_0_#00d982]
                                   hover:shadow-none hover:translate-x-[5px] hover:translate-y-[5px]
                                   transition-all duration-150">
                            <i data-lucide="arrow-left" class="w-5 h-5"></i>
                            Kembali
                        </a>

                        <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-3
                                   bg-[#00d982] text-[#121212]
                                   border-4 border-[#121212]
                                   font-black uppercase text-sm
                                   shadow-[5px_5px_0_0_#121212]
                                   hover:shadow-none hover:translate-x-[5px] hover:translate-y-[5px]
                                   transition-all duration-150 cursor-pointer">
                            <i data-lucide="sparkles" class="w-5 h-5"></i>
                            Generate Sekarang
                        </button>

                    </div>

                </form>

                <!-- ⚠️ Peringatan Template (BARU) -->
                <div class="mb-7 mt-8 p-5 bg-red-500 text-white border-4 border-[#121212]
            shadow-[5px_5px_0_0_#121212]">
                    <div class="flex items-start gap-3">
                        <div class="shrink-0 w-9 h-9
                    flex items-center justify-center
                    bg-white border-2 border-[#121212]">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-red-500"></i>
                        </div>
                        <div class="text-sm font-bold space-y-2">

                            <p class="font-black uppercase text-base">
                                Peringatan Penting — Jangan Hapus Template
                            </p>

                            <p>
                                Laporan berikut adalah <strong>acuan (template)</strong> yang dipakai untuk generate
                                otomatis.
                                <strong>Jangan menghapus</strong> laporan ini, karena akan menyebabkan proses generate
                                gagal.
                            </p>

                            <ul class="mt-2 space-y-1 pl-1">
                                <?php foreach (($templateList ?? []) as $tpl): ?>
                                    <li class="flex items-start gap-2">
                                        <span
                                            class="inline-block w-2 h-2 mt-1.5 bg-white border border-[#121212] shrink-0"></span>
                                        <span>
                                            <strong><?= htmlspecialchars($tpl['day_name']) ?></strong>
                                            <span
                                                class="text-white/80">(<?= htmlspecialchars($tpl['report_date']) ?>)</span>
                                            → Report ID
                                            <span
                                                class="inline-block px-2 py-0.5 bg-[#121212] text-white font-mono text-xs">
                                                #<?= (int) $tpl['template_id'] ?>
                                            </span>
                                        </span>
                                    </li>
                                <?php endforeach; ?>
                            </ul>

                            <p class="mt-3">
                                Jika ada penyesuaian pola kegiatan harian, silakan
                                <strong>edit kegiatan langsung pada laporan acuan di atas</strong>.
                                Karena laporan tersebut yang akan dijadikan contoh untuk semua generate berikutnya.
                            </p>

                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

<script>
    // ==============================
    // Validasi rentang tanggal (client-side)
    // ==============================
    (() => {
        const form = document.querySelector('form[action="/report/generate"]');
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        const hint = document.getElementById('range-hint');

        if (!form || !startDate || !endDate) return;

        const MAX_DAYS = 31;

        function validate() {
            hint.classList.remove('text-red-600');
            hint.classList.add('text-gray-500', 'dark:text-gray-400');

            if (!startDate.value || !endDate.value) return true;

            if (endDate.value < startDate.value) {
                hint.textContent = '⚠ Tanggal akhir tidak boleh lebih kecil dari tanggal mulai.';
                hint.classList.remove('text-gray-500', 'dark:text-gray-400');
                hint.classList.add('text-red-600');
                return false;
            }

            const s = new Date(startDate.value);
            const e = new Date(endDate.value);
            const diffDays = Math.floor((e - s) / (1000 * 60 * 60 * 24)) + 1;

            if (diffDays > MAX_DAYS) {
                hint.textContent = `⚠ Rentang maksimal ${MAX_DAYS} hari. Rentang Anda: ${diffDays} hari.`;
                hint.classList.remove('text-gray-500', 'dark:text-gray-400');
                hint.classList.add('text-red-600');
                return false;
            }

            hint.innerHTML = `Total rentang: <span class="font-black text-[#121212] dark:text-white">${diffDays} hari</span>. Maksimal ${MAX_DAYS} hari.`;
            return true;
        }

        startDate.addEventListener('change', () => {
            if (startDate.value) endDate.min = startDate.value;
            validate();
        });
        endDate.addEventListener('change', () => {
            if (endDate.value) startDate.max = endDate.value;
            validate();
        });

        form.addEventListener('submit', (e) => {
            if (!validate()) {
                e.preventDefault();
                endDate.focus();
            }
        });

        validate();
    })();
</script>