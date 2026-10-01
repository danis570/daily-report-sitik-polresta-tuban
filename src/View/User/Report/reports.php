<div class="min-h-screen bg-gray-50 dark:bg-[#121212] pt-24 pb-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="mb-10">
            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">

                <div>
                    <div class="inline-flex items-center gap-2 mb-4">
                        <span class="w-3 h-3 bg-[#00d982] border-2 border-black"></span>
                        <span class="text-xs font-black uppercase tracking-[0.2em] text-gray-600 dark:text-gray-400">
                            Daily Report
                        </span>
                    </div>

                    <h1
                        class="font-black text-4xl sm:text-5xl uppercase tracking-tight text-[#121212] dark:text-white leading-none">
                        <?= htmlspecialchars($title ?? 'Kelola Laporan Harian') ?>
                    </h1>

                    <p class="mt-3 text-sm sm:text-base font-medium text-gray-600 dark:text-gray-400 max-w-2xl">
                        Daftar rekapitulasi laporan harian SITIK Polresta Tuban.
                    </p>
                </div>

                <!-- Container Tombol -->
                <div class="flex flex-wrap items-center gap-4">

                    <!-- Tombol Tambah Laporan -->
                    <a href="/report/add" class="inline-flex items-center justify-center gap-2 px-5 py-3 
                                bg-[#00d982] text-[#121212] font-black uppercase text-sm 
                                border-4 border-[#121212] 
                                shadow-[6px_6px_0_0_#121212] hover:shadow-none 
                                hover:translate-x-[6px] hover:translate-y-[6px] 
                                transition-all duration-150">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        <span>Tambah Laporan</span>
                    </a>

                    <!-- Tombol Opsi Jawaban -->
                    <a href="/report/options" class="inline-flex items-center justify-center gap-2 px-5 py-3 
                                bg-[#00d982] text-[#121212] font-black uppercase text-sm 
                                border-4 border-[#121212] 
                                shadow-[6px_6px_0_0_#121212] hover:shadow-none 
                                hover:translate-x-[6px] hover:translate-y-[6px] 
                                transition-all duration-150">

                        <!-- Menambahkan icon penyesuaian agar seimbang dengan tombol pertama -->
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Opsi Jawaban</span>
                    </a>

                </div>

            </div>

            <!-- Garis Brutalist -->
            <div class="mt-8 border-b-4 border-[#121212] dark:border-white"></div>
        </div>


        <!-- Error -->
        <?php if (!empty($error)) { ?>
            <div class="mb-8 p-5 bg-yellow-300 border-4 border-[#121212]
                        shadow-[6px_6px_0_0_#121212]" role="alert">

                <div class="flex items-start gap-4">

                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-[#121212]" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>

                    <div>
                        <p class="font-black uppercase text-sm">
                            Miss
                        </p>

                        <p class="mt-1 font-bold text-sm">
                            <?= htmlspecialchars($error) ?>
                        </p>
                    </div>

                </div>
            </div>
        <?php } ?>


        <!-- Daftar Report -->
        <div class="space-y-7">

            <?php if (!empty($reports)) { ?>

                <?php foreach ($reports as $item) { ?>

                    <!-- Report Card Container -->
                    <div class="block bg-white dark:bg-[#181818]
           border-4 border-[#121212] dark:border-white
           shadow-[6px_6px_0_0_#121212]
           dark:shadow-[6px_6px_0_0_#00d982]
           hover:shadow-none
           hover:translate-x-[6px]
           hover:translate-y-[6px]
           transition-all duration-150 mb-6">

                        <div class="p-6 sm:p-7">

                            <!-- Card Header -->
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-5">

                                <!-- Sisi Kiri: Informasi Data Laporan -->
                                <div class="min-w-0 flex-1">

                                    <!-- Label -->
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="inline-block px-3 py-1
                                 bg-[#121212] text-[#00d982]
                                 border-2 border-[#121212]
                                 font-black text-[10px]
                                 uppercase tracking-widest">
                                            LAPORAN HARIAN
                                        </span>

                                        <span class="text-xs font-bold text-gray-500 dark:text-gray-400">
                                            #<?= (int) $item['report']->id ?>
                                        </span>
                                    </div>

                                    <!-- Date -->
                                    <h2 class="font-black text-2xl sm:text-3xl
                           uppercase tracking-tight
                           text-[#121212] dark:text-white
                           transition-colors">
                                        <?= htmlspecialchars($item['formattedDate']) ?>
                                    </h2>

                                    <!-- Creator -->
                                    <div class="mt-3 flex items-center gap-2
                            text-sm font-bold
                            text-gray-600 dark:text-gray-400">

                                        <div class="w-8 h-8 flex items-center justify-center
                                bg-[#00d982]
                                border-2 border-[#121212]">
                                            <svg class="w-4 h-4 text-[#121212]" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                </path>
                                            </svg>
                                        </div>

                                        <span>
                                            Oleh:
                                            <span class="text-[#121212] dark:text-white font-black">
                                                <?= htmlspecialchars($item['creatorName']) ?>
                                            </span>
                                        </span>
                                    </div>

                                </div>
                                <div class="flex flex-col items-stretch sm:items-end gap-2 shrink-0 w-full sm:w-auto">

                                    <!-- Baris Atas: Edit, Cetak & Hapus -->
                                    <div class="flex items-center gap-2 w-full sm:w-auto">

                                        <!-- Tombol Edit -->
                                        <a href="/report/edit/<?= $item['report']->id ?>"
                                            class="flex-1 sm:flex-none text-center px-3 py-1.5
                                                bg-yellow-400 text-black
                                                border-2 border-black
                                                font-black uppercase text-xs
                                                shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]
                                                hover:translate-x-[1px]
                                                hover:translate-y-[1px]
                                                hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)]
                                                transition-all">
                                            Edit
                                        </a>

                                        <!-- Tombol Cetak PDF -->
                                        <a href="/report/print/pdf/<?= $item['report']->reportDate->format('Y-m-d') ?>"
                                            target="_blank"
                                            class="flex-1 sm:flex-none text-center px-3 py-1.5
                                                bg-[#00d982] text-black
                                                border-2 border-black
                                                font-black uppercase text-xs
                                                shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]
                                                hover:translate-x-[1px]
                                                hover:translate-y-[1px]
                                                hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)]
                                                transition-all">

                                            <span class="inline-flex items-center gap-1">
                                                <i data-lucide="printer" class="w-4 h-4"></i>
                                                Cetak
                                            </span>

                                        </a>

                                        <!-- Tombol Hapus -->
                                        <form action="/report/delete/<?= $item['report']->id ?>"
                                            method="POST"
                                            onsubmit="return confirm('PERINGATAN: Menghapus laporan ini akan menghapus seluruh rincian kegiatan di dalamnya! Hapus?');"
                                            class="flex-1 sm:flex-none inline">

                                            <button type="submit"
                                                class="w-full text-center px-3 py-1.5
                                                    bg-red-500 text-white
                                                    border-2 border-black
                                                    font-black uppercase text-xs
                                                    shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]
                                                    hover:translate-x-[1px]
                                                    hover:translate-y-[1px]
                                                    hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)]
                                                    transition-all">
                                                Hapus
                                            </button>

                                        </form>

                                    </div>

                                    <!-- Baris Bawah: Detail -->
                                    <a href="/report/<?= $item['report']->reportDate->format('Y-m-d') ?>"
                                        class="w-full sm:w-12 h-10 flex items-center justify-center
                                            bg-[#00d982] text-[#121212]
                                            border-2 border-[#121212]
                                            shadow-[2px_2px_0px_0px_rgba(0,0,0,1)]
                                            hover:translate-x-0.5
                                            hover:-translate-y-0.5
                                            transition-transform">

                                        <svg class="w-5 h-5"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">

                                            <path stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="3"
                                                d="M9 5l7 7-7 7">
                                            </path>

                                        </svg>

                                    </a>

                                </div>
                            </div>

                            <!-- Divider -->
                            <div class="my-6 border-t-2 border-dashed border-gray-300 dark:border-gray-700"></div>

                            <!-- Metadata Bawah -->
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-center gap-2">
                                    <span
                                        class="px-3 py-1.5 bg-gray-100 dark:bg-[#222] border-2 border-[#121212] dark:border-gray-600 text-xs font-black text-[#121212] dark:text-white">
                                        REPORT ID #<?= (int) $item['report']->id ?>
                                    </span>
                                </div>

                                <div class="flex items-center gap-2 text-xs font-bold text-gray-500 dark:text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>
                                        <?= $item['report']->createdAt->format('H:i:s') ?> WIB
                                    </span>
                                </div>
                            </div>

                        </div>

                    </div>

                <?php } ?>

            <?php } else { ?>

                <!-- Empty State -->
                <div class="bg-white dark:bg-[#181818]
                            border-4 border-dashed
                            border-[#121212] dark:border-gray-600
                            p-10 sm:p-16
                            text-center">

                    <div class="mx-auto w-20 h-20
                                flex items-center justify-center
                                bg-[#00d982]
                                border-4 border-[#121212]
                                shadow-[5px_5px_0_0_#121212]">

                        <svg class="w-10 h-10 text-[#121212]" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>

                        </svg>

                    </div>

                    <h3 class="mt-7 font-black text-2xl uppercase
                               text-[#121212] dark:text-white">

                        Belum Ada Laporan

                    </h3>

                    <p class="mt-2 text-sm font-medium
                              text-gray-500 dark:text-gray-400">

                        Silakan tambahkan laporan harian baru
                        melalui tombol di atas.

                    </p>

                    <a href="/report/add" class="inline-flex items-center gap-2
                               mt-6 px-5 py-3
                               bg-[#121212] text-white
                               border-4 border-[#121212]
                               font-black uppercase text-sm
                               shadow-[5px_5px_0_0_#00d982]
                               hover:shadow-none
                               hover:translate-x-[5px]
                               hover:translate-y-[5px]
                               transition-all duration-150">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4">
                            </path>

                        </svg>

                        Buat Laporan

                    </a>

                </div>

            <?php } ?>

        </div>

    </div>
</div>