<div class="min-h-screen bg-gray-50 dark:bg-[#121212] pt-12 pb-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">

        <div class="mb-6">
            <h1 class="font-black text-4xl sm:text-5xl uppercase tracking-tight leading-none text-[#121212] dark:text-white">
                <?= $mode === 'add' ? 'Tambah Status' : 'Edit Status' ?>
            </h1>
            <p class="mt-3 text-sm sm:text-base font-medium text-gray-600 dark:text-gray-400">
                Status absensi untuk kehadiran anggota.
            </p>
        </div>

        <div class="bg-white dark:bg-[#181818] border-4 border-[#121212] dark:border-white
                    shadow-[7px_7px_0_0_#121212] dark:shadow-[7px_7px_0_0_#00d982] p-6 sm:p-8">

            <!-- Error -->
            <?php if (!empty($_SESSION['status_error'])): ?>
                <div class="mb-6 p-4 bg-red-100 border-4 border-[#121212] shadow-[5px_5px_0_0_#121212]">
                    <p class="font-black uppercase text-sm text-red-700">Gagal</p>
                    <p class="mt-1 text-sm font-bold text-[#121212]">
                        <?= htmlspecialchars($_SESSION['status_error']) ?>
                    </p>
                </div>
                <?php unset($_SESSION['status_error']); ?>
            <?php endif; ?>

            <?php
            $old = $_SESSION['status_old'] ?? [];
            unset($_SESSION['status_old']);

            $formAction = $mode === 'add'
                ? '/admin/attendance/status/add'
                : '/admin/attendance/status/edit/' . urlencode($status->code);
            ?>

            <form action="<?= $formAction ?>" method="POST" class="space-y-6">

                <!-- Kode -->
                <div>
                    <label for="code" class="block mb-2 text-sm font-black uppercase text-[#121212] dark:text-white">
                        Kode <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="code" id="code" required
                        value="<?= htmlspecialchars($old['code'] ?? $status->code ?? '') ?>"
                        placeholder="mis. H, D, IZIN"
                        maxlength="10"
                        class="w-full px-4 py-3 bg-white dark:bg-[#121212] text-[#121212] dark:text-white
                               border-4 border-[#121212] dark:border-white font-black font-mono uppercase
                               outline-none focus:border-[#00d982]">
                    <p class="mt-2 text-xs font-bold text-gray-500 dark:text-gray-400">
                        Kode singkat (maks 10 karakter). Huruf kapital, tanpa spasi.
                    </p>
                </div>

                <!-- Label -->
                <div>
                    <label for="label" class="block mb-2 text-sm font-black uppercase text-[#121212] dark:text-white">
                        Label <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="label" id="label" required
                        value="<?= htmlspecialchars($old['label'] ?? $status->label ?? '') ?>"
                        placeholder="mis. Hadir, Dinas"
                        class="w-full px-4 py-3 bg-white dark:bg-[#121212] text-[#121212] dark:text-white
                               border-4 border-[#121212] dark:border-white font-bold
                               outline-none focus:border-[#00d982]">
                </div>

                <!-- Sort -->
                <div>
                    <label for="sort_order" class="block mb-2 text-sm font-black uppercase text-[#121212] dark:text-white">
                        Urutan
                    </label>
                    <input type="number" name="sort_order" id="sort_order" min="0"
                        value="<?= (int) ($old['sort_order'] ?? $status->sortOrder ?? 0) ?>"
                        class="w-full px-4 py-3 bg-white dark:bg-[#121212] text-[#121212] dark:text-white
                               border-4 border-[#121212] dark:border-white font-bold
                               outline-none focus:border-[#00d982]">
                    <p class="mt-2 text-xs font-bold text-gray-500 dark:text-gray-400">
                        Angka kecil tampil lebih dulu di dropdown.
                    </p>
                </div>

                <!-- Is Active (khusus edit) -->
                <?php if ($mode === 'edit'): ?>
                    <div>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1"
                                <?= ($status->isActive ?? true) ? 'checked' : '' ?>
                                class="w-5 h-5 border-4 border-[#121212]">
                            <span class="font-black uppercase text-sm text-[#121212] dark:text-white">
                                Aktif
                            </span>
                        </label>
                        <p class="mt-2 text-xs font-bold text-gray-500 dark:text-gray-400">
                            Kalau tidak aktif, status tidak muncul di dropdown absen.
                        </p>
                    </div>
                <?php endif; ?>

                <!-- Actions -->
                <div class="flex flex-col-reverse sm:flex-row sm:justify-between gap-4 pt-4 border-t-4 border-dashed border-gray-300">
                    <a href="/admin/attendance/status"
                        class="text-center px-5 py-3 bg-white dark:bg-[#181818] text-[#121212] dark:text-white
                               border-4 border-[#121212] dark:border-white font-black uppercase text-sm
                               shadow-[5px_5px_0_0_#121212] hover:shadow-none
                               transition-all">
                        ← Kembali
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-[#00d982] text-[#121212] border-4 border-[#121212]
                               font-black uppercase text-sm shadow-[5px_5px_0_0_#121212]
                               hover:shadow-none transition-all cursor-pointer">
                        <?= $mode === 'add' ? 'Simpan Status' : 'Simpan Perubahan' ?>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>