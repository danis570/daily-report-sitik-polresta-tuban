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
                        Semua User
                    </h1>

                    <p class="mt-4 text-lg font-medium max-w-2xl text-black/80">
                        Kelola pengguna Semua Pengguna.
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

            <?php if (isset($_SESSION['flash_message'])): ?>

                <div id="flash-message" class="max-w-4xl mb-6 mx-auto px-4">
                    <div class="
            bg-primary
            text-black
            border-4 border-black
            p-4
            font-bold
            shadow-[6px_6px_0px_#000]
            flex items-center gap-4
        ">

                        <!-- Icon -->
                        <div class="
                w-10 h-10
                flex-shrink-0
                border-4 border-black
                bg-black text-primary
                flex items-center justify-center
            ">
                            <i data-lucide="check" class="w-5 h-5"></i>
                        </div>

                        <!-- Message -->
                        <div class="flex-1">
                            <p class="uppercase text-sm font-display">
                                Berhasil
                            </p>

                            <p>
                                <?= htmlspecialchars($_SESSION['flash_message']) ?>
                            </p>
                        </div>

                        <!-- Close -->
                        <button type="button" onclick="closeFlashMessage()" class="
                    w-10 h-10
                    flex-shrink-0
                    border-4 border-black
                    bg-white text-black
                    flex items-center justify-center
                    hover:bg-black hover:text-primary
                    transition-colors
                " aria-label="Tutup pesan">
                            <i data-lucide="x" class="w-5 h-5"></i>
                        </button>

                    </div>
                </div>

                <?php \Unirow2026\DailyReportSitikPolrestaTuban\App\View::clearFlashMessage(); ?>

            <?php endif; ?>
            <script>
                function closeFlashMessage() {
                    const flashMessage = document.getElementById('flash-message');

                    if (flashMessage) {
                        flashMessage.remove();
                    }
                }
            </script>


            <!-- User Table -->
            <div
                class="border-4 border-black dark:border-white bg-white dark:bg-dark shadow-brutal dark:shadow-brutal-dark">

                <!-- Table Header -->

                <div class="px-6 py-5
    border-b-4 border-black dark:border-white
    flex flex-col sm:flex-row
    sm:items-center sm:justify-between
    gap-4">

                    <div>
                        <h2 class="font-display text-2xl uppercase">
                            Daftar Pengguna
                        </h2>

                        <p class="text-sm mt-1 text-gray-600 dark:text-gray-400">
                            Daftar seluruh pengguna yang terdaftar dalam sistem.
                        </p>
                    </div>

                    <!-- Action -->
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">

                        <!-- Total User -->
                        <div class="
            flex items-center justify-center gap-2
            border-4 border-black dark:border-white
            px-4 py-2
            font-bold
        ">
                            <i data-lucide="database" class="w-5 h-5"></i>

                            <?= $totalUsers ?> USER
                        </div>

                        <!-- Tambah Pengguna -->
                        <a href="/register" class="
                inline-flex items-center justify-center gap-2
                px-5 py-3
                bg-primary text-black
                border-4 border-black dark:border-white
                font-display uppercase
                shadow-[4px_4px_0px_#000]
                hover:shadow-none
                hover:translate-x-[4px]
                hover:translate-y-[4px]
                transition-all
            ">
                            <i data-lucide="user-plus" class="w-5 h-5"></i>
                            Tambah Pengguna
                        </a>

                    </div>

                </div>

                <!-- Responsive Table -->
                <div class="overflow-x-auto">

                    <table class="w-full min-w-[600px]">

                        <thead>
                            <tr class="bg-black text-white dark:bg-primary dark:text-black">

                                <th class="px-6 py-4 text-left font-display uppercase">
                                    No
                                </th>

                                <th class="px-6 py-4 text-left font-display uppercase">
                                    Email
                                </th>

                                <th class="px-6 py-4 text-left font-display uppercase">
                                    Role
                                </th>

                                <th class="px-6 py-4 text-center font-display uppercase">
                                    Aksi
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <?php if (empty($users)): ?>

                                <tr>
                                    <td colspan="4"
                                        class="px-6 py-12 text-center font-bold text-gray-500 dark:text-gray-400">

                                        <div class="flex flex-col items-center gap-3">
                                            <i data-lucide="users-round-x" class="w-12 h-12"></i>

                                            <span>
                                                Belum ada pengguna.
                                            </span>
                                        </div>

                                    </td>
                                </tr>

                            <?php else: ?>

                                <?php foreach ($users as $i => $u): ?>

                                    <?php
                                    $isAdmin = ($u['role'] ?? '') === 'admin';
                                    ?>

                                    <tr class="border-t-4 border-black dark:border-white hover:bg-primary/10 transition-colors">

                                        <!-- Number -->
                                        <td class="px-6 py-5 font-display text-lg">
                                            <?= $i + 1 ?>
                                        </td>


                                        <!-- Email -->
                                        <td class="px-6 py-5">

                                            <div class="flex items-center gap-3">

                                                <div
                                                    class="w-10 h-10 flex-shrink-0 border-2 border-black dark:border-white bg-primary flex items-center justify-center">

                                                    <i data-lucide="user" class="w-5 h-5 text-black"></i>

                                                </div>

                                                <span class="font-bold">
                                                    <?= htmlspecialchars($u['email']) ?>
                                                </span>

                                            </div>

                                        </td>


                                        <!-- Role -->
                                        <td class="px-6 py-5">

                                            <span class="inline-flex items-center gap-2 px-3 py-1 border-2 border-black dark:border-white font-bold uppercase text-sm
                    <?= $isAdmin
                        ? 'bg-black text-primary dark:bg-primary dark:text-black'
                        : 'bg-white text-black dark:bg-dark dark:text-white'
                        ?>">

                                                <i data-lucide="<?= $isAdmin ? 'shield-check' : 'user' ?>" class="w-4 h-4">
                                                </i>

                                                <?= htmlspecialchars($u['role']) ?>

                                            </span>

                                        </td>


                                        <!-- Action -->

                                        <td class="px-6 py-5 text-center">

                                            <?php if ($isAdmin): ?>

                                                <!-- Admin tidak dapat dihapus -->
                                                <button type="button" disabled title="Akun admin tidak dapat dihapus" class="inline-flex items-center gap-2 px-4 py-2
        border-4 border-gray-400
        bg-gray-300 text-gray-500
        font-bold uppercase
        cursor-not-allowed">

                                                    <i data-lucide="shield-off" class="w-5 h-5"></i>

                                                    Hapus

                                                </button>

                                            <?php else: ?>

                                                <!-- Tombol Hapus -->
                                                <button type="button" onclick="openDeleteModal(
            '<?= htmlspecialchars($u['id']) ?>',
            '<?= htmlspecialchars($u['email'], ENT_QUOTES) ?>'
        )" class="inline-flex items-center gap-2 px-4 py-2
        border-4 border-black dark:border-white
        bg-red-500 text-white
        font-bold uppercase
        hover:bg-red-600
        hover:translate-x-[2px]
        hover:translate-y-[2px]
        transition-all">

                                                    <i data-lucide="trash-2" class="w-5 h-5"></i>

                                                    Hapus

                                                </button>

                                            <?php endif; ?>

                                        </td>



                                    </tr>

                                <?php endforeach; ?>

                            <?php endif; ?>

                        </tbody>


                    </table>

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