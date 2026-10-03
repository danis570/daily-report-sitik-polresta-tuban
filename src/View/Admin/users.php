<!-- Dashboard Header -->
<section class="border-b-4 border-black dark:border-primary bg-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">

            <div>
                <p class="font-bold uppercase tracking-widest text-sm mb-3 text-black">
                    Admin Panel
                </p>

                <h1 class="font-display text-4xl md:text-6xl uppercase tracking-tight text-black">
                    Semua User
                </h1>

                <p class="mt-4 text-lg font-medium max-w-2xl text-black/80">
                    Kelola semua pengguna yang terdaftar dalam sistem.
                </p>
            </div>

            <div class="flex items-center gap-3">
                <div class="border-4 border-black bg-white px-4 py-3
                            shadow-[6px_6px_0_0_#000]
                            flex items-center gap-3 text-black">
                    <i data-lucide="shield-check" class="w-6 h-6"></i>
                    <span class="font-bold uppercase">Admin</span>
                </div>
            </div>

        </div>

    </div>
</section>


<!-- Content -->
<section class="py-12 bg-white dark:bg-[#121212]">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

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

        <!-- Flash Message -->
        <?php if (!empty($_SESSION['flash_message'])): ?>
            <div id="flash-message" class="mb-6 p-4
                       bg-primary text-black
                       border-4 border-black dark:border-white
                       shadow-[6px_6px_0_0_#000] dark:shadow-[6px_6px_0_0_#00d982]
                       flex items-center gap-4
                       transition-all duration-200" role="alert">

                <div class="w-10 h-10 flex-shrink-0
                            border-4 border-black
                            bg-black text-primary
                            flex items-center justify-center">
                    <i data-lucide="check" class="w-5 h-5"></i>
                </div>

                <div class="flex-1 min-w-0">
                    <p class="uppercase text-sm font-display">Berhasil</p>
                    <p class="font-bold break-words">
                        <?= htmlspecialchars($_SESSION['flash_message']) ?>
                    </p>
                </div>

                <button type="button" id="flash-close" class="w-10 h-10 flex-shrink-0
                           border-4 border-black
                           bg-white text-black
                           flex items-center justify-center
                           hover:bg-black hover:text-primary
                           transition-colors cursor-pointer" aria-label="Tutup pesan">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>

            </div>
            <?php \Unirow2026\DailyReportSitikPolrestaTuban\App\View::clearFlashMessage(); ?>
        <?php endif; ?>


        <!-- User Table Card -->
        <div class="border-4 border-black dark:border-white
                    bg-white dark:bg-[#181818]
                    shadow-[6px_6px_0_0_#000] dark:shadow-[6px_6px_0_0_#00d982]">

            <!-- Table Header -->
            <div class="px-6 py-5
                        border-b-4 border-black dark:border-white
                        flex flex-col sm:flex-row
                        sm:items-center sm:justify-between
                        gap-4">

                <div>
                    <h2 class="font-display text-2xl uppercase
                               text-black dark:text-white">
                        Daftar Pengguna
                    </h2>
                    <p class="text-sm mt-1 text-gray-600 dark:text-gray-400">
                        Daftar seluruh pengguna yang terdaftar dalam sistem.
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">

                    <!-- Total User Badge -->
                    <div class="flex items-center justify-center gap-2
                                border-4 border-black dark:border-white
                                px-4 py-2 font-bold
                                text-black dark:text-white">
                        <i data-lucide="database" class="w-5 h-5"></i>
                        <?= $totalUsers ?> USER
                    </div>

                    <!-- Tambah Pengguna -->
                    <a href="/register" class="inline-flex items-center justify-center gap-2
                               px-5 py-3
                               bg-primary text-black
                               border-4 border-black dark:border-white
                               font-display uppercase
                               shadow-[4px_4px_0_0_#000] dark:shadow-[4px_4px_0_0_#00d982]
                               hover:shadow-none
                               hover:translate-x-[4px] hover:translate-y-[4px]
                               transition-all">
                        <i data-lucide="user-plus" class="w-5 h-5"></i>
                        Tambah Pengguna
                    </a>

                </div>

            </div>


            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full min-w-[600px]">

                    <thead>
                        <tr class="bg-black text-white dark:bg-primary dark:text-black">
                            <th class="px-6 py-4 text-left font-display uppercase">No</th>
                            <th class="px-6 py-4 text-left font-display uppercase">Email</th>
                            <th class="px-6 py-4 text-left font-display uppercase">Role</th>
                            <th class="px-6 py-4 text-center font-display uppercase">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (empty($users)): ?>

                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center font-bold
                                           text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center gap-3">
                                        <i data-lucide="users-round-x" class="w-12 h-12"></i>
                                        <span>Belum ada pengguna.</span>
                                    </div>
                                </td>
                            </tr>

                        <?php else: ?>

                            <?php foreach ($users as $i => $u): ?>
                                <?php $isAdmin = ($u['role'] ?? '') === 'admin'; ?>

                                <tr class="border-t-4 border-black dark:border-white
                                           hover:bg-primary/10 transition-colors">

                                    <!-- Number -->
                                    <td class="px-6 py-5 font-display text-lg
                                               text-black dark:text-white">
                                        <?= $i + 1 ?>
                                    </td>

                                    <!-- Email -->
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 flex-shrink-0
                                                        border-2 border-black dark:border-white
                                                        bg-primary
                                                        flex items-center justify-center">
                                                <i data-lucide="user" class="w-5 h-5 text-black"></i>
                                            </div>

                                            <span class="font-bold text-black dark:text-white">
                                                <?= htmlspecialchars($u['email']) ?>
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Role -->
                                    <td class="px-6 py-5">
                                        <span class="inline-flex items-center gap-2 px-3 py-1
                                                     border-2 border-black dark:border-white
                                                     font-bold uppercase text-sm
                                            <?= $isAdmin
                                                ? 'bg-black text-primary dark:bg-primary dark:text-black'
                                                : 'bg-white text-black dark:bg-[#222] dark:text-white' ?>">
                                            <i data-lucide="<?= $isAdmin ? 'shield-check' : 'user' ?>" class="w-4 h-4"></i>
                                            <?= htmlspecialchars($u['role']) ?>
                                        </span>
                                    </td>

                                    <!-- Action -->
                                    <td class="px-6 py-5 text-center">
                                        <?php if ($isAdmin): ?>
                                            <button type="button" disabled title="Akun admin tidak dapat dihapus" class="inline-flex items-center gap-2 px-4 py-2
                                                       border-4 border-gray-400
                                                       bg-gray-300 dark:bg-[#333] text-gray-500 dark:text-gray-400
                                                       font-bold uppercase
                                                       cursor-not-allowed">
                                                <i data-lucide="shield-off" class="w-5 h-5"></i>
                                                Hapus
                                            </button>
                                        <?php else: ?>
                                            <button type="button" class="delete-btn inline-flex items-center gap-2 px-4 py-2
                                                       border-4 border-black dark:border-white
                                                       bg-red-500 text-white
                                                       font-bold uppercase
                                                       shadow-[4px_4px_0_0_#000] dark:shadow-[4px_4px_0_0_#00d982]
                                                       hover:bg-red-600
                                                       hover:shadow-none
                                                       hover:translate-x-[4px] hover:translate-y-[4px]
                                                       transition-all cursor-pointer"
                                                data-id="<?= htmlspecialchars($u['id']) ?>"
                                                data-email="<?= htmlspecialchars($u['email'], ENT_QUOTES) ?>">
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


<!-- ============================== -->
<!-- DELETE MODAL -->
<!-- ============================== -->
<div id="delete-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center px-4">

    <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" onclick="closeDeleteModal()"></div>

    <div class="relative w-full max-w-lg
                border-4 border-black dark:border-white
                bg-white dark:bg-[#181818]
                shadow-[10px_10px_0_0_#00d982]">

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
                       transition-colors cursor-pointer">
                <i data-lucide="x" class="w-5 h-5 mx-auto"></i>
            </button>

        </div>

        <!-- Modal Body -->
        <div class="px-6 py-8">
            <p class="text-lg font-bold text-black dark:text-white">
                Apakah kamu yakin ingin menghapus user ini?
            </p>

            <div class="mt-5
                        border-4 border-black dark:border-white
                        bg-gray-100 dark:bg-[#222]
                        px-4 py-4">
                <p class="text-xs uppercase font-bold
                          text-gray-500 dark:text-gray-400">
                    User yang akan dihapus
                </p>
                <p id="delete-user-email" class="mt-1 font-display text-lg break-all
                           text-black dark:text-white">
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
                       text-black dark:text-white
                       font-bold uppercase
                       hover:bg-primary hover:text-black
                       transition-colors cursor-pointer">
                Batal
            </button>

            <form id="delete-user-form" action="/user/delete" method="post">
                <input type="hidden" name="id" id="delete-user-id">

                <button type="submit" class="w-full sm:w-auto
                           px-6 py-3
                           bg-red-500 text-white
                           border-4 border-black
                           font-display uppercase
                           shadow-[4px_4px_0_0_#000]
                           hover:bg-red-600
                           hover:shadow-none
                           hover:translate-x-[4px] hover:translate-y-[4px]
                           transition-all cursor-pointer">
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
    document.addEventListener('DOMContentLoaded', function () {

        /* ==========================================================
         * 1. FLASH MESSAGE CLOSE
         * ========================================================== */
        (() => {
            const flash = document.getElementById('flash-message');
            const flashClose = document.getElementById('flash-close');

            if (!flash || !flashClose) return;

            flashClose.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                flash.style.opacity = '0';
                flash.style.transform = 'translateY(-10px)';
                setTimeout(() => flash.remove(), 200);
            });
        })();


        /* ==========================================================
         * 2. MODAL KONFIRMASI HAPUS
         * ========================================================== */
        (() => {
            const modal = document.getElementById('delete-modal');
            const deleteUserId = document.getElementById('delete-user-id');
            const deleteUserEmail = document.getElementById('delete-user-email');

            if (!modal) return;

            window.openDeleteModal = function (id, email) {
                deleteUserId.value = id;
                deleteUserEmail.textContent = email;

                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            };

            window.closeDeleteModal = function () {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            };

            // Bind ke tombol .delete-btn
            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    openDeleteModal(this.dataset.id, this.dataset.email);
                });
            });

            // Tutup dengan ESC
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeDeleteModal();
                }
            });

        })();

    });
</script>