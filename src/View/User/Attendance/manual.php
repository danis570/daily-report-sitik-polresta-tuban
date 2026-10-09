<div class="min-h-screen bg-gray-50 dark:bg-[#121212] pt-12 pb-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">

        <div class="mb-6">
            <h1
                class="font-black text-4xl sm:text-5xl uppercase tracking-tight leading-none text-[#121212] dark:text-white">
                Absen Manual
            </h1>
            <p class="mt-3 text-sm sm:text-base font-medium text-gray-600 dark:text-gray-400">
                Untuk absen dari rumah — pilih status sesuai kondisi Anda.
            </p>
        </div>

        <div class="bg-white dark:bg-[#181818] border-4 border-[#121212] dark:border-white
                    shadow-[7px_7px_0_0_#121212] dark:shadow-[7px_7px_0_0_#00d982] p-6 sm:p-8">

            <!-- Error Message -->
            <?php if (!empty($error)): ?>
                <div class="mb-6 p-5 bg-red-100 dark:bg-red-900/30
                border-4 border-[#121212] dark:border-white
                shadow-[5px_5px_0_0_#121212]" role="alert">
                    <div class="flex items-start gap-3">
                        <div class="w-9 h-9 shrink-0 flex items-center justify-center
                        bg-red-500 border-2 border-[#121212]">
                            <svg class="w-5 h-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <p class="font-black uppercase text-sm text-red-700 dark:text-red-400">
                                Gagal
                            </p>
                            <p class="mt-1 text-sm font-bold text-[#121212] dark:text-white">
                                <?= htmlspecialchars($error) ?>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <form action="/absen/manual" method="POST" class="space-y-6">

                <!-- Status -->
                <div>
                    <label for="status_code"
                        class="block mb-2 text-sm font-black uppercase text-[#121212] dark:text-white">
                        Status
                    </label>
                    <select name="status_code" id="status_code" required class="w-full px-4 py-3 bg-white dark:bg-[#121212] text-[#121212] dark:text-white
                               border-4 border-[#121212] dark:border-white font-bold outline-none
                               focus:border-[#00d982] transition-colors">
                        <option value="">-- Pilih Status --</option>
                        <?php foreach ($manualStatuses as $s): ?>
                            <option value="<?= htmlspecialchars($s->code) ?>"
                                <?= (($oldInput['status_code'] ?? '') === $s->code) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s->label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tanggal -->
                <div>
                    <label for="date" class="block mb-2 text-sm font-black uppercase text-[#121212] dark:text-white">
                        Tanggal
                    </label>
                    <input type="date" name="date" id="date"
                        value="<?= htmlspecialchars($oldInput['date'] ?? date('Y-m-d')) ?>"
                        max="<?= date('Y-m-d') ?>"
                        min="<?= date('Y-m-d', strtotime('-1 day')) ?>"
                        class="w-full px-4 py-3 bg-white dark:bg-[#121212] text-[#121212] dark:text-white
                               border-4 border-[#121212] dark:border-white font-bold outline-none
                               focus:border-[#00d982] transition-colors">
                    <p class="mt-2 text-xs font-bold text-gray-500 dark:text-gray-400">
                        Hanya hari ini atau kemarin.
                    </p>
                </div>

                <!-- Keterangan -->
                <div>
                    <label for="remarks" class="block mb-2 text-sm font-black uppercase text-[#121212] dark:text-white">
                        Keterangan <span class="text-gray-400">(Opsional)</span>
                    </label>
                    <textarea name="remarks" id="remarks" rows="3"
                        class="w-full px-4 py-3 bg-white dark:bg-[#121212] text-[#121212] dark:text-white
                               border-4 border-[#121212] dark:border-white font-bold outline-none
                               focus:border-[#00d982] transition-colors"><?= htmlspecialchars($oldInput['remarks'] ?? '') ?></textarea>
                </div>

                <!-- Actions -->
                <div
                    class="flex flex-col-reverse sm:flex-row sm:justify-between gap-4 pt-4 border-t-4 border-dashed border-gray-300 dark:border-gray-700">
                    <a href="/absen" class="text-center px-5 py-3 bg-white dark:bg-[#181818] text-[#121212] dark:text-white
                              border-4 border-[#121212] dark:border-white font-black uppercase text-sm
                              shadow-[5px_5px_0_0_#121212] dark:shadow-[5px_5px_0_0_#00d982]
                              hover:shadow-none hover:translate-x-[5px] hover:translate-y-[5px]
                              transition-all">
                        ← Kembali
                    </a>
                    <button type="submit" class="px-6 py-3 bg-[#00d982] text-[#121212] border-4 border-[#121212]
                               font-black uppercase text-sm
                               shadow-[5px_5px_0_0_#121212]
                               hover:shadow-none hover:translate-x-[5px] hover:translate-y-[5px]
                               transition-all cursor-pointer">
                        Simpan Absen
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>