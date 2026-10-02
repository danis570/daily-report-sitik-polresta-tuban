<!-- CONTACT SECTION (LOGIN FORM) -->
<section
    class="py-20 mt-20 bg-gray-100 dark:bg-[#0a0a0a] border-t-4 border-black dark:border-primary relative overflow-hidden">
    <!-- Decoration -->

    <?php if (isset($error)) { ?>
        <div class="max-w-3xl mx-auto mb-4 px-4">
            <div class="bg-red-500 dark:bg-red-600 text-white border-4 border-black dark:border-white p-4 font-bold shadow-[6px_6px_0px_#000] dark:shadow-[6px_6px_0px_#fff] flex items-center gap-4">
                <div
                    class="w-10 h-10 flex-shrink-0 border-4 border-black dark:border-white bg-black text-red-500 flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="w-5 h-5"></i>
                </div>
                <div class="flex-1">
                    <p class="uppercase text-sm font-display">Error</p>
                    <p><?= htmlspecialchars($error) ?></p>
                </div>
                <button type="button" onclick="this.closest('div.max-w-3xl').remove()"
                    class="w-10 h-10 flex-shrink-0 border-4 border-black dark:border-white bg-white text-black flex items-center justify-center hover:bg-black hover:text-red-500 transition-colors"
                    aria-label="Tutup pesan">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
    <?php } ?>

    <div
        class="absolute -right-20 top-10 w-72 h-72 z-0 flex items-center justify-center opacity-30 dark:opacity-10 pointer-events-none">
        <div
            class="absolute w-72 h-72 border-4 border-black dark:border-white rounded-full animate-ping [animation-duration:3s]">
        </div>
        <div class="absolute w-56 h-56 border-4 border-black dark:border-white rounded-full"></div>
        <div class="absolute w-40 h-40 border-4 border-dashed border-primary rounded-full"></div>
        <div class="absolute w-20 h-20 bg-black dark:bg-primary rounded-full"></div>
    </div>

    <div class="max-w-3xl mx-auto px-4 relative z-10">
        <div
            class="bg-white dark:bg-[#121212] border-4 border-black dark:border-white p-8 md:p-12 shadow-[12px_12px_0px_0px_#000] dark:shadow-[12px_12px_0px_0px_#00d982]">
            <h2 class="font-display text-4xl md:text-5xl mb-6 uppercase text-black dark:text-white">Login</h2>

            <form action="/login" method="POST" class="space-y-6">
                <div>
                    <label class="block font-bold text-xl mb-2 uppercase text-black dark:text-white">Email</label>
                    <input type="text" name="email"
                        class="w-full bg-gray-50 dark:bg-black border-4 border-black dark:border-white p-4 font-bold text-lg focus:outline-none focus:shadow-[4px_4px_0px_#00d982] text-black dark:text-white transition-shadow placeholder-gray-400 dark:placeholder-gray-600"
                        placeholder="MASUKKAN EMAIL">
                </div>

                <div>
                    <label class="block font-bold text-xl mb-2 uppercase text-black dark:text-white">Password</label>
                    <div class="relative">
                        <input id="login-password" type="password" name="password"
                            class="w-full bg-gray-50 dark:bg-black border-4 border-black dark:border-white p-4 pr-16 font-bold text-lg focus:outline-none focus:shadow-[4px_4px_0px_#00d982] text-black dark:text-white transition-shadow placeholder-gray-400 dark:placeholder-gray-600"
                            placeholder="••••••••">

                        <button type="button" onclick="toggleLoginPassword()"
                            class="absolute right-2 top-1/2 -translate-y-1/2 w-12 h-12 flex items-center justify-center border-4 border-black dark:border-white bg-white dark:bg-black text-black dark:text-white hover:bg-primary hover:text-black transition-colors"
                            aria-label="Tampilkan password">
                            <i data-lucide="eye" id="login-eye-icon" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-black text-white dark:bg-primary dark:text-black font-display text-2xl py-4 border-4 border-transparent hover:bg-primary hover:text-black hover:border-black dark:hover:bg-white transition-all shadow-brutal dark:shadow-brutal-white hover:shadow-none hover:translate-x-[6px] hover:translate-y-[6px]">
                    MASUK SYSTEM
                </button>
            </form>
        </div>
    </div>
</section>

<script>
    function toggleLoginPassword() {
        const input = document.getElementById('login-password');
        const icon = document.getElementById('login-eye-icon');

        const isHidden = input.type === 'password';

        input.type = isHidden ? 'text' : 'password';

        icon.setAttribute('data-lucide', isHidden ? 'eye-off' : 'eye');

        if (window.lucide) {
            lucide.createIcons();
        }
    }
</script>