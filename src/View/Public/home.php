<!-- HERO SECTION -->
<section class="min-h-screen pt-20 flex flex-col md:flex-row relative overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute inset-0 bg-grid-pattern opacity-10 pointer-events-none z-0"></div>

    <!-- Left Content -->
    <div
        class="w-full md:w-3/5 p-8 md:p-20 flex flex-col justify-center z-10 border-b-4 md:border-b-0 md:border-r-4 border-black dark:border-primary bg-white/90 dark:bg-dark/90 backdrop-blur-sm">
        <div
            class="inline-block px-4 py-2 border-4 border-black dark:border-white bg-primary font-bold mb-6 w-max transform -rotate-2">
            laporan Harian
        </div>

        <h1 class="font-display text-6xl md:text-8xl leading-none mb-6 uppercase">
            SITIK <br>
            <span
                class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-green-600 dark:to-white stroke-black"
                style="-webkit-text-stroke: 3px black;">Polresta</span> <br>
            Tuban
        </h1>

        <p class="text-xl md:text-2xl font-sans font-medium mb-10 max-w-lg border-l-8 border-primary pl-6">
            Sistem laporan harian DIV SITIK Polresta Tuban.
        </p>

        <div class="flex flex-col sm:flex-row gap-6">
            <a href="/login"
                class="group relative px-8 py-4 bg-black text-white font-bold text-xl border-4 border-black dark:border-white hover:bg-primary hover:text-black transition-all shadow-brutal hover:shadow-brutal-hover hover:translate-x-[6px] hover:translate-y-[6px]">
                LOGIN
                <i data-lucide="arrow-right" class="inline ml-2 group-hover:translate-x-2 transition-transform"></i>
            </a>
            <a href="/about"
                class="px-8 py-4 bg-white dark:bg-transparent text-black dark:text-white font-bold text-xl border-4 border-black dark:border-white hover:bg-gray-100 dark:hover:bg-white/10 transition-all shadow-brutal dark:shadow-brutal-dark hover:shadow-brutal-hover hover:translate-x-[6px] hover:translate-y-[6px]">
                TENTANG KAMI
            </a>
        </div>
    </div>

    <!-- Right Content (Visual) -->
    <div
        class="w-full md:w-2/5 bg-primary relative flex items-center justify-center border-b-4 border-black dark:border-white min-h-[400px]">
        <!-- Decoration -->
        <div
            class="absolute -right-20 top-10 w-72 h-72 z-0 flex items-center justify-center opacity-30 dark:opacity-10 pointer-events-none">
            <div
                class="absolute w-72 h-72 border-4 border-black dark:border-white rounded-full animate-ping [animation-duration:3s]">
            </div>
            <div class="absolute w-56 h-56 border-4 border-black dark:border-white rounded-full"></div>
            <div class="absolute w-40 h-40 border-4 border-dashed border-primary rounded-full"></div>
            <div class="absolute w-20 h-20 bg-black dark:bg-primary rounded-full"></div>
        </div>
        <div
            class="absolute w-32 h-32 bg-black border-4 border-white bottom-20 left-10 z-10 rounded-full flex items-center justify-center">
            <i data-lucide="shield-cog-corner" class="text-primary w-16 h-16"></i>
        </div>

        <!-- Main Image Frame -->
        <div
            class="relative w-64 h-80 bg-white border-4 border-black z-20 rotate-3 hover:rotate-0 transition-transform duration-300 shadow-[12px_12px_0px_black]">
            <img src="http://plus.unsplash.com/premium_photo-1687950889899-ef36866e50ca?q=80&w=687&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                alt="Profile" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all">

            <!-- Floating Badge -->
            <div
                class="absolute -bottom-6 -right-6 bg-black text-white p-3 border-4 border-white font-display text-xl transform rotate-6">
                DIV SITIK
            </div>
        </div>
    </div>
</section>