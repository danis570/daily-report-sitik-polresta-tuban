<!-- CONTACT SECTION (LOGIN FORM) -->
<section
    class="py-20 mt-20 bg-gray-100 dark:bg-[#0a0a0a] border-t-4 border-black dark:border-primary relative overflow-hidden">
    <!-- Decoration -->

    <?php if (isset($error)) { ?>
        <div class="max-w-3xl mx-auto px-4">
            <div class="bg-red-100 border-4 border-black p-4 font-bold text-red-700 shadow-[4px_4px_0px_#000]">
                <span>
                    <?= htmlspecialchars($error) ?>
                </span>
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
                    <input type="password" name="password"
                        class="w-full bg-gray-50 dark:bg-black border-4 border-black dark:border-white p-4 font-bold text-lg focus:outline-none focus:shadow-[4px_4px_0px_#00d982] text-black dark:text-white transition-shadow placeholder-gray-400 dark:placeholder-gray-600"
                        placeholder="••••••••">
                </div>

                <button type="submit"
                    class="w-full bg-black text-white dark:bg-primary dark:text-black font-display text-2xl py-4 border-4 border-transparent hover:bg-primary hover:text-black hover:border-black dark:hover:bg-white transition-all shadow-brutal dark:shadow-brutal-white hover:shadow-none hover:translate-x-[6px] hover:translate-y-[6px]">
                    MASUK SYSTEM
                </button>
            </form>
        </div>
    </div>
</section>