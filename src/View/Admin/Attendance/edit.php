<div class="min-h-screen bg-gray-50 dark:bg-[#121212] pt-12 pb-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">

        <div class="mb-6">
            <h1 class="font-black text-4xl sm:text-5xl uppercase tracking-tight leading-none text-[#121212] dark:text-white">
                Edit Absensi
            </h1>
            <p class="mt-3 text-sm sm:text-base font-medium text-gray-600 dark:text-gray-400">
                <?= htmlspecialchars($profile->name ?? 'User') ?> —
                <?= htmlspecialchars($attendance->attendanceDate->format('d M Y')) ?>
            </p>
        </div>

        <div class="bg-white dark:bg-[#181818] border-4 border-[#121212] dark:border-white
                    shadow-[7px_7px_0_0_#121212] dark:shadow-[7px_7px_0_0_#00d982] p-6 sm:p-8">

            <?php if (!empty($_SESSION['attendance_admin_error'])): ?>
                <div class="mb-6 p-4 bg-red-100 border-4 border-[#121212] shadow-[5px_5px_0_0_#121212]">
                    <p class="font-black uppercase text-sm text-red-700">Gagal</p>
                    <p class="mt-1 text-sm font-bold text-[#121212]">
                        <?= htmlspecialchars($_SESSION['attendance_admin_error']) ?>
                    </p>
                </div>
                <?php unset($_SESSION['attendance_admin_error']); ?>
            <?php endif; ?>

            <form action="/admin/attendance/edit/<?= (int) $attendance->id ?>" method="POST" class="space-y-6">

                <!-- Info user (readonly) -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-black uppercase text-gray-500 mb-1">Nama</p>
                        <p class="font-bold"><?= htmlspecialchars($profile->name ?? '-') ?></p>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase text-gray-500 mb-1">Pangkat/NRP</p>
                        <p class="font-bold">
                            <?= htmlspecialchars(($profile->rank ?? '') . '/' . ($profile->nrp ?? '-')) ?>
                        </p>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="status_code" class="block mb-2 text-sm font-black uppercase text-[#121212] dark:text-white">
                        Status
                    </label>
                    <select name="status_code" id="status_code" required
                        class="w-full px-4 py-3 bg-white dark:bg-[#121212] text-[#121212] dark:text-white
                               border-4 border-[#121212] dark:border-white font-bold outline-none
                               focus:border-[#00d982]">
                        <?php foreach ($allStatuses as $s): ?>
                            <option value="<?= htmlspecialchars($s->code) ?>"
                                <?= $attendance->statusCode === $s->code ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s->label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Remarks -->
                <div>
                    <label for="remarks" class="block mb-2 text-sm font-black uppercase text-[#121212] dark:text-white">
                        Keterangan
                    </label>
                    <textarea name="remarks" id="remarks" rows="3"
                        class="w-full px-4 py-3 bg-white dark:bg-[#121212] text-[#121212] dark:text-white
                               border-4 border-[#121212] dark:border-white font-bold outline-none
                               focus:border-[#00d982]"><?= htmlspecialchars($attendance->remarks ?? '') ?></textarea>
                </div>

                <!-- Info waktu (readonly) -->
                <div class="grid grid-cols-2 gap-4 pt-4 border-t-2 border-dashed border-gray-300">
                    <div>
                        <p class="text-xs font-black uppercase text-gray-500 mb-1">Masuk</p>
                        <p class="font-bold">
                            <?= $attendance->checkInTime ? $attendance->checkInTime->format('H:i') : '-' ?>
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-black uppercase text-gray-500 mb-1">Keluar</p>
                        <p class="font-bold">
                            <?= $attendance->checkOutTime ? $attendance->checkOutTime->format('H:i') : '-' ?>
                        </p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col-reverse sm:flex-row sm:justify-between gap-4 pt-4 border-t-4 border-dashed border-gray-300">
                    <a href="/admin/attendance?date=<?= $attendance->attendanceDate->format('Y-m-d') ?>"
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
                        Simpan Perubahan
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>