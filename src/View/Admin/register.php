<main class="pt-20 min-h-screen bg-white dark:bg-dark">

    <!-- Admin Header -->
    <section class="border-b-4 border-black dark:border-primary bg-primary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-6">

                <div>
                    <p class="font-bold uppercase tracking-widest text-sm mb-3">
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
                    <div class="border-4 border-black bg-white px-4 py-3 shadow-brutal flex items-center gap-3">
                        <i data-lucide="shield-check" class="w-6 h-6"></i>
                        <span class="font-bold uppercase">
                            Admin
                        </span>
                    </div>
                </div>

            </div>

        </div>
    </section>


    <!-- Register Section -->
    <section class="
            py-16 md:py-20
            bg-gray-100 dark:bg-[#0a0a0a]
            border-b-4 border-black dark:border-primary
            relative overflow-hidden
        ">

        <!-- Decoration -->
        <div class="
                absolute -right-20 top-10
                w-72 h-72
                z-0
                flex items-center justify-center
                opacity-30 dark:opacity-10
                pointer-events-none
            ">
            <div class="
                    absolute w-72 h-72
                    border-4 border-black dark:border-white
                    rounded-full
                    animate-ping
                    [animation-duration:3s]
                "></div>

            <div class="
                    absolute w-56 h-56
                    border-4 border-black dark:border-white
                    rounded-full
                "></div>

            <div class="
                    absolute w-40 h-40
                    border-4 border-dashed border-primary
                    rounded-full
                "></div>

            <div class="
                    absolute w-20 h-20
                    bg-black dark:bg-primary
                    rounded-full
                "></div>
        </div>


        <!-- Flash Message -->
        <?php if (isset($_SESSION['flash_message'])): ?>

            <div id="flash-message" class="max-w-3xl mb-6 mx-auto px-4 relative z-20">

                <div class="
                        bg-primary
                        text-black
                        border-4 border-black
                        p-4
                        font-bold
                        shadow-[6px_6px_0px_#000]
                        flex items-center gap-4
                    ">

                    <div class="
                            w-10 h-10
                            flex-shrink-0
                            border-4 border-black
                            bg-black text-primary
                            flex items-center justify-center
                        ">
                        <i data-lucide="check" class="w-5 h-5"></i>
                    </div>

                    <div class="flex-1">

                        <p class="uppercase text-sm font-display">
                            Berhasil
                        </p>

                        <p>
                            <?= htmlspecialchars($_SESSION['flash_message']) ?>
                        </p>

                    </div>

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

            <?php
            \Unirow2026\DailyReportSitikPolrestaTuban\App\View::clearFlashMessage();
            ?>

        <?php endif; ?>


        <!-- Form Container -->
        <div class="max-w-3xl mx-auto px-4 relative z-10">

            <div class="
                    bg-white dark:bg-[#121212]
                    border-4 border-black dark:border-white
                    p-8 md:p-12
                    shadow-[12px_12px_0px_0px_#000]
                    dark:shadow-[12px_12px_0px_0px_#00d982]
                ">

                <!-- Form Header -->
                <div class="mb-8">

                    <div class="
                            inline-flex
                            items-center
                            gap-3
                            bg-primary
                            text-black
                            border-4 border-black
                            px-4 py-3
                            shadow-brutal
                        ">
                        <i data-lucide="user-plus" class="w-6 h-6"></i>

                        <span class="font-bold uppercase">
                            Pengguna Baru
                        </span>
                    </div>

                    <h2 class="
                            mt-6
                            font-display
                            text-3xl md:text-5xl
                            uppercase
                            text-black dark:text-white
                        ">
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

                        <label for="email" class="
                                block
                                font-bold
                                text-lg
                                mb-2
                                uppercase
                                text-black dark:text-white
                            ">
                            Email
                        </label>

                        <input id="email" type="email" name="email"
                            value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required
                            autocomplete="email" class="
                                w-full
                                bg-gray-50 dark:bg-black
                                border-4 border-black dark:border-white
                                p-4
                                font-bold text-lg
                                text-black dark:text-white
                                placeholder-gray-400 dark:placeholder-gray-600
                                focus:outline-none
                                focus:shadow-[6px_6px_0px_#00d982]
                                transition-shadow
                            " placeholder="MASUKKAN EMAIL">

                    </div>


                    <!-- Password -->
                    <div>

                        <label for="password" class="
                                block
                                font-bold
                                text-lg
                                mb-2
                                uppercase
                                text-black dark:text-white
                            ">
                            Password
                        </label>

                        <input id="password" type="password" name="password" required autocomplete="new-password" class="
                                w-full
                                bg-gray-50 dark:bg-black
                                border-4 border-black dark:border-white
                                p-4
                                font-bold text-lg
                                text-black dark:text-white
                                placeholder-gray-400 dark:placeholder-gray-600
                                focus:outline-none
                                focus:shadow-[6px_6px_0px_#00d982]
                                transition-shadow
                            " placeholder="••••••••">

                    </div>


                    <!-- Submit -->
                    <button type="submit" class="
                            w-full
                            flex items-center justify-center gap-3
                            bg-black
                            text-white
                            dark:bg-primary
                            dark:text-black
                            font-display
                            text-xl md:text-2xl
                            py-4
                            border-4 border-black
                            dark:border-white
                            hover:bg-primary
                            hover:text-black
                            dark:hover:bg-white
                            transition-all
                            shadow-brutal
                            dark:shadow-brutal-white
                            hover:shadow-none
                            hover:translate-x-[6px]
                            hover:translate-y-[6px]
                        ">
                        <i data-lucide="user-plus" class="w-6 h-6"></i>
                        REGISTRASI
                    </button>

                </form>

            </div>

        </div>

    </section>

</main>


<script>
    function closeFlashMessage() {
        const flashMessage = document.getElementById('flash-message');

        if (flashMessage) {
            flashMessage.remove();
        }
    }
</script>