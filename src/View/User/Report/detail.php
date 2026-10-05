<div class="min-h-screen bg-gray-50 dark:bg-[#121212] pt-12 pb-16 px-4 sm:px-6 lg:px-8">

    <div class="max-w-6xl mx-auto">

        <?php

        use Unirow2026\DailyReportSitikPolrestaTuban\App\View;

        if (!empty($_SESSION['flash_message'])): ?>

            <div id="flash-message" class="mb-8 p-5 bg-[#00d982] border-4 border-[#121212]
               shadow-[6px_6px_0_0_#121212] flex items-start gap-4
               transition-all duration-200" role="alert">

                <div class="flex-shrink-0 w-9 h-9 flex items-center justify-center
                    bg-[#121212] border-2 border-[#121212]">
                    <svg class="w-5 h-5 text-[#00d982]" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                            clip-rule="evenodd" />
                    </svg>
                </div>

                <div class="flex-1 min-w-0">
                    <p class="font-black uppercase text-sm text-[#121212]">Berhasil</p>
                    <p class="mt-1 font-bold text-sm text-[#121212] break-words">
                        <?= htmlspecialchars($_SESSION['flash_message']) ?>
                    </p>
                </div>

                <button type="button" id="flash-close" class="relative z-10 flex-shrink-0 w-8 h-8 flex items-center justify-center
                   bg-[#121212] text-[#00d982] border-2 border-[#121212]
                   hover:bg-white hover:text-[#121212] transition-colors cursor-pointer" aria-label="Tutup notifikasi">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 6l12 12M6 18L18 6" />
                    </svg>
                </button>
            </div>

            <?php View::clearFlashMessage(); ?>

            <script>
                (function () {
                    var flash = document.getElementById('flash-message');
                    var btn = document.getElementById('flash-close');
                    if (!flash || !btn) return;

                    // Hindari double-binding jika file ini di-include 2x
                    if (btn.dataset.bound === '1') return;
                    btn.dataset.bound = '1';

                    btn.addEventListener('click', function (e) {
                        e.preventDefault();
                        flash.style.opacity = '0';
                        flash.style.transform = 'translateY(-10px)';
                        setTimeout(function () {
                            if (flash.parentNode) flash.parentNode.removeChild(flash);
                        }, 200);
                    });
                })();
            </script>

        <?php endif; ?>

        <?php if (!empty($report)) { ?>

            <!-- Header -->
            <div class="mb-6">

                <!-- Breadcrumb -->
                <nav aria-label="Breadcrumb" class="mb-6 pl-2">
                    <ol class="flex flex-wrap items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300">

                        <!-- Laporan -->
                        <li>
                            <a href="/reports" class="hover:text-[#00d982] transition-colors">
                                Laporan
                            </a>
                        </li>

                        <!-- Separator -->
                        <li aria-hidden="true" class="text-gray-400 dark:text-gray-600 flex items-center">
                            <i data-lucide="chevron-right" class="w-4 h-4"></i>
                        </li>

                        <!-- Current Page -->
                        <li>
                            <span aria-current="page" class="text-black dark:text-white">
                                <?= htmlspecialchars($formattedDate) ?> · Detail Giat
                            </span>
                        </li>

                    </ol>
                </nav>

                <!-- Title Card -->
                <div class="bg-white dark:bg-[#1a1a1a] border-4 border-black dark:border-[#00d982]
                shadow-brutal dark:shadow-brutal-dark p-6 sm:p-8">

                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-6">

                        <!-- Kiri: Info -->
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="inline-block px-3 py-1 bg-[#00d982] border-2 border-black
                                 text-xs font-black uppercase tracking-wider text-black">
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

                        <!-- Kanan: Tombol Tambah Kegiatan -->
                        <!-- Kanan: Aksi -->
                        <div class="shrink-0 flex flex-col gap-3 w-full sm:w-auto">

                            <!-- Tombol Tambah Kegiatan -->
                            <a href="/report/item/<?= $report->reportDate->format('Y-m-d') ?>/add" class="inline-flex items-center justify-center gap-2
               px-5 py-3 bg-black text-[#00d982] dark:bg-[#00d982] dark:text-black
               border-4 border-black dark:border-[#00d982]
               font-black uppercase text-sm
               shadow-[4px_4px_0_0_#00d982] dark:shadow-[4px_4px_0_0_#121212]
               hover:translate-x-1 hover:translate-y-1 hover:shadow-none
               transition-all">
                                <i data-lucide="plus" class="w-5 h-5"></i>
                                Tambah Kegiatan
                            </a>

                            <!-- Tombol Cetak Laporan -->
                            <a href="/report/print/pdf/<?= $report->reportDate->format('Y-m-d') ?>" target="_blank" class="inline-flex items-center justify-center gap-2
               px-5 py-3 bg-white text-black dark:bg-[#1a1a1a] dark:text-white
               border-4 border-black dark:border-white
               font-black uppercase text-sm
               shadow-[4px_4px_0_0_#121212] dark:shadow-[4px_4px_0_0_#00d982]
               hover:translate-x-1 hover:translate-y-1 hover:shadow-none
               transition-all">
                                <i data-lucide="printer" class="w-5 h-5"></i>
                                Cetak Laporan
                            </a>

                        </div>

                    </div>
                </div>
            </div>

            <!-- Activity Section DIHAPUS -->


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
                                                    Sasaran #<?= htmlspecialchars($data['item']->itemNo ?? '-') ?> :
                                                    <?= htmlspecialchars($data['targetName']) ?>
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
                                                    class="delete-form inline">
                                                    <button type="button" class="delete-btn px-3 py-1 bg-red-500 text-white border-2 border-black 
               font-black uppercase text-xs 
               shadow-[2px_2px_0px_0px_rgba(0,0,0,1)] 
               hover:translate-x-[1px] hover:translate-y-[1px] 
               hover:shadow-[1px_1px_0px_0px_rgba(0,0,0,1)] transition-all"
                                                        data-item-no="<?= htmlspecialchars($data['item']->itemNo ?? '-') ?>"
                                                        data-activity="<?= htmlspecialchars($data['activityName']) ?>">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Grid Rincian Teks Opsi dari array gabungan -->
                                        <div
                                            class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm border-2 border-black p-4 bg-gray-50 dark:bg-[#222]">
                                            <div>
                                                <span class="block text-xs font-bold text-gray-400 uppercase">
                                                    Kegiatan:</span>
                                                <span
                                                    class="font-bold text-black dark:text-white"><?= htmlspecialchars($data['activityName']) ?></span>
                                            </div>
                                            <div>
                                                <span class="block text-xs font-bold text-gray-400 uppercase">Kuat Personel:</span>
                                                <span
                                                    class="font-bold text-black dark:text-white"><?= htmlspecialchars($data['personnelName']) ?></span>
                                            </div>
                                            <div>
                                                <span class="block text-xs font-bold text-gray-400 uppercase">Lokasi Giat:</span>
                                                <span
                                                    class="font-bold text-black dark:text-white"><?= htmlspecialchars($data['locationName']) ?></span>
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

<!-- ============================== -->
<!-- MODAL KONFIRMASI HAPUS GIAT -->
<!-- ============================== -->
<div id="delete-item-modal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4
           bg-black/70 backdrop-blur-sm">

    <div id="delete-item-modal-content" class="w-full max-w-md bg-white dark:bg-[#181818]
               border-4 border-[#121212] dark:border-white
               shadow-[8px_8px_0_0_#121212] dark:shadow-[8px_8px_0_0_#00d982]
               transform scale-95 transition-transform duration-200">

        <!-- Header -->
        <div class="flex items-center gap-3 p-5 border-b-4 border-[#121212] dark:border-white">
            <div class="w-10 h-10 flex items-center justify-center
                        bg-red-500 border-2 border-[#121212]">
                <svg class="w-5 h-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <h3 class="font-black uppercase text-lg text-[#121212] dark:text-white">
                Konfirmasi Hapus Giat
            </h3>
        </div>

        <!-- Body -->
        <div class="p-5">
            <p class="text-sm font-bold text-[#121212] dark:text-white">
                Yakin ingin menghapus giat:
            </p>

            <p id="delete-item-modal-label" class="mt-2 px-3 py-2 bg-gray-100 dark:bg-[#222]
                       border-2 border-[#121212] dark:border-white
                       font-black text-sm text-[#121212] dark:text-white">
                -
            </p>

            <p class="mt-3 text-xs font-bold text-red-600 dark:text-red-400 uppercase">
                Peringatan: rincian kegiatan ini akan dihapus permanen.
            </p>
        </div>

        <!-- Actions -->
        <div class="flex gap-3 p-5 border-t-4 border-[#121212] dark:border-white">
            <button type="button" id="delete-item-cancel" class="flex-1 px-4 py-3 bg-gray-200 dark:bg-[#222] text-[#121212] dark:text-white
                       border-4 border-[#121212] dark:border-white
                       font-black uppercase text-sm
                       shadow-[4px_4px_0_0_#121212] dark:shadow-[4px_4px_0_0_#00d982]
                       hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px]
                       transition-all">
                Batal
            </button>
            <button type="button" id="delete-item-confirm" class="flex-1 px-4 py-3 bg-red-500 text-white
                       border-4 border-[#121212]
                       font-black uppercase text-sm
                       shadow-[4px_4px_0_0_#121212]
                       hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px]
                       transition-all">
                Ya, Hapus
            </button>
        </div>

    </div>
</div>

<script>
    // ==============================
    // Modal Konfirmasi Hapus Giat
    // ==============================
    (() => {
        const modal = document.getElementById('delete-item-modal');
        const modalContent = document.getElementById('delete-item-modal-content');
        const modalLabel = document.getElementById('delete-item-modal-label');
        const btnCancel = document.getElementById('delete-item-cancel');
        const btnConfirm = document.getElementById('delete-item-confirm');

        if (!modal) return;

        let pendingForm = null;

        function openModal(form, label) {
            pendingForm = form;
            modalLabel.textContent = label;
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';

            requestAnimationFrame(() => {
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            });
        }

        function closeModal() {
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            document.body.style.overflow = '';
            pendingForm = null;

            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }

        // Buka modal saat tombol hapus diklik
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                const form = btn.closest('.delete-form');
                const itemNo = btn.dataset.itemNo || '-';
                const activity = btn.dataset.activity || 'Giat ini';
                openModal(form, `Giat #${itemNo} — ${activity}`);
            });
        });

        btnCancel.addEventListener('click', closeModal);

        // Klik backdrop → tutup
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });

        // ESC → tutup
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });

        // Konfirmasi hapus
        btnConfirm.addEventListener('click', () => {
            if (pendingForm) pendingForm.submit();
        });
    })();
</script>