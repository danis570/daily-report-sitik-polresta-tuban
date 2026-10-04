<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'SITIK') ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Space+Grotesk:wght@400;500;700&display=swap"
        rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#00d982',
                        dark: '#121212',
                        light: '#ffffff',
                    },
                    fontFamily: {
                        sans: ['Space Grotesk', 'sans-serif'],
                        display: ['Archivo Black', 'sans-serif'],
                    },
                    boxShadow: {
                        'brutal': '6px 6px 0px 0px rgba(0,0,0,1)',
                        'brutal-hover': '0px 0px 0px 0px rgba(0,0,0,1)',
                        'brutal-dark': '6px 6px 0px 0px #00d982',
                        'brutal-white': '6px 6px 0px 0px #ffffff',
                    },
                }
            }
        }
    </script>

    <style>
        body {
            background-color: #ffffff;
            color: #121212;
            overflow-x: hidden;
        }

        .bg-grid-pattern {
            background-image: radial-gradient(#000000 2px, transparent 2px);
            background-size: 30px 30px;
        }

        .dark .bg-grid-pattern {
            background-image: radial-gradient(#00d982 2px, transparent 2px);
            background-size: 30px 30px;
        }

        ::-webkit-scrollbar {
            width: 12px;
            height: 12px;
        }

        ::-webkit-scrollbar-track {
            background: #ffffff;
            border-left: 2px solid #000;
        }

        ::-webkit-scrollbar-thumb {
            background: #00d982;
            border: 2px solid #000;
        }

        .dark ::-webkit-scrollbar-track {
            background: #121212;
            border-left: 2px solid #00d982;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #ffffff;
            border: 2px solid #00d982;
        }

        ::selection {
            background-color: #000;
            color: #00d982;
        }

        .dark ::selection {
            background-color: #00d982;
            color: #000;
        }
    </style>
</head>

<body class="transition-colors duration-300 dark:bg-dark dark:text-white">

    <!-- ============================== -->
    <!-- NAVBAR (Public) -->
    <!-- ============================== -->
    <nav class="fixed top-0 left-0 right-0 z-50 h-16
            bg-white dark:bg-dark
            border-b-4 border-black dark:border-primary">

        <div class="max-w-7xl mx-auto h-full
                px-4 sm:px-6 lg:px-8 xl:px-12
                flex items-center justify-between">

            <!-- KIRI: Logo + TIK -->
            <a href="/" class="flex items-center gap-2.5 md:gap-3 group shrink-0">

                <div class="w-10 h-10 md:w-11 md:h-11
                        flex items-center justify-center
                        border-2 border-transparent
                        group-hover:rotate-12 transition-transform
                        overflow-hidden">
                    <img src="/assets/logo.png" alt="Logo SITIK" class="w-full h-full object-contain" />
                </div>

                <span class="font-display text-2xl md:text-[1.75rem]
                         tracking-tighter uppercase leading-none
                         text-black dark:text-white">
                    TIK
                </span>

            </a>


            <!-- KANAN: About → Theme Toggle → Login -->
            <div class="flex items-center gap-2 sm:gap-3 md:gap-4 lg:gap-5">

                <!-- ABOUT (desktop only) -->
                <a href="/about" class="hidden md:inline-flex items-center
                       px-4 py-2 md:px-5 md:py-2.5
                       text-black dark:text-white
                       font-black uppercase text-xs tracking-wider
                       border-2 border-transparent
                       hover:bg-primary hover:text-black hover:border-black
                       dark:hover:border-white
                       transition-all duration-150">
                    About
                </a>


                <!-- THEME TOGGLE -->
                <button id="theme-toggle" class="w-10 h-10 md:w-11 md:h-11
                       flex items-center justify-center
                       bg-white dark:bg-dark
                       text-black dark:text-white
                       border-2 border-black dark:border-white
                       shadow-[3px_3px_0_0_#000] dark:shadow-[3px_3px_0_0_#00d982]
                       hover:translate-x-[3px] hover:translate-y-[3px]
                       hover:shadow-none
                       transition-all duration-150 cursor-pointer" aria-label="Ganti tema">
                    <i data-lucide="sun" class="hidden dark:block w-5 h-5"></i>
                    <i data-lucide="moon" class="block dark:hidden w-5 h-5"></i>
                </button>


                <!-- LOGIN (desktop only — paling kanan) -->
                <a href="/login" class="hidden md:inline-flex items-center justify-center gap-2
                       px-5 py-2 md:px-6 md:py-2.5
                       bg-primary text-black
                       border-2 border-black dark:border-white
                       font-black uppercase text-xs tracking-wider
                       shadow-[3px_3px_0_0_#000] dark:shadow-[3px_3px_0_0_#00d982]
                       hover:translate-x-[3px] hover:translate-y-[3px]
                       hover:shadow-none
                       transition-all duration-150">
                    <i data-lucide="log-in" class="w-4 h-4"></i>
                    Login
                </a>


                <!-- MOBILE MENU BUTTON -->
                <button id="mobile-menu-btn" class="md:hidden w-10 h-10
                       flex items-center justify-center
                       bg-[#00d982] text-black
                       border-2 border-black
                       shadow-[3px_3px_0_0_#000]
                       hover:translate-x-[3px] hover:translate-y-[3px]
                       hover:shadow-none
                       transition-all duration-150 cursor-pointer" aria-label="Buka menu">
                    <i data-lucide="menu" class="w-5 h-5"></i>
                </button>

            </div>

        </div>

    </nav>


    <!-- ============================== -->
    <!-- MOBILE MENU OVERLAY -->
    <!-- ============================== -->
    <div id="mobile-menu" class="md:hidden fixed top-16 left-0 right-0 z-40 hidden
               bg-white dark:bg-dark
               border-b-4 border-black dark:border-primary">

        <div class="p-4 space-y-3">

            <a href="/about" class="block px-4 py-3
                       text-black dark:text-white
                       border-2 border-black dark:border-white
                       font-black uppercase text-sm text-center
                       hover:bg-primary hover:text-black
                       transition-colors">
                About
            </a>

            <a href="/login" class="block px-4 py-3
                       bg-primary text-black
                       border-2 border-black
                       font-black uppercase text-sm text-center">
                Login
            </a>

        </div>

    </div>


    <!-- ============================== -->
    <!-- KONTEN UTAMA -->
    <!-- ============================== -->
    <main class="pt-16 min-h-screen">