<div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">

    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="mb-8">

            <h1 class="text-4xl sm:text-5xl font-black uppercase tracking-tight text-black">
                Edit Profil
            </h1>

            <p class="mt-3 text-gray-600 font-medium">
                Perbarui informasi profil Anda.
            </p>
        </div>


        <!-- Alert Error -->
        <?php if (!empty($error)): ?>
            <div id="error-message" class="mb-6 p-5
               bg-red-100 dark:bg-red-900/30
               border-2 border-black dark:border-white
               shadow-[6px_6px_0px_0px_#000] dark:shadow-[6px_6px_0px_0px_#00d982]
               flex items-start gap-4
               transition-all duration-200" role="alert">

                <div class="flex-shrink-0 w-9 h-9
                    flex items-center justify-center
                    bg-red-500 border-2 border-[#121212]">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                            clip-rule="evenodd" />
                    </svg>
                </div>

                <div class="flex-1 min-w-0">
                    <p class="font-black uppercase text-sm text-red-700 dark:text-red-400">
                        Terjadi Kesalahan
                    </p>
                    <p class="mt-1 text-sm font-bold text-black dark:text-white break-words">
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


        <!-- Alert Success -->
        <?php if (!empty($success)): ?>
            <div id="success-message" class="mb-6 p-5
               bg-[#00d982]
               border-2 border-black dark:border-white
               shadow-[6px_6px_0px_0px_#000] dark:shadow-[6px_6px_0px_0px_#00d982]
               flex items-start gap-4
               transition-all duration-200" role="alert">

                <div class="flex-shrink-0 w-9 h-9
                    flex items-center justify-center
                    bg-[#121212] border-2 border-[#121212]">
                    <svg class="w-5 h-5 text-[#00d982]" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </div>

                <div class="flex-1 min-w-0">
                    <p class="font-black uppercase text-sm text-black">
                        Berhasil
                    </p>
                    <p class="mt-1 text-sm font-bold text-black break-words">
                        <?= htmlspecialchars($success) ?>
                    </p>
                </div>

                <button type="button" id="success-close" class="shrink-0 w-8 h-8 flex items-center justify-center
                   bg-[#121212] text-[#00d982] border-2 border-[#121212]
                   hover:bg-white hover:text-black
                   transition-colors cursor-pointer" aria-label="Tutup notifikasi">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 6l12 12M6 18L18 6" />
                    </svg>
                </button>

            </div>
        <?php endif; ?>


        <!-- Flash Message (Global) -->
        <?php if (!empty($_SESSION['flash_message'])): ?>
            <div id="flash-message" class="mb-6 p-5
               bg-[#00d982]
               border-2 border-black dark:border-white
               shadow-[6px_6px_0px_0px_#000] dark:shadow-[6px_6px_0px_0px_#00d982]
               flex items-start gap-4
               transition-all duration-200" role="alert">

                <div class="flex-shrink-0 w-9 h-9
                    flex items-center justify-center
                    bg-[#121212] border-2 border-[#121212]">
                    <svg class="w-5 h-5 text-[#00d982]" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </div>

                <div class="flex-1 min-w-0">
                    <p class="font-black uppercase text-sm text-black">
                        Berhasil
                    </p>
                    <p class="mt-1 text-sm font-bold text-black break-words">
                        <?= htmlspecialchars($_SESSION['flash_message']) ?>
                    </p>
                </div>

                <button type="button" id="flash-close" class="shrink-0 w-8 h-8 flex items-center justify-center
                   bg-[#121212] text-[#00d982] border-2 border-[#121212]
                   hover:bg-white hover:text-black
                   transition-colors cursor-pointer" aria-label="Tutup notifikasi">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 6l12 12M6 18L18 6" />
                    </svg>
                </button>

            </div>

            <?php \Unirow2026\DailyReportSitikPolrestaTuban\App\View::clearFlashMessage(); ?>
        <?php endif; ?>

        <!-- Form -->
        <div class="bg-white border-2 border-black
                    shadow-[8px_8px_0px_0px_#000]">

            <div class="p-6 sm:p-8">

                <form action="/profile" method="post" enctype="multipart/form-data">

                    <!-- Section Header 01 -->
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
                                Foto Profil
                            </h2>

                            <p class="text-sm text-gray-500 font-bold">
                                Unggah foto profil Anda
                            </p>
                        </div>

                    </div>


                    <!-- Avatar -->
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 mb-10">

                        <!-- Preview -->
                        <div class="relative w-40 h-40 flex-shrink-0
                                    border-4 border-black
                                    bg-gray-100 overflow-hidden
                                    shadow-[6px_6px_0px_0px_#000]">

                            <img id="avatar-preview"
                                src="<?= ($profile && $profile->avatar) ? '/uploads/avatar/' . htmlspecialchars($profile->avatar) : '/uploads/avatar/default-avatar.png' ?>"
                                alt="Avatar" class="w-full h-full object-cover">

                            <!-- Placeholder -->
                            <div id="avatar-placeholder"
                                class="hidden absolute inset-0 flex flex-col items-center justify-center bg-[#00d982] text-black">

                                <i data-lucide="user" class="w-12 h-12"></i>

                                <span class="font-bold text-xs uppercase mt-2">
                                    No Image
                                </span>

                            </div>

                        </div>

                        <!-- Upload -->
                        <div class="flex-1 w-full">

                            <label for="avatar" class="flex flex-col items-center justify-center w-full min-h-[160px]
                                       border-4 border-dashed border-black
                                       hover:bg-[#00d982]/10 cursor-pointer transition-colors">

                                <i data-lucide="upload" class="w-10 h-10 mb-3"></i>

                                <span class="font-black uppercase">
                                    Pilih Foto
                                </span>

                                <span class="text-sm text-gray-500 font-bold mt-2 text-center">
                                    JPG, JPEG, PNG atau WEBP
                                </span>

                                <span class="text-xs text-gray-500 font-bold mt-1">
                                    Maksimal ukuran sesuai validasi server
                                </span>

                                <input type="file" name="avatar" id="avatar" accept="image/jpeg,image/png,image/webp"
                                    class="hidden">

                            </label>

                        </div>

                    </div>


                    <!-- Divider -->
                    <div class="my-8 border-t-4 border-black"></div>


                    <!-- Section Header 02 -->
                    <div class="flex items-center gap-4 mb-8">

                        <div class="w-12 h-12 shrink-0
                                    bg-black text-white
                                    flex items-center justify-center
                                    font-black">
                            02
                        </div>

                        <div>
                            <h2 class="text-2xl font-black uppercase">
                                Informasi Akun
                            </h2>

                            <p class="text-sm text-gray-500 font-bold">
                                Data profil dan email akun
                            </p>
                        </div>

                    </div>


                    <!-- Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        <!-- Name -->
                        <div>

                            <label for="name" class="block mb-2 text-sm font-black uppercase">
                                Nama
                            </label>

                            <div class="relative">

                                <i data-lucide="user"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-black pointer-events-none z-10"></i>

                                <input class="w-full pl-12 pr-4 py-3
                                           bg-gray-50 border-2 border-black
                                           font-bold text-black
                                           outline-none
                                           focus:bg-white
                                           focus:shadow-[4px_4px_0px_0px_#00d982]
                                           transition-shadow" type="text" name="name" id="name"
                                    value="<?= htmlspecialchars($profile->name ?? '') ?>" placeholder="Masukkan nama"
                                    required>

                            </div>

                        </div>

                        <!-- NRP -->
                        <div>
                            <label for="nrp" class="block mb-2 text-sm font-black uppercase">
                                NRP
                            </label>
                            <div class="relative">
                                <i data-lucide="hash"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-black pointer-events-none z-10"></i>
                                <input class="w-full pl-12 pr-4 py-3
                       bg-gray-50 border-2 border-black
                       font-bold text-black outline-none
                       focus:bg-white
                       focus:shadow-[4px_4px_0px_0px_#00d982]
                       transition-shadow" type="text" name="nrp" id="nrp"
                                    value="<?= htmlspecialchars($profile->nrp ?? '') ?>" placeholder="Contoh: 82031270">
                            </div>
                        </div>

                        <!-- Pangkat -->
                        <div>
                            <label for="rank" class="block mb-2 text-sm font-black uppercase">
                                Pangkat
                            </label>
                            <div class="relative">
                                <i data-lucide="award"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-black pointer-events-none z-10"></i>
                                <input class="w-full pl-12 pr-4 py-3
                       bg-gray-50 border-2 border-black
                       font-bold text-black outline-none
                       focus:bg-white
                       focus:shadow-[4px_4px_0px_0px_#00d982]
                       transition-shadow" type="text" name="rank" id="rank"
                                    value="<?= htmlspecialchars($profile->rank ?? '') ?>" placeholder="Contoh: AIPDA">
                            </div>
                        </div>

                        <!-- Jabatan -->
                        <div class="md:col-span-2">
                            <label for="position" class="block mb-2 text-sm font-black uppercase">
                                Jabatan
                            </label>
                            <div class="relative">
                                <i data-lucide="briefcase"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-black pointer-events-none z-10"></i>
                                <input class="w-full pl-12 pr-4 py-3
                       bg-gray-50 border-2 border-black
                       font-bold text-black outline-none
                       focus:bg-white
                       focus:shadow-[4px_4px_0px_0px_#00d982]
                       transition-shadow" type="text" name="position" id="position"
                                    value="<?= htmlspecialchars($profile->position ?? '') ?>"
                                    placeholder="Contoh: PS. KASI TIK">
                            </div>
                        </div>

                        <!-- QR Code (read-only display) -->
                        <?php if (!empty($profile->qrCode)): ?>
                            <div class="md:col-span-2">
                                <label class="block mb-2 text-sm font-black uppercase">
                                    QR Code Anggota
                                </label>
                                <div class="flex items-center gap-3 p-3 bg-gray-50 border-2 border-black">
                                    <i data-lucide="qr-code" class="w-5 h-5 text-black"></i>
                                    <span class="font-mono font-black text-lg">
                                        <?= htmlspecialchars($profile->qrCode) ?>
                                    </span>
                                    <span class="ml-auto text-xs text-gray-500 font-bold">
                                        Untuk absensi
                                    </span>
                                </div>
                                <p class="mt-2 text-xs text-gray-500 font-bold">
                                    QR Code ini dipakai untuk absensi. Simpan atau cetak kartu QR Anda.
                                </p>
                            </div>
                        <?php endif; ?>


                        <!-- Email Readonly -->
                        <?php if (isset($user['email'])): ?>

                            <div>

                                <label for="email" class="block mb-2 text-sm font-black uppercase">
                                    Email
                                </label>

                                <div class="relative">

                                    <i data-lucide="mail"
                                        class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500 pointer-events-none z-10"></i>

                                    <input class="w-full pl-12 pr-4 py-3
                                               bg-gray-100 border-2 border-black
                                               font-bold text-gray-600
                                               cursor-not-allowed" type="email" id="email"
                                        value="<?= htmlspecialchars($user['email']) ?>" readonly>

                                </div>

                                <p class="mt-2 text-xs text-gray-500 font-bold">
                                    Email tidak dapat diubah.
                                </p>

                            </div>

                        <?php endif; ?>

                    </div>


                    <!-- Actions -->
                    <div class="mt-8 pt-6 border-t-4 border-black
                                flex flex-col sm:flex-row
                                justify-end gap-4">

                        <a href="/" class="px-6 py-3
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
                            <span class="inline-flex items-center gap-2">
                                <i data-lucide="save" class="w-5 h-5"></i>
                                Update Profil
                            </span>
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<!-- Avatar Preview -->
<script>
    const avatarInput = document.getElementById('avatar');
    const avatarPreview = document.getElementById('avatar-preview');
    const avatarPlaceholder = document.getElementById('avatar-placeholder');

    avatarInput.addEventListener('change', function () {

        const file = this.files[0];

        if (!file) {
            return;
        }

        if (!file.type.startsWith('image/')) {
            alert('File yang dipilih harus berupa gambar.');
            this.value = '';
            return;
        }

        const imageUrl = URL.createObjectURL(file);

        avatarPreview.src = imageUrl;
        avatarPreview.classList.remove('hidden');
        avatarPlaceholder.classList.add('hidden');

        avatarPreview.onload = function () {
            URL.revokeObjectURL(imageUrl);
        };
    });

    avatarPreview.addEventListener('error', function () {
        avatarPreview.classList.add('hidden');
        avatarPlaceholder.classList.remove('hidden');
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        /* ==========================================================
         * TOMBOL CLOSE — SEMUA MESSAGE
         * ========================================================== */
        (() => {
            // Error message
            const error = document.getElementById('error-message');
            const errorClose = document.getElementById('error-close');

            if (error && errorClose) {
                errorClose.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    fadeOut(error);
                });
            }

            // Success message
            const success = document.getElementById('success-message');
            const successClose = document.getElementById('success-close');

            if (success && successClose) {
                successClose.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    fadeOut(success);
                });
            }

            // Flash message
            const flash = document.getElementById('flash-message');
            const flashClose = document.getElementById('flash-close');

            if (flash && flashClose) {
                flashClose.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    fadeOut(flash);
                });
            }

            // --------------------------------------------------
            // Helper: fade out + remove
            // --------------------------------------------------
            function fadeOut(el) {
                el.style.opacity = '0';
                el.style.transform = 'translateY(-10px)';
                setTimeout(() => el.remove(), 200);
            }
        })();

    });
</script>