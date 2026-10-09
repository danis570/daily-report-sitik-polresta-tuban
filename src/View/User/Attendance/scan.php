<div class="min-h-screen bg-gray-50 dark:bg-[#121212] pt-12 pb-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-6xl mx-auto">

        <!-- Header -->
        <div class="mb-6">
            <h1
                class="font-black text-4xl sm:text-5xl uppercase tracking-tight leading-none text-[#121212] dark:text-white">
                Absensi
            </h1>
            <p class="mt-3 text-sm sm:text-base font-medium text-gray-600 dark:text-gray-400">
                Scan QR kantor untuk absen masuk & keluar.
            </p>
        </div>

        <!-- Status Hari Ini -->
        <div class="mb-6 p-5 border-4 border-[#121212] dark:border-white
                    bg-white dark:bg-[#181818]
                    shadow-[6px_6px_0_0_#121212] dark:shadow-[6px_6px_0_0_#00d982]">

            <p class="text-xs font-black uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-2">
                Status Hari Ini
            </p>

            <?php if ($todayStatus['state'] === 'none'): ?>
                <p class="font-black text-xl text-[#121212] dark:text-white">
                    Belum absen
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Scan QR di kantor untuk absen masuk.
                </p>

            <?php elseif ($todayStatus['state'] === 'checked_in'): ?>
                <p class="font-black text-xl text-blue-600 dark:text-blue-400">
                    Sudah absen masuk
                    <span class="text-base font-bold ml-2">
                        pukul <?= $todayStatus['attendance']->checkInTime
                            ? htmlspecialchars($todayStatus['attendance']->checkInTime->format('H:i'))
                            : '-' ?>
                    </span>
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Status: <strong><?= htmlspecialchars($todayStatus['attendance']->statusCode) ?></strong> ·
                    Scan lagi untuk absen keluar.
                </p>

            <?php elseif ($todayStatus['state'] === 'complete'): ?>
                <p class="font-black text-xl text-[#00d982]">
                    ✓ Absen lengkap
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Masuk: <?= $todayStatus['attendance']->checkInTime
                        ? htmlspecialchars($todayStatus['attendance']->checkInTime->format('H:i'))
                        : '-' ?> ·
                    Keluar: <?= $todayStatus['attendance']->checkOutTime
                        ? htmlspecialchars($todayStatus['attendance']->checkOutTime->format('H:i'))
                        : '-' ?>
                </p>

            <?php elseif ($todayStatus['state'] === 'final'): ?>
                <?php
                $statusLabels = [
                    'LD' => 'Lepas Dinas',
                    'P' => 'Pawas',
                    'DIKSIP' => 'Diksip',
                    'DIK' => 'Dik',
                    'PATSUS' => 'Patsus',
                    'IZIN' => 'Izin',
                    'CUTI' => 'Cuti',
                    'SKT' => 'Sakit',
                    'TK' => 'Tanpa Keterangan',
                ];
                $label = $statusLabels[$todayStatus['attendance']->statusCode]
                    ?? $todayStatus['attendance']->statusCode;
                ?>
                <p class="font-black text-xl text-red-600 dark:text-red-400">
                    <?= htmlspecialchars($label) ?>
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Status hari ini sudah tercatat. Tidak perlu scan QR.
                    <?php if (!empty($todayStatus['attendance']->remarks)): ?>
                        <br>Keterangan: <?= htmlspecialchars($todayStatus['attendance']->remarks) ?>
                    <?php endif; ?>
                </p>

            <?php endif; ?>
        </div>

        <!-- Scanner -->
        <div class="mb-6 bg-white dark:bg-[#181818] border-4 border-[#121212] dark:border-white
                    shadow-[6px_6px_0_0_#121212] dark:shadow-[6px_6px_0_0_#00d982] p-6 sm:p-8">

            <div id="qr-reader" class="w-full bg-gray-100 dark:bg-[#222] min-h-[300px]
                                       border-4 border-[#121212] dark:border-white"></div>

            <div id="scan-message" class="mt-4 hidden p-4 border-4 border-[#121212] font-black uppercase text-sm"></div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="/absen/manual" class="flex-1 text-center px-5 py-3 bg-yellow-400 text-black border-4 border-[#121212]
                      font-black uppercase text-sm
                      shadow-[5px_5px_0_0_#121212]
                      hover:shadow-none hover:translate-x-[5px] hover:translate-y-[5px]
                      transition-all">
                Absen Manual
            </a>
            <a href="/absen/rekap" class="flex-1 text-center px-5 py-3 bg-[#00d982] text-black border-4 border-[#121212]
                      font-black uppercase text-sm
                      shadow-[5px_5px_0_0_#121212]
                      hover:shadow-none hover:translate-x-[5px] hover:translate-y-[5px]
                      transition-all">
                Rekap Harian
            </a>
            <a href="/absen/rekap/bulanan" class="flex-1 text-center px-5 py-3 bg-[#00d982] text-black border-4 border-[#121212]
                      font-black uppercase text-sm
                      shadow-[5px_5px_0_0_#121212]
                      hover:shadow-none hover:translate-x-[5px] hover:translate-y-[5px]
                      transition-all">
                Rekap Bulanan
            </a>
        </div>

        <!-- Modal Pilih Status (setelah scan) -->
        <div id="status-modal"
            class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/70 backdrop-blur-sm">
            <div class="w-full max-w-sm bg-white dark:bg-[#181818] border-4 border-[#121212] dark:border-white
                        shadow-[8px_8px_0_0_#121212] p-6">

                <h3 class="font-black uppercase text-lg text-[#121212] dark:text-white mb-4">
                    Pilih Status Kehadiran
                </h3>

                <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mb-5">
                    Pilih status Anda hari ini:
                </p>

                <div class="grid grid-cols-2 gap-3">
                    <button type="button" data-status="H" class="px-4 py-4 bg-[#00d982] text-[#121212] border-4 border-[#121212]
                               font-black uppercase text-lg
                               shadow-[4px_4px_0_0_#121212]
                               hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px]
                               transition-all cursor-pointer">
                        Hadir
                    </button>
                    <button type="button" data-status="D" class="px-4 py-4 bg-yellow-400 text-[#121212] border-4 border-[#121212]
                               font-black uppercase text-lg
                               shadow-[4px_4px_0_0_#121212]
                               hover:shadow-none hover:translate-x-[4px] hover:translate-y-[4px]
                               transition-all cursor-pointer">
                        Dinas
                    </button>
                </div>

                <button type="button" id="status-modal-cancel" class="mt-5 w-full px-4 py-2 bg-gray-200 dark:bg-[#222] text-[#121212] dark:text-white
                           border-2 border-[#121212] dark:border-white
                           font-black uppercase text-xs
                           hover:bg-gray-300 dark:hover:bg-[#333] transition-all cursor-pointer">
                    Batal
                </button>

            </div>
        </div>

    </div>
</div>

<!-- QR Scanner Library -->
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
    (() => {
        const QR_CODE = <?= json_encode($qrCode) ?>;
        const STATE = <?= json_encode($todayStatus['state']) ?>;

        const messageEl = document.getElementById('scan-message');
        const statusModal = document.getElementById('status-modal');
        const qrReaderEl = document.getElementById('qr-reader');

        let pendingQrCode = null;
        let html5QrCode = null;
        let scanning = false;

        // ============================================================
        // MESSAGE HELPER
        // ============================================================
        function showMessage(text, type) {
            messageEl.textContent = text;
            messageEl.classList.remove(
                'hidden',
                'bg-red-500', 'text-white',
                'bg-[#00d982]', 'text-black',
                'bg-yellow-400'
            );

            if (type === 'error') {
                messageEl.classList.add('bg-red-500', 'text-white');
            } else if (type === 'warning') {
                messageEl.classList.add('bg-yellow-400', 'text-black');
            } else {
                messageEl.classList.add('bg-[#00d982]', 'text-black');
            }
        }

        // ============================================================
        // SCANNER CONTROL
        // ============================================================
        function stopScanner() {
            if (html5QrCode && scanning) {
                html5QrCode.stop()
                    .then(() => { scanning = false; })
                    .catch(() => { });
            }
        }

        function startScanner() {
            if (!html5QrCode || scanning) return;

            html5QrCode.start(
                { facingMode: 'environment' },
                { fps: 10, qrbox: { width: 250, height: 250 } },
                onScanSuccess
            ).then(() => {
                scanning = true;
            }).catch(err => {
                showMessage('Gagal membuka kamera: ' + err, 'error');
            });
        }

        // ============================================================
        // SEND SCAN (AJAX)
        // ============================================================
        async function sendScan(qrCode, statusCode = null) {
            const res = await fetch('/absen/scan', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    qr_code: qrCode,
                    status_code: statusCode
                }),
            });

            return await res.json();
        }

        // ============================================================
        // ON SCAN SUCCESS
        // ============================================================
        function onScanSuccess(decodedText) {
            if (pendingQrCode || !scanning) return;

            pendingQrCode = decodedText;
            stopScanner();

            // state = none → minta pilih status (Hadir / Dinas)
            if (STATE === 'none') {
                statusModal.classList.remove('hidden');
                return;
            }

            // state = ld_only atau checked_in → kirim langsung
            // (Service akan otomatis handle: LD → check-in, checked_in → check-out)
            sendScan(decodedText).then(handleResponse);
        }

        // ============================================================
        // HANDLE RESPONSE
        // ============================================================
        function handleResponse(result) {
            pendingQrCode = null;

            if (result.success) {
                showMessage('✓ ' + result.message + ' (pukul ' + (result.time || '') + ')', 'success');
                // Reload halaman setelah 1.5 detik biar status ter-refresh
                setTimeout(() => window.location.reload(), 1500);
            } else {
                showMessage('✗ ' + result.message, 'error');
                // Resume scanner setelah 2 detik
                setTimeout(() => startScanner(), 2000);
            }
        }

        // ============================================================
        // INIT — cek state dulu
        // ============================================================
        const isFinalState = (STATE === 'final' || STATE === 'complete');

        if (isFinalState) {
            // Tidak perlu scanner — status sudah final
            qrReaderEl.innerHTML =
                '<div class="flex flex-col items-center justify-center min-h-[300px] p-6 text-center">' +
                '<div class="w-16 h-16 mb-4 flex items-center justify-center bg-gray-200 dark:bg-[#333] border-4 border-[#121212] dark:border-white">' +
                '<svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" ' +
                'd="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />' +
                '</svg>' +
                '</div>' +
                '<p class="font-black uppercase text-gray-500 dark:text-gray-400">' +
                (STATE === 'complete' ? 'Absen hari ini sudah lengkap' : 'Status hari ini sudah final') +
                '</p>' +
                '<p class="mt-2 text-sm font-bold text-gray-400">' +
                'Scanner tidak diaktifkan.' +
                '</p>' +
                '</div>';
        } else {
            // Aktifkan scanner
            html5QrCode = new Html5Qrcode('qr-reader');
            startScanner();
        }

        // ============================================================
        // MODAL HANDLER — pilih status H / D
        // ============================================================
        document.querySelectorAll('#status-modal [data-status]').forEach(btn => {
            btn.addEventListener('click', () => {
                const statusCode = btn.dataset.status;
                statusModal.classList.add('hidden');

                if (pendingQrCode) {
                    sendScan(pendingQrCode, statusCode).then(handleResponse);
                }
            });
        });

        document.getElementById('status-modal-cancel').addEventListener('click', () => {
            statusModal.classList.add('hidden');
            pendingQrCode = null;
            // Resume scanner
            setTimeout(() => startScanner(), 300);
        });

        // ============================================================
        // STOP SCANNER SAAT HALAMAN DI-LEAVE
        // ============================================================
        window.addEventListener('beforeunload', () => {
            stopScanner();
        });

    })();
</script>