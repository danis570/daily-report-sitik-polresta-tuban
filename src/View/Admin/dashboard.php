<main class="pt-20 min-h-screen bg-white dark:bg-dark">

    <!-- Dashboard Header -->
    <section class="border-b-4 border-black dark:border-primary bg-primary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">

                <div>
                    <p class="font-bold uppercase tracking-widest text-sm mb-3">
                        Admin Panel
                    </p>

                    <h1 class="font-display text-4xl md:text-6xl uppercase tracking-tight text-black">
                        Dashboard
                    </h1>

                    <p class="mt-4 text-lg font-medium max-w-2xl text-black/80">
                        Kelola pengguna dan pantau informasi sistem melalui halaman administrasi.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <div
                        class="border-4 border-black dark:border-primary bg-white dark:bg-[#121212] px-4 py-3 shadow-brutal dark:shadow-brutal-white flex items-center gap-3 text-black dark:text-white">
                        <i data-lucide="shield-check" class="w-6 h-6 text-black dark:text-primary"></i>
                        <span class="font-bold uppercase">
                            Admin
                        </span>
                    </div>
                </div>

            </div>

        </div>
    </section>


    <!-- Dashboard Content -->
    <section class="py-12">

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Statistics -->
            <?php
            $totalUsers = count($users);
            $totalAdmins = 0;
            $totalRegularUsers = 0;

            foreach ($users as $user) {
                if (($user['role'] ?? '') === 'admin') {
                    $totalAdmins++;
                } else {
                    $totalRegularUsers++;
                }
            }
            ?>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-12">

                <!-- Total Users -->
                <div
                    class="border-4 border-black dark:border-white bg-white dark:bg-dark p-6 shadow-brutal dark:shadow-brutal-dark">
                    <div class="flex items-center justify-between mb-6">
                        <div
                            class="w-12 h-12 border-4 border-black dark:border-white bg-primary flex items-center justify-center">
                            <i data-lucide="users" class="w-6 h-6 text-black"></i>
                        </div>

                        <span class="font-display text-4xl">
                            <?= $totalUsers ?>
                        </span>
                    </div>

                    <p class="font-bold uppercase tracking-wide">
                        Total User
                    </p>

                    <p class="text-sm mt-1 text-gray-600 dark:text-gray-400">
                        Jumlah seluruh pengguna
                    </p>
                </div>


                <!-- Admin -->
                <div
                    class="border-4 border-black dark:border-white bg-white dark:bg-dark p-6 shadow-brutal dark:shadow-brutal-dark">
                    <div class="flex items-center justify-between mb-6">
                        <div
                            class="w-12 h-12 border-4 border-black dark:border-white bg-black dark:bg-primary flex items-center justify-center">
                            <i data-lucide="shield" class="w-6 h-6 text-primary dark:text-black"></i>
                        </div>

                        <span class="font-display text-4xl">
                            <?= $totalAdmins ?>
                        </span>
                    </div>

                    <p class="font-bold uppercase tracking-wide">
                        Administrator
                    </p>

                    <p class="text-sm mt-1 text-gray-600 dark:text-gray-400">
                        Pengguna dengan akses admin
                    </p>
                </div>


                <!-- Regular Users -->
                <div
                    class="border-4 border-black dark:border-white bg-white dark:bg-dark p-6 shadow-brutal dark:shadow-brutal-dark">
                    <div class="flex items-center justify-between mb-6">
                        <div
                            class="w-12 h-12 border-4 border-black dark:border-white bg-white dark:bg-primary flex items-center justify-center">
                            <i data-lucide="user" class="w-6 h-6"></i>
                        </div>

                        <span class="font-display text-4xl">
                            <?= $totalRegularUsers ?>
                        </span>
                    </div>

                    <p class="font-bold uppercase tracking-wide">
                        User Biasa
                    </p>

                    <p class="text-sm mt-1 text-gray-600 dark:text-gray-400">
                        Pengguna tanpa akses admin
                    </p>
                </div>

            </div>

        </div>

    </section>

</main>

<!-- DELETE MODAL -->

<div id="delete-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center px-4">

    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeDeleteModal()">
    </div>


    <!-- Modal -->
    <div class="relative w-full max-w-lg
    border-4 border-black dark:border-primary
    bg-white dark:bg-dark
    shadow-[10px_10px_0px_#00d982]">

        <!-- Modal Header -->
        <div class="flex items-center justify-between
        px-6 py-5
        bg-primary
        border-b-4 border-black">

            <div class="flex items-center gap-3">

                <div class="w-10 h-10
                border-4 border-black
                bg-black text-primary
                flex items-center justify-center">

                    <i data-lucide="triangle-alert" class="w-5 h-5"></i>

                </div>

                <h2 class="font-display text-xl uppercase text-black">
                    Konfirmasi
                </h2>

            </div>

            <button type="button" onclick="closeDeleteModal()" class="w-10 h-10
            border-4 border-black
            bg-white text-black
            hover:bg-black hover:text-primary
            transition-colors">

                <i data-lucide="x" class="w-5 h-5 mx-auto"></i>

            </button>

        </div>


        <!-- Modal Body -->
        <div class="px-6 py-8">

            <p class="text-lg font-bold">
                Apakah kamu yakin ingin menghapus user ini?
            </p>

            <div class="mt-5
            border-4 border-black dark:border-white
            bg-gray-100 dark:bg-gray-800
            px-4 py-4">

                <p class="text-xs uppercase font-bold text-gray-500 dark:text-gray-400">
                    User yang akan dihapus
                </p>

                <p id="delete-user-email" class="mt-1 font-display text-lg break-all">
                </p>

            </div>

            <p class="mt-5 text-sm text-gray-600 dark:text-gray-400">
                Tindakan ini akan menghapus user dari sistem.
                Pastikan kamu sudah memeriksa akun yang dipilih.
            </p>

        </div>


        <!-- Modal Footer -->
        <div class="px-6 py-5
        border-t-4 border-black dark:border-white
        flex flex-col sm:flex-row gap-4 justify-end">

            <button type="button" onclick="closeDeleteModal()" class="px-6 py-3
            border-4 border-black dark:border-white
            font-bold uppercase
            hover:bg-primary hover:text-black
            transition-colors">

                Batal

            </button>


            <form id="delete-user-form" action="/user/delete" method="post">

                <input type="hidden" name="id" id="delete-user-id">

                <button type="submit" class="w-full sm:w-auto
                px-6 py-3
                bg-red-500
                text-white
                border-4 border-black
                font-display uppercase
                shadow-[4px_4px_0px_#000]
                hover:bg-red-600
                hover:shadow-none
                hover:translate-x-[4px]
                hover:translate-y-[4px]
                transition-all">

                    <span class="inline-flex items-center gap-2">

                        <i data-lucide="trash-2" class="w-5 h-5"></i>

                        Ya, Hapus

                    </span>

                </button>

            </form>

        </div>

    </div>

</div>

<script>
    const deleteModal = document.getElementById('delete-modal');
    const deleteUserId = document.getElementById('delete-user-id');
    const deleteUserEmail = document.getElementById('delete-user-email');

    function openDeleteModal(id, email) {
        deleteUserId.value = id;
        deleteUserEmail.textContent = email;

        deleteModal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeDeleteModal() {
        deleteModal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    // Tutup modal dengan tombol Escape
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeDeleteModal();
        }
    });
</script>