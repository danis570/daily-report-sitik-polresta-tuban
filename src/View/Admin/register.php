<!-- Admin Header -->
<section class="border-b-4 border-black dark:border-primary bg-primary">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">

            <div>
                <p class="font-bold uppercase tracking-widest text-sm mb-3 text-black">
                    Admin Panel
                </p>

                <h1 class="font-display text-4xl md:text-6xl uppercase tracking-tight text-black">
                    Tambah Pengguna
                </h1>

                <p class="mt-4 text-lg font-medium max-w-2xl text-black/80">
                    Tambahkan pengguna baru ke dalam sistem.
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

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Error Alert -->
        <?php if (!empty($error)): ?>
            <div id="error-message"
                class="mb-6 p-4
                       bg-red-500 text-white
                       border-4 border-black dark:border-white
                       shadow-[6px_6px_0_0_#000] dark:shadow-[6px_6px_0_0_#00d982]
                       flex items-center gap-4
                       transition-all duration-200"
                role="alert">

                <div class="w-10 h-10 flex-shrink-0
                            border-4 border-black
                            bg-black text-red-500
                            flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                </div>

                <div class="flex-1 min-w-0">
                    <p class="uppercase text-sm font-display">Error</p>
                    <p class="font-bold break-words">
                        <?= htmlspecialchars($error) ?>
                    </p>
                </div>

                <button type="button" id="error-close"
                    class="w-10 h-10 flex-shrink-0
                           border-4 border-black
                           bg-white text-black
                           flex items-center justify-center
                           hover:bg-black hover:text-red-500
                           transition-colors cursor-pointer"
                    aria-label="Tutup pesan">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>

            </div>
        <?php endif; ?>


        <!-- Flash Message -->
        <?php if (!empty($_SESSION['flash_message'])): ?>
            <div id="flash-message"
                class="mb-6 p-4
                       bg-primary text-black
                       border-4 border-black dark:border-white
                       shadow-[6px_6px_0_0_#000] dark:shadow-[6px_6px_0_0_#00d982]
                       flex items-center gap-4
                       transition-all duration-200"
                role="alert">

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

                <button type="button" id="flash-close"
                    class="w-10 h-10 flex-shrink-0
                           border-4 border-black
                           bg-white text-black
                           flex items-center justify-center
                           hover:bg-black hover:text-primary
                           transition-colors cursor-pointer"
                    aria-label="Tutup pesan">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>

            </div>
            <?php \Unirow2026\DailyReportSitikPolrestaTuban\App\View::clearFlashMessage(); ?>
        <?php endif; ?>


        <!-- Form Card -->
        <div class="bg-white dark:bg-[#181818]
                    border-4 border-black dark:border-white
                    p-8 md:p-12
                    shadow-[12px_12px_0_0_#000] dark:shadow-[12px_12px_0_0_#00d982]">

            <!-- Form Header -->
            <div class="mb-8">
                <div class="inline-flex items-center gap-3
                            bg-primary text-black
                            border-4 border-black
                            px-4 py-3
                            shadow-[4px_4px_0_0_#000]">
                    <i data-lucide="user-plus" class="w-6 h-6"></i>
                    <span class="font-bold uppercase">Pengguna Baru</span>
                </div>

                <h2 class="mt-6 font-display text-3xl md:text-5xl uppercase
                           text-black dark:text-white">
                    Registrasi Pengguna
                </h2>

                <p class="mt-3 text-gray-600 dark:text-gray-400">
                    Masukkan email dan password untuk membuat akun pengguna baru.
                </p>
            </div>


            <!-- Form -->
            <form action="/register" method="POST" class="space-y-6">

                <!-- Email -->
                <div>
                    <label for="email"
                        class="block font-bold text-lg mb-2 uppercase
                               text-black dark:text-white">
                        Email
                    </label>

                    <input id="email" type="email" name="email"
                        value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                        autocomplete="email"
                        required
                        class="w-full
                               bg-gray-50 dark:bg-[#222]
                               border-4 border-black dark:border-white
                               p-4
                               font-bold text-lg
                               text-black dark:text-white
                               placeholder-gray-400 dark:placeholder-gray-600
                               outline-none
                               focus:shadow-[6px_6px_0_0_#00d982]
                               transition-shadow"
                        placeholder="MASUKKAN EMAIL">
                </div>


                <!-- Password -->
                <div>
                    <label for="password"
                        class="block font-bold text-lg mb-2 uppercase
                               text-black dark:text-white">
                        Password
                    </label>

                    <div class="relative">
                        <input id="password" type="password" name="password"
                            autocomplete="new-password"
                            required
                            class="w-full
                                   bg-gray-50 dark:bg-[#222]
                                   border-4 border-black dark:border-white
                                   p-4 pr-16
                                   font-bold text-lg
                                   text-black dark:text-white
                                   placeholder-gray-400 dark:placeholder-gray-600
                                   outline-none
                                   focus:shadow-[6px_6px_0_0_#00d982]
                                   transition-shadow"
                            placeholder="••••••••">

                        <button type="button" id="toggle-password"
                            class="absolute right-2 top-1/2 -translate-y-1/2
                                   w-12 h-12
                                   flex items-center justify-center
                                   border-4 border-black dark:border-white
                                   bg-white dark:bg-black
                                   text-black dark:text-white
                                   hover:bg-primary hover:text-black
                                   transition-colors cursor-pointer"
                            aria-label="Tampilkan password">
                            <i data-lucide="eye" id="eye-icon" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <div class="mt-3 flex items-start gap-2">
                        <span class="mt-1 w-2 h-2 flex-shrink-0
                                     bg-[#00d982]
                                     border border-[#121212]"></span>
                        <p class="text-xs font-bold text-gray-500 dark:text-gray-400">
                            Minimal 8 karakter untuk keamanan.
                        </p>
                    </div>
                </div>


                <!-- Submit -->
                <button type="submit"
                    class="w-full
                           flex items-center justify-center gap-3
                           bg-black text-white
                           dark:bg-primary dark:text-black
                           font-display text-xl md:text-2xl
                           py-4
                           border-4 border-black dark:border-white
                           shadow-[6px_6px_0_0_#000] dark:shadow-[6px_6px_0_0_#00d982]
                           hover:bg-primary hover:text-black
                           dark:hover:bg-white dark:hover:text-black
                           hover:shadow-none
                           hover:translate-x-[6px] hover:translate-y-[6px]
                           transition-all cursor-pointer">
                    <i data-lucide="user-plus" class="w-6 h-6"></i>
                    REGISTRASI
                </button>

            </form>

        </div>

    </div>
</section>


<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ==========================================================
     * 1. TOGGLE PASSWORD
     * ========================================================== */
    (() => {
        const toggleBtn = document.getElementById('toggle-password');
        const input     = document.getElementById('password');
        const icon      = document.getElementById('eye-icon');

        if (!toggleBtn || !input || !icon) return;

        toggleBtn.addEventListener('click', function () {
            const isHidden = input.type === 'password';

            input.type = isHidden ? 'text' : 'password';
            icon.setAttribute('data-lucide', isHidden ? 'eye-off' : 'eye');

            if (window.lucide) lucide.createIcons();
        });
    })();


    /* ==========================================================
     * 2. FLASH MESSAGE CLOSE
     * ========================================================== */
    (() => {
        const flash      = document.getElementById('flash-message');
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
     * 3. ERROR MESSAGE CLOSE
     * ========================================================== */
    (() => {
        const error      = document.getElementById('error-message');
        const errorClose = document.getElementById('error-close');

        if (!error || !errorClose) return;

        errorClose.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            error.style.opacity = '0';
            error.style.transform = 'translateY(-10px)';
            setTimeout(() => error.remove(), 200);
        });
    })();

});
</script>