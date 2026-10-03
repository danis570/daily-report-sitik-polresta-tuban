<!-- ============================== -->
<!-- LOGIN SECTION -->
<!-- ============================== -->
<section class="py-16 md:py-20
                bg-gray-100 dark:bg-[#0a0a0a]
                relative overflow-hidden">

    <!-- Background Decoration -->
    <div class="absolute -right-20 top-10 w-72 h-72 z-0
                flex items-center justify-center
                opacity-20 dark:opacity-10 pointer-events-none">

        <div class="absolute w-72 h-72
                    border-4 border-black dark:border-white
                    rounded-full animate-ping [animation-duration:3s]"></div>

        <div class="absolute w-56 h-56
                    border-4 border-black dark:border-white
                    rounded-full"></div>

        <div class="absolute w-40 h-40
                    border-4 border-dashed border-primary
                    rounded-full"></div>

        <div class="absolute w-20 h-20
                    bg-black dark:bg-primary
                    rounded-full"></div>

    </div>


    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

        <!-- Error Alert -->
        <?php if (!empty($error)): ?>
            <div id="login-error"
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

                <button type="button" id="login-error-close"
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


        <!-- Login Card -->
        <div class="bg-white dark:bg-[#181818]
                    border-4 border-black dark:border-white
                    p-8 md:p-12
                    shadow-[12px_12px_0_0_#000] dark:shadow-[12px_12px_0_0_#00d982]">

            <!-- Header -->
            <div class="mb-8">
                <h2 class="font-display text-4xl md:text-5xl uppercase
                           text-black dark:text-white">
                    Login
                </h2>

                <p class="mt-3 text-gray-600 dark:text-gray-400">
                    Masukkan email dan password untuk mengakses dashboard SITIK.
                </p>
            </div>


            <!-- Form -->
            <form action="/login" method="POST" class="space-y-6">

                <!-- Email -->
                <div>
                    <label for="login-email"
                        class="block font-bold text-lg mb-2 uppercase
                               text-black dark:text-white">
                        Email
                    </label>

                    <input id="login-email" type="email" name="email"
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
                    <label for="login-password"
                        class="block font-bold text-lg mb-2 uppercase
                               text-black dark:text-white">
                        Password
                    </label>

                    <div class="relative">
                        <input id="login-password" type="password" name="password"
                            autocomplete="current-password"
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

                        <!-- ================================================ -->
                        <!-- Toggle Password Button (SVG, tanpa Lucide) -->
                        <!-- ================================================ -->
                        <button type="button" id="toggle-login-password"
                            class="absolute right-2 top-1/2 -translate-y-1/2
                                   w-12 h-12
                                   flex items-center justify-center
                                   text-black dark:text-white
                                   hover:text-primary dark:hover:text-primary
                                   transition-colors cursor-pointer"
                            aria-label="Tampilkan password">

                            <!-- Ikon mata TERBUKA (default) -->
                            <svg id="icon-eye-open" class="w-5 h-5" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7Z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>

                            <!-- Ikon mata TERTUTUP (hidden default) -->
                            <svg id="icon-eye-closed" class="w-5 h-5 hidden" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9.88 9.88a3 3 0 1 0 4.24 4.24" />
                                <path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c7 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68" />
                                <path d="M6.61 6.61A13.526 13.526 0 0 0 2 12s3 7 10 7a9.74 9.74 0 0 0 5.39-1.61" />
                                <line x1="2" x2="22" y1="2" y2="22" />
                            </svg>

                        </button>

                    </div>
                </div>


                <!-- Submit -->
                <button type="submit"
                    class="w-full
                           flex items-center justify-center gap-3
                           bg-black text-primary
                           dark:bg-primary dark:text-black
                           font-display text-xl md:text-2xl
                           py-4
                           border-4 border-black dark:border-white
                           shadow-[6px_6px_0_0_#00d982] dark:shadow-[6px_6px_0_0_#000]
                           hover:bg-primary hover:text-black
                           dark:hover:bg-white dark:hover:text-black
                           hover:shadow-none
                           hover:translate-x-[6px] hover:translate-y-[6px]
                           transition-all duration-150 cursor-pointer">
                    <i data-lucide="log-in" class="w-6 h-6"></i>
                    MASUK SYSTEM
                </button>

            </form>

        </div>

    </div>

</section>


<!-- ============================== -->
<!-- SCRIPT -->
<!-- ============================== -->
<script>
    document.addEventListener('DOMContentLoaded', function () {

        /* ==========================================================
         * 1. TOGGLE PASSWORD (SVG — tanpa Lucide)
         * ========================================================== */
        (() => {
            const toggleBtn = document.getElementById('toggle-login-password');
            const input     = document.getElementById('login-password');
            const iconOpen  = document.getElementById('icon-eye-open');
            const iconClose = document.getElementById('icon-eye-closed');

            if (!toggleBtn || !input || !iconOpen || !iconClose) return;

            toggleBtn.addEventListener('click', function () {
                const isHidden = input.type === 'password';

                // Toggle input type
                input.type = isHidden ? 'text' : 'password';

                // Toggle ikon: sembunyikan yang terbuka, tampilkan yang tertutup
                iconOpen.classList.toggle('hidden', isHidden);
                iconClose.classList.toggle('hidden', !isHidden);

                // Update aria-label
                toggleBtn.setAttribute(
                    'aria-label',
                    isHidden ? 'Sembunyikan password' : 'Tampilkan password'
                );
            });
        })();


        /* ==========================================================
         * 2. ERROR ALERT CLOSE (fade out + remove)
         * ========================================================== */
        (() => {
            const error      = document.getElementById('login-error');
            const errorClose = document.getElementById('login-error-close');

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