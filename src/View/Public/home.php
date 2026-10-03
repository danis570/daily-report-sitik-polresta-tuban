<!-- HERO SECTION -->
<section class="min-h-screen
                flex flex-col md:flex-row
                relative overflow-hidden">

    <!-- Background Decor -->
    <div class="absolute inset-0 bg-grid-pattern opacity-10
                pointer-events-none z-0"></div>


    <!-- LEFT: Content -->
    <div class="w-full md:w-1/2 lg:w-3/5
                px-8 py-12
                md:px-12 md:py-20
                lg:px-16 lg:py-24
                xl:px-20
                flex flex-col justify-center z-10
                border-b-4 md:border-b-0 md:border-r-4
                border-black dark:border-primary
                bg-white/90 dark:bg-dark/90 backdrop-blur-sm">

        <!-- Badge -->
        <div class="inline-block w-max
                    px-4 py-2 mb-6
                    bg-primary text-black
                    border-4 border-black dark:border-white
                    font-black uppercase text-xs sm:text-sm tracking-wider
                    transform -rotate-2">
            Laporan Harian
        </div>


        <!-- Title -->
        <h1 class="font-display uppercase leading-[0.85]
                   text-5xl sm:text-6xl
                   lg:text-7xl
                   xl:text-8xl
                   mb-6
                   text-black dark:text-white">
            SITIK<br>

            <span class="inline-block
                         text-primary dark:text-white
                         tracking-tight relative"
                style="
                    -webkit-text-stroke: 2px #000;
                    paint-order: stroke fill;
                    text-shadow: 4px 4px 0 #000;
                ">
                POLRESTA
            </span><br>

            TUBAN
        </h1>


        <!-- Description -->
        <p class="text-base sm:text-lg lg:text-xl
                  font-medium
                  mb-10
                  max-w-md
                  border-l-8 border-primary pl-6
                  text-black dark:text-white">
            Sistem laporan harian DIV SITIK Polresta Tuban.
        </p>


        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row gap-4">

            <a href="/login"
                class="group inline-flex items-center justify-center gap-2
                       px-6 py-3.5
                       bg-black text-primary
                       dark:bg-primary dark:text-black
                       border-4 border-black dark:border-primary
                       font-display uppercase text-base
                       shadow-[6px_6px_0_0_#00d982] dark:shadow-[6px_6px_0_0_#000]
                       hover:shadow-none
                       hover:translate-x-[6px] hover:translate-y-[6px]
                       transition-all duration-150">
                Login
                <i data-lucide="arrow-right"
                    class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
            </a>

            <a href="/about"
                class="inline-flex items-center justify-center gap-2
                       px-6 py-3.5
                       bg-white dark:bg-transparent
                       text-black dark:text-white
                       border-4 border-black dark:border-white
                       font-display uppercase text-base
                       shadow-[6px_6px_0_0_#000] dark:shadow-[6px_6px_0_0_#00d982]
                       hover:bg-primary hover:text-black hover:border-black
                       hover:shadow-none
                       hover:translate-x-[6px] hover:translate-y-[6px]
                       transition-all duration-150">
                Tentang Kami
            </a>

        </div>

    </div>


    <!-- RIGHT: Visual -->
    <div class="w-full md:w-1/2 lg:w-2/5
                bg-primary
                relative
                flex items-center justify-center
                border-b-4 border-black dark:border-white
                min-h-[400px] md:min-h-0">

        <!-- Decoration -->
        <div class="absolute -right-20 top-10 w-72 h-72 z-0
                    flex items-center justify-center
                    opacity-30 dark:opacity-10 pointer-events-none">

            <div class="absolute w-72 h-72
                        border-4 border-black dark:border-white
                        rounded-full animate-ping [animation-duration:3s]"></div>
            <div class="absolute w-56 h-56
                        border-4 border-black dark:border-white
                        rounded-full"></div>
            <div class="absolute w-40 h-40
                        border-4 border-dashed border-black dark:border-white
                        rounded-full"></div>
            <div class="absolute w-20 h-20
                        bg-black dark:bg-white
                        rounded-full"></div>

        </div>

        <!-- Floating Icon -->
        <div class="absolute w-28 h-28
                    bg-black border-4 border-white
                    bottom-16 left-8 z-10
                    rounded-full flex items-center justify-center
                    shadow-[6px_6px_0_0_#000]">
            <i data-lucide="shield-cog-corner" class="text-primary w-14 h-14"></i>
        </div>

        <!-- Main Image -->
        <div class="relative w-56 h-72 md:w-64 md:h-80
                    bg-white border-4 border-black
                    z-20
                    rotate-3 hover:rotate-0
                    transition-transform duration-300
                    shadow-[12px_12px_0_0_#000]">

            <img src="https://plus.unsplash.com/premium_photo-1687950889899-ef36866e50ca?q=80&w=687&auto=format&fit=crop"
                alt="Ilustrasi Personel TIK"
                class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all">

            <div class="absolute -bottom-6 -right-6
                        bg-black text-white
                        px-3 py-2
                        border-4 border-white
                        font-display text-lg
                        transform rotate-6">
                DIV SITIK
            </div>

        </div>

    </div>

</section>