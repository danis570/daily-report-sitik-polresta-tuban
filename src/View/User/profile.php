<main class="pt-20 min-h-screen bg-white dark:bg-dark">

    <!-- Header -->
    <section class="bg-primary border-b-4 border-black">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

            <p class="font-bold uppercase tracking-widest text-sm mb-3 text-black">
                Account Settings
            </p>

            <h1 class="font-display text-4xl md:text-6xl uppercase tracking-tight text-black">
                Edit Profile
            </h1>

            <p class="mt-4 text-lg font-medium text-black/80">
                Perbarui profil .
            </p>

        </div>
    </section>


    <!-- Profile Form -->
    <section class="py-12">

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Alert Error -->
            <?php if (isset($error)): ?>

                <div
                    class="mb-8 flex items-start gap-4 border-4 border-black dark:border-white bg-red-500 text-white p-5 shadow-brutal">

                    <i data-lucide="circle-alert" class="w-6 h-6 flex-shrink-0"></i>

                    <div>
                        <p class="font-bold uppercase">
                            Error
                        </p>

                        <p class="mt-1">
                            <?= htmlspecialchars($error) ?>
                        </p>
                    </div>

                </div>

            <?php endif; ?>


            <!-- Alert Success -->
            <?php if (isset($success)): ?>

                <div
                    class="mb-8 flex items-start gap-4 border-4 border-black dark:border-white bg-primary text-black p-5 shadow-brutal">

                    <i data-lucide="circle-check" class="w-6 h-6 flex-shrink-0"></i>

                    <div>
                        <p class="font-bold uppercase">
                            Berhasil
                        </p>

                        <p class="mt-1">
                            <?= htmlspecialchars($success) ?>
                        </p>
                    </div>

                </div>

            <?php endif; ?>


            <!-- Form Card -->
            <div
                class="border-4 border-black dark:border-white bg-white dark:bg-dark shadow-brutal dark:shadow-brutal-dark">

                <!-- Card Header -->
                <div class="border-b-4 border-black dark:border-white p-6">

                    <h2 class="font-display text-2xl uppercase">
                        Informasi Profil
                    </h2>

                </div>


                <form action="/profile" method="post" enctype="multipart/form-data">

                    <div class="p-6 md:p-8 space-y-8">

                        <!-- Avatar -->
                        <div>

                            <label class="block font-bold uppercase mb-4">
                                Foto Profil
                            </label>

                            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6">

                                <!-- Preview -->
                                <div
                                    class="relative w-40 h-40 flex-shrink-0 border-4 border-black dark:border-white bg-gray-100 dark:bg-gray-800 overflow-hidden shadow-brutal">

                                    <img id="avatar-preview" <img id="avatar-preview"
                                        src="<?= ($profile && $profile->avatar) ? '/uploads/avatar/' . htmlspecialchars($profile->avatar) : '/uploads/avatar/default-avatar.png' ?>"
                                        alt="Avatar" class="w-full h-full object-cover">


                                    <!-- Placeholder -->
                                    <div id="avatar-placeholder"
                                        class="hidden absolute inset-0 flex flex-col items-center justify-center bg-primary text-black">

                                        <i data-lucide="user" class="w-12 h-12"></i>

                                        <span class="font-bold text-xs uppercase mt-2">
                                            No Image
                                        </span>

                                    </div>

                                </div>


                                <!-- Upload -->
                                <div class="flex-1 w-full">

                                    <label for="avatar"
                                        class="flex flex-col items-center justify-center w-full min-h-[160px] border-4 border-dashed border-black dark:border-white hover:bg-primary/10 cursor-pointer transition-colors">

                                        <i data-lucide="upload" class="w-10 h-10 mb-3"></i>

                                        <span class="font-bold uppercase">
                                            Pilih Foto
                                        </span>

                                        <span class="text-sm text-gray-500 dark:text-gray-400 mt-2 text-center">
                                            JPG, JPEG, PNG atau WEBP
                                        </span>

                                        <span class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Maksimal ukuran sesuai validasi server
                                        </span>

                                        <input type="file" name="avatar" id="avatar"
                                            accept="image/jpeg,image/png,image/webp" class="hidden">

                                    </label>

                                </div>

                            </div>

                        </div>


                        <!-- Name -->
                        <div>

                            <label for="name" class="block font-bold uppercase mb-3">
                                Nama
                            </label>

                            <div class="relative">

                                <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5">
                                </i>

                                <input
                                    class="w-full border-4 border-black dark:border-white bg-white dark:bg-dark text-black dark:text-white pl-12 pr-4 py-4 font-bold outline-none focus:bg-primary focus:text-black dark:focus:text-black transition-colors"
                                    type="text" name="name" id="name"
                                    value="<?= htmlspecialchars($profile->name ?? '') ?>" placeholder="Masukkan nama"
                                    required>

                            </div>

                        </div>


                        <!-- Email Readonly -->
                        <?php if (isset($user['email'])): ?>

                            <div>

                                <label for="email" class="block font-bold uppercase mb-3">
                                    Email
                                </label>

                                <div class="relative">

                                    <i data-lucide="mail" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5">
                                    </i>

                                    <input
                                        class="w-full border-4 border-black dark:border-white bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400 pl-12 pr-4 py-4 font-bold cursor-not-allowed"
                                        type="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" readonly>

                                </div>

                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    Email tidak dapat diubah melalui halaman ini.
                                </p>

                            </div>

                        <?php endif; ?>

                    </div>


                    <!-- Form Footer -->
                    <div
                        class="border-t-4 border-black dark:border-white p-6 md:p-8 flex flex-col sm:flex-row gap-4 sm:justify-end">

                        <a href="/"
                            class="px-6 py-3 border-4 border-black dark:border-white font-bold uppercase text-center hover:bg-primary hover:text-black transition-colors">
                            Batal
                        </a>

                        <button type="submit"
                            class="px-8 py-3 bg-black text-primary font-display uppercase border-4 border-black shadow-[4px_4px_0px_#00d982] hover:bg-primary hover:text-black hover:shadow-none transition-all dark:bg-primary dark:text-black dark:border-primary dark:hover:bg-white">

                            <span class="inline-flex items-center gap-2">
                                <i data-lucide="save" class="w-5 h-5"></i>
                                Update Profile
                            </span>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </section>

</main>

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

        // Pastikan file merupakan gambar
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
</script>