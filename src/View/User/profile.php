<div class="min-h-screen bg-gray-50 py-12 px-4 mt-24 sm:px-6 lg:px-8">

    <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="mb-8">

            <a href="/" class="inline-flex items-center gap-2 px-4 py-2 mb-6
                       bg-white border-2 border-black
                       font-bold text-black
                       shadow-[4px_4px_0px_0px_#000]
                       hover:shadow-none
                       hover:translate-x-1 hover:translate-y-1
                       transition-all">
                ← Kembali ke Beranda
            </a>

            <div class="flex items-center gap-3 mb-4">
                <span class="bg-black text-white px-3 py-1 text-xs font-black uppercase">
                    Account Settings
                </span>

                <span class="text-sm font-bold text-gray-500">
                    Edit Profile
                </span>
            </div>

            <h1 class="text-4xl sm:text-5xl font-black uppercase tracking-tight text-black">
                Edit Profil
            </h1>

            <p class="mt-3 text-gray-600 font-medium">
                Perbarui informasi profil Anda.
            </p>
        </div>


        <!-- Alert Error -->
        <?php if (!empty($error)) { ?>

            <div class="mb-6 p-5 bg-red-100 border-2 border-black
                        shadow-[6px_6px_0px_0px_#000]">

                <p class="font-black uppercase text-red-700">
                    Terjadi Kesalahan
                </p>

                <p class="mt-1 text-sm font-bold text-black">
                    <?= htmlspecialchars($error) ?>
                </p>

            </div>

        <?php } ?>


        <!-- Alert Success -->
        <?php if (!empty($success)) { ?>

            <div class="mb-6 p-5 bg-[#00d982] border-2 border-black
                        shadow-[6px_6px_0px_0px_#000]">

                <p class="font-black uppercase text-black">
                    Berhasil
                </p>

                <p class="mt-1 text-sm font-bold text-black">
                    <?= htmlspecialchars($success) ?>
                </p>

            </div>

        <?php } ?>


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