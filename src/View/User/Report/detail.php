<div class="min-h-screen bg-gray-50 dark:bg-[#121212] py-12 px-4 mt-24">

    <div class="max-w-4xl mx-auto">

        <?php if (!empty($report)) { ?>

            <!-- Header -->
            <div class="mb-8">

                <!-- Back -->
                <a href="/reports"
                    class="inline-flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300 hover:text-[#00d982] transition-colors mb-6">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Kembali ke Daftar Laporan
                </a>

                <!-- Title Card -->
                <div
                    class="bg-white dark:bg-[#1a1a1a] border-4 border-black dark:border-[#00d982] shadow-brutal dark:shadow-brutal-dark p-6 sm:p-8">

                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6">

                        <div>

                            <div class="flex items-center gap-2 mb-3">
                                <span
                                    class="inline-block px-3 py-1 bg-[#00d982] border-2 border-black text-xs font-black uppercase tracking-wider text-black">
                                    Daily Report
                                </span>

                                <span class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    ID #<?= $report->id ?>
                                </span>
                            </div>

                            <h1 class="font-black text-3xl sm:text-4xl text-black dark:text-white uppercase leading-tight">
                                <?= htmlspecialchars($formattedDate) ?>
                            </h1>

                            <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                                Dicatat oleh:
                                <span class="font-bold text-black dark:text-white">
                                    <?= htmlspecialchars($creatorName) ?>
                                </span>
                            </p>

                        </div>

                        <!-- Icon -->
                        <div
                            class="w-14 h-14 shrink-0 bg-black dark:bg-[#00d982] text-[#00d982] dark:text-black border-4 border-black dark:border-[#00d982] flex items-center justify-center">
                            <i data-lucide="clipboard-list" class="w-7 h-7"></i>
                        </div>

                    </div>

                </div>
            </div>

         

            <!-- Activity Section -->
            <div class="bg-[#00d982] border-4 border-black shadow-brutal p-5 sm:p-6 mb-8">

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-5">

                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <i data-lucide="list-checks" class="w-5 h-5 text-black"></i>

                            <h2 class="font-black text-lg uppercase text-black">
                                Daftar Kegiatan / Giat
                            </h2>
                        </div>

                        <p class="text-sm font-medium text-black/70">
                            Kelola seluruh rincian penugasan harian pada tanggal ini.
                        </p>
                    </div>

                    <a href="/report/item/<?= $report->reportDate->format('Y-m-d') ?>/add" class="inline-flex items-center justify-center gap-2 px-5 py-3
                        bg-black text-white border-4 border-black
                        font-black text-sm uppercase
                        shadow-[4px_4px_0px_0px_white]
                        hover:translate-x-1 hover:translate-y-1
                        hover:shadow-none transition-all">

                        <i data-lucide="plus" class="w-5 h-5"></i>
                        Tambah Kegiatan

                    </a>

                </div>

            </div>


            <!-- Activity List -->
            <div
                class="bg-white dark:bg-[#1a1a1a] border-4 border-black dark:border-[#00d982] shadow-brutal dark:shadow-brutal-dark">

                <!-- Section Header -->
                <div
                    class="px-5 sm:px-6 py-4 border-b-4 border-black dark:border-[#00d982] bg-black text-white flex items-center justify-between">

                    <div class="flex items-center gap-2">
                        <i data-lucide="clipboard-check" class="w-5 h-5 text-[#00d982]"></i>

                        <h3 class="font-black uppercase text-sm tracking-wide">
                            Kegiatan Laporan
                        </h3>
                    </div>

                    <span class="px-2 py-1 bg-[#00d982] text-black border-2 border-black text-xs font-black">
                        <?= !empty($activities) ? count($activities) : 0 ?>
                    </span>

                </div>


                <?php if (!empty($activities)) { ?>

                    <div class="divide-y-4 divide-black dark:divide-[#00d982]">

                        <?php foreach ($activities as $data) { ?>

                            <div class="p-5 sm:p-6 bg-white dark:bg-[#111]">

                                <div class="flex items-start gap-4">

                                    <!-- Mengambil itemNo dari objek asli Domain di dalam array 'item' -->
                                    <div
                                        class="w-10 h-10 shrink-0 bg-[#00d982] border-4 border-black flex items-center justify-center font-black text-black">
                                        <?= htmlspecialchars($data['item']->itemNo ?? '-') ?>
                                    </div>

                                    <div class="flex-1 space-y-3">
                                        <!-- Bagian Judul dan Tombol Aksi Kanan -->
                                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                                            <div>
                                                <p class="font-black text-black dark:text-white uppercase tracking-wide">
                                                    Giat #<?= htmlspecialchars($data['item']->itemNo ?? '-') ?> :
                                                    <?= htmlspecialchars($data['activityName']) ?>
                                                </p>
                                            </div>

                                            <!-- TOMBOL INTEGRASI EDIT & DELETE (GAYA NEOBRUTALISM) -->
                                            <div class="flex items-center gap-2">
                                                <!-- Tombol Edit -->
                                                <a href="/report/item/edit/<?= $data['item']->id ?>"
                                                    class="px-3 py-1 bg-yellow-400 text-black border-2 border-black font-black uppercase text-xs shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all">
                                                    Edit
                                                </a>

                                                <!-- Tombol Hapus Form -->
                                                <form action="/report/item/delete/<?= $data['item']->id ?>" method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus giat ini?');"
                                                    class="inline">
                                                    <button type="submit"
                                                        class="px-3 py-1 bg-red-500 text-white border-2 border-black font-black uppercase text-xs shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] hover:translate-x-[1px] hover:translate-y-[1px] hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Grid Rincian Teks Opsi dari array gabungan -->
                                        <div
                                            class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm border-2 border-black p-4 bg-gray-50 dark:bg-[#222]">
                                            <div>
                                                <span class="block text-xs font-bold text-gray-400 uppercase">Sasaran /
                                                    Target:</span>
                                                <span
                                                    class="font-bold text-black dark:text-white"><?= htmlspecialchars($data['targetName']) ?></span>
                                            </div>
                                            <div>
                                                <span class="block text-xs font-bold text-gray-400 uppercase">Lokasi Giat:</span>
                                                <span
                                                    class="font-bold text-black dark:text-white"><?= htmlspecialchars($data['locationName']) ?></span>
                                            </div>
                                            <div>
                                                <span class="block text-xs font-bold text-gray-400 uppercase">Kuat Personel:</span>
                                                <span
                                                    class="font-bold text-black dark:text-white"><?= htmlspecialchars($data['personnelName']) ?></span>
                                            </div>
                                            <div>
                                                <span class="block text-xs font-bold text-gray-400 uppercase">Penanggung
                                                    Jawab:</span>
                                                <span
                                                    class="font-bold text-black dark:text-white"><?= htmlspecialchars($data['picName']) ?></span>
                                            </div>
                                            <div class="sm:col-span-2 border-t border-dashed border-gray-300 pt-2">
                                                <span class="block text-xs font-bold text-gray-400 uppercase">Hasil
                                                    Diharapkan:</span>
                                                <span
                                                    class="font-bold text-black dark:text-white"><?= htmlspecialchars($data['expectedResultName']) ?></span>
                                            </div>
                                        </div>

                                        <!-- Uraian Remarks dari Objek Domain asli -->
                                        <div class="border-l-4 border-black dark:border-[#00d982] pl-3 py-1">
                                            <span class="block text-xs font-bold text-gray-400 uppercase">Uraian / Keterangan
                                                Aktivitas:</span>
                                            <p class="text-sm text-gray-700 dark:text-gray-300 mt-0.5 whitespace-pre-line">
                                                <?= htmlspecialchars($data['item']->remarks ?: '(Tidak ada keterangan)') ?>
                                            </p>
                                        </div>
                                    </div>

                                </div>

                            </div>

                        <?php } ?>

                    </div>

                <?php } else { ?>

                    <!-- Empty State -->
                    <div class="py-16 px-6 text-center">

                        <div
                            class="w-16 h-16 mx-auto bg-gray-100 dark:bg-[#222] border-4 border-black dark:border-[#00d982] flex items-center justify-center">
                            <i data-lucide="clipboard-x" class="w-8 h-8 text-gray-400"></i>
                        </div>

                        <h3 class="mt-5 font-black text-lg uppercase text-black dark:text-white">
                            Belum Ada Kegiatan
                        </h3>

                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 max-w-md mx-auto">
                            Belum ada daftar kegiatan yang diinput untuk hari ini.
                        </p>

                        <a href="/report/item/<?= $report->reportDate->format('Y-m-d') ?>/add" class="inline-flex items-center gap-2 mt-6 px-5 py-3
                            bg-[#00d982] text-black
                            border-4 border-black
                            font-black uppercase text-sm
                            shadow-brutal
                            hover:translate-x-1 hover:translate-y-1
                            hover:shadow-brutal-hover transition-all">

                            <i data-lucide="plus" class="w-5 h-5"></i>
                            Tambah Kegiatan

                        </a>

                    </div>

                <?php } ?>

            </div>


        <?php } else { ?>

            <!-- Not Found -->
            <div class="bg-white dark:bg-[#1a1a1a]
                border-4 border-black dark:border-[#00d982]
                shadow-brutal dark:shadow-brutal-dark
                p-8 sm:p-12 text-center">

                <div class="w-16 h-16 mx-auto bg-red-100 border-4 border-black flex items-center justify-center">
                    <i data-lucide="file-x-2" class="w-8 h-8 text-red-600"></i>
                </div>

                <h2 class="mt-6 font-black text-xl uppercase text-black dark:text-white">
                    Laporan Tidak Ditemukan
                </h2>

                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Maaf, data laporan tidak ditemukan atau telah dihapus.
                </p>

                <a href="/reports" class="inline-flex items-center gap-2 mt-6 px-5 py-3
                    bg-[#00d982] text-black
                    border-4 border-black
                    font-black uppercase text-sm
                    shadow-brutal
                    hover:translate-x-1 hover:translate-y-1
                    hover:shadow-brutal-hover transition-all">

                    <i data-lucide="arrow-left" class="w-5 h-5"></i>
                    Kembali ke Daftar Laporan

                </a>

            </div>

        <?php } ?>

    </div>
</div>