<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <!-- Google Fonts: Space Grotesk (Modern/Tech) & Archivo Black (Headlines) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Space+Grotesk:wght@400;500;700&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Tailwind Config for Custom Colors & Fonts -->
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: '#00d982', // Neon Green
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
                    animation: {
                        'marquee': 'marquee 25s linear infinite',
                    },
                    keyframes: {
                        marquee: {
                            '0%': { transform: 'translateX(0%)' },
                            '100%': { transform: 'translateX(-100%)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom Utilities for Brutalist Feel */
        body {
            background-color: #ffffff;
            color: #121212;
            overflow-x: hidden;
        }

        /* Dot Grid Pattern */
        .bg-grid-pattern {
            background-image: radial-gradient(#000000 2px, transparent 2px);
            background-size: 30px 30px;
        }

        .dark .bg-grid-pattern {
            background-image: radial-gradient(#00d982 2px, transparent 2px);
            background-size: 30px 30px;
        }

        /* Brutalist Scrollbar */
        ::-webkit-scrollbar {
            width: 16px;
        }

        ::-webkit-scrollbar-track {
            background: #ffffff;
            border-left: 4px solid #000;
        }

        ::-webkit-scrollbar-thumb {
            background: #00d982;
            border: 4px solid #000;
        }

        .dark ::-webkit-scrollbar-track {
            background: #121212;
            border-left: 4px solid #00d982;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #ffffff;
            border: 4px solid #00d982;
        }

        /* Selection Color */
        ::selection {
            background-color: #000;
            color: #00d982;
        }

        .dark ::selection {
            background-color: #00d982;
            color: #000;
        }

        /* Hard clip for jagged shapes */
        .clip-jagged {
            clip-path: polygon(0% 0%, 100% 0%, 100% 85%,
                    95% 85%, 95% 90%, 90% 90%, 90% 95%,
                    85% 95%, 85% 100%, 0% 100%);
        }

        /* Utility classes for border consistency in dark mode */
        .border-hard {
            @apply border-4 border-black dark:border-white;
        }
    </style>
</head>

<body class="transition-colors duration-300 dark:bg-dark dark:text-white">

    <!-- NAVIGATION -->
    <nav class="fixed top-0 w-full z-50 bg-white dark:bg-dark border-b-4 border-black dark:border-primary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="/" class="flex-shrink-0 flex items-center gap-2 group cursor-pointer decoration-none">
                    <div
                        class="w-10 h-10 bg-transparent dark:bg-transparent flex items-center justify-center border-2 border-transparent group-hover:rotate-12 transition-transform overflow-hidden">
                        <img src="/assets/logo.png" alt="Logo SI" class="w-full h-full object-contain p-1" />
                    </div>
                    <span class="font-display text-2xl tracking-tighter uppercase dark:text-white">TIK</span>
                </a>



                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/about"
                        class="font-bold text-lg hover:bg-primary hover:text-black px-2 py-1 transition-colors border-2 border-transparent hover:border-black dark:hover:border-white">ABOUT</a>

                    <!-- Theme Toggle -->
                    <button id="theme-toggle"
                        class="p-2 border-4 border-black dark:border-white shadow-brutal hover:shadow-brutal-hover hover:translate-x-[6px] hover:translate-y-[6px] transition-all bg-white dark:bg-dark text-black dark:text-white">
                        <i data-lucide="sun" class="hidden dark:block"></i>
                        <i data-lucide="moon" class="block dark:hidden"></i>
                    </button>

                    <!-- Login Button -->
                    <a href="/login"
                        class="px-6 py-2 bg-black text-primary font-display border-4 border-transparent hover:bg-white hover:text-black hover:border-black transition-all shadow-[4px_4px_0px_#00d982] hover:shadow-none dark:bg-primary dark:text-black dark:hover:bg-white">
                        Login
                    </a>
                </div>

                <!-- Mobile Button -->
                <div class="md:hidden flex items-center gap-4">
                    <button id="theme-toggle-mobile"
                        class="p-2 border-4 border-black dark:border-primary bg-white dark:bg-dark">
                        <i data-lucide="sun" class="hidden dark:block w-5 h-5"></i>
                        <i data-lucide="moon" class="block dark:hidden w-5 h-5"></i>
                    </button>
                    <button id="mobile-menu-btn"
                        class="p-2 border-4 border-black dark:border-white bg-primary text-black">
                        <i data-lucide="menu" class="w-8 h-8"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu Overlay -->
        <div id="mobile-menu"
            class="hidden md:hidden absolute w-full bg-white dark:bg-dark border-b-4 border-black dark:border-primary">
            <div class="px-4 pt-4 pb-8 space-y-4 flex flex-col">
                <a href="/about"
                    class="block px-4 py-4 text-2xl font-bold border-4 border-black dark:border-white hover:bg-primary hover:text-black text-center uppercase">About</a>
                <a href="/login"
                    class="block px-4 py-4 text-2xl font-bold bg-black text-primary dark:bg-primary dark:text-black border-4 border-transparent text-center uppercase">Login</a>
            </div>
        </div>
    </nav>