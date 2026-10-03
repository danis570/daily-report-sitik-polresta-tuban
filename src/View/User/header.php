<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Archivo+Black&family=Space+Grotesk:wght@400;500;700&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Tailwind Config -->
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
    <!-- SIDEBAR -->
    <!-- ============================== -->
    <aside id="sidebar" class="fixed top-0 left-0 z-50 h-screen w-72 bg-white dark:bg-dark
               border-r-4 border-black dark:border-primary
               flex flex-col transform -translate-x-full lg:translate-x-0
               transition-transform duration-300">

        <!-- Logo -->
        <div class="h-20 flex items-center gap-3 px-6 border-b-4 border-black dark:border-primary shrink-0">
            <a href="/" class="flex items-center gap-2 group">
                <div class="w-10 h-10 flex items-center justify-center border-2 border-transparent
                            group-hover:rotate-12 transition-transform overflow-hidden">
                    <img src="/assets/logo.png" alt="Logo SI" class="w-full h-full object-contain p-1" />
                </div>
                <span class="font-display text-2xl tracking-tighter uppercase dark:text-white">TIK</span>
            </a>

            <button id="sidebar-close" class="lg:hidden ml-auto p-1 border-2 border-black dark:border-white">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Menu -->
        <nav class="flex-1 overflow-y-auto p-4 space-y-3">

            <p class="px-2 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 dark:text-gray-400">
                Menu Utama
            </p>

            <!-- Laporan -->
            <a href="/reports"
                class="flex items-center gap-3 px-4 py-3 font-black uppercase text-sm border-4 transition-all
                    <?= $current === 'report'
                        ? 'bg-primary text-black border-black shadow-[4px_4px_0_0_#121212]'
                        : 'border-transparent hover:bg-primary hover:text-black hover:border-black dark:text-white dark:hover:border-white' ?>">
                <i data-lucide="file-text" class="w-5 h-5 shrink-0"></i>
                <span>Laporan</span>
            </a>

            <!-- Tambah -->
            <a href="/report/add"
                class="flex items-center gap-3 px-4 py-3 font-black uppercase text-sm border-4 transition-all
                    <?= $current === 'add'
                        ? 'bg-primary text-black border-black shadow-[4px_4px_0_0_#121212]'
                        : 'border-transparent hover:bg-primary hover:text-black hover:border-black dark:text-white dark:hover:border-white' ?>">
                <i data-lucide="plus-square" class="w-5 h-5 shrink-0"></i>
                <span>Tambah</span>
            </a>

            <!-- Lacak -->
            <a href="/report/tracking"
                class="flex items-center gap-3 px-4 py-3 font-black uppercase text-sm border-4 transition-all
                    <?= $current === 'tracking'
                        ? 'bg-primary text-black border-black shadow-[4px_4px_0_0_#121212]'
                        : 'border-transparent hover:bg-primary hover:text-black hover:border-black dark:text-white dark:hover:border-white' ?>">
                <i data-lucide="search" class="w-5 h-5 shrink-0"></i>
                <span>Lacak</span>
            </a>

            <!-- Opsi -->
            <a href="/report/options"
                class="flex items-center gap-3 px-4 py-3 font-black uppercase text-sm border-4 transition-all
                    <?= $current === 'options'
                        ? 'bg-primary text-black border-black shadow-[4px_4px_0_0_#121212]'
                        : 'border-transparent hover:bg-primary hover:text-black hover:border-black dark:text-white dark:hover:border-white' ?>">
                <i data-lucide="settings" class="w-5 h-5 shrink-0"></i>
                <span>Opsi</span>
            </a>

            <!-- Akun -->
            <div class="pt-4 mt-4 border-t-2 border-dashed border-gray-300 dark:border-gray-700">
                <p class="px-2 mb-3 text-[10px] font-black uppercase tracking-[0.2em] text-gray-500 dark:text-gray-400">
                    Akun
                </p>

                <!-- Profile -->
                <a href="/profile"
                    class="flex items-center gap-3 px-4 py-3 font-black uppercase text-sm border-4 transition-all
                        <?= $current === 'profile'
                            ? 'bg-primary text-black border-black shadow-[4px_4px_0_0_#121212]'
                            : 'border-transparent hover:bg-primary hover:text-black hover:border-black dark:text-white dark:hover:border-white' ?>">
                    <i data-lucide="user" class="w-5 h-5 shrink-0"></i>
                    <span>Profile</span>
                </a>
            </div>
        </nav>

        <!-- User Card + Logout -->
        <div class="shrink-0 p-4 border-t-4 border-black dark:border-primary space-y-3">

            <div
                class="flex items-center gap-3 p-3 border-4 border-black dark:border-white bg-gray-50 dark:bg-[#181818]">
                <div class="w-10 h-10 shrink-0 overflow-hidden rounded-full border-2 border-black dark:border-white">
                    <?php if ($currentProfile->avatar): ?>
                        <img src="/uploads/avatar/<?= htmlspecialchars($currentProfile->avatar) ?>" alt="Avatar"
                            class="w-full h-full object-cover">
                    <?php else: ?>
                        <img src="/uploads/avatar/default-avatar.png" alt="Default Avatar"
                            class="w-full h-full object-cover">
                    <?php endif; ?>
                </div>
                <div class="min-w-0">
                    <p class="font-black text-xs truncate dark:text-white">
                        <?= htmlspecialchars($currentProfile->name ?? 'Username') ?>
                    </p>
                    <p class="text-[10px] truncate text-gray-500 dark:text-gray-400">
                        <?= htmlspecialchars($currentUser->email) ?>
                    </p>
                </div>
            </div>

            <div class="flex gap-2">
                <button id="theme-toggle" class="flex-1 p-3 border-4 border-black
           bg-white dark:bg-dark
           hover:bg-primary hover:text-black
           dark:hover:bg-primary dark:hover:text-black
           transition-all text-black dark:text-white">
                    <i data-lucide="sun" class="hidden dark:block w-5 h-5 mx-auto"></i>
                    <i data-lucide="moon" class="block dark:hidden w-5 h-5 mx-auto"></i>
                </button>
                <a href="/logout" class="flex-1 flex items-center justify-center gap-2 p-3 bg-black text-primary
                           border-4 border-black font-black uppercase text-xs
                           hover:bg-primary hover:text-black transition-all
                           dark:bg-primary dark:text-black dark:hover:bg-white">
                    <i data-lucide="log-out" class="w-4 h-4"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>
    </aside>

    <!-- Overlay mobile -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/60 z-40 hidden lg:hidden"></div>

    <!-- ============================== -->
    <!-- MOBILE TOP BAR -->
    <!-- ============================== -->
    <header class="lg:hidden fixed top-0 left-0 right-0 z-30 h-16
               bg-white dark:bg-dark
               border-b-4 border-black dark:border-primary
               flex items-center justify-between px-4">

        <!-- Kiri: Logo + TIK -->
        <a href="/" class="flex items-center gap-2 group">
            <div class="w-10 h-10 flex items-center justify-center
                    border-2 border-transparent
                    group-hover:rotate-12 transition-transform overflow-hidden">
                <img src="/assets/logo.png" alt="Logo SI" class="w-full h-full object-contain p-1" />
            </div>
            <span class="font-display text-xl uppercase tracking-tighter
                     text-black dark:text-white">
                TIK
            </span>
        </a>

        <!-- Kanan: Tombol Menu (style neobrutalism hijau) -->
        <button id="sidebar-open" class="w-9 h-9 flex items-center justify-center
           bg-[#00d982] text-black
           border-2 border-black
           shadow-[3px_3px_0px_0px_#000]
           hover:translate-x-[3px] hover:translate-y-[3px]
           hover:shadow-none
           transition-all duration-150
           cursor-pointer">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>

    </header>

    <!-- ============================== -->
    <!-- KONTEN UTAMA -->
    <!-- ============================== -->
    <main class="lg:ml-72 pt-16 lg:pt-0 min-h-screen flex flex-col">
        <div class="flex-1">