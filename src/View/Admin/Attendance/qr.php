<div class="min-h-screen bg-gray-50 dark:bg-[#121212] pt-12 pb-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">

        <div class="mb-6">
            <h1
                class="font-black text-4xl sm:text-5xl uppercase tracking-tight leading-none text-[#121212] dark:text-white">
                Kelola QR Absensi
            </h1>
            <p class="mt-3 text-sm sm:text-base font-medium text-gray-600 dark:text-gray-400">
                QR code yang dipakai untuk absen di kantor.
            </p>
        </div>

        <?php if (empty($qrList)): ?>
            <div class="bg-white dark:bg-[#181818] border-4 border-[#121212] dark:border-white
                        shadow-[6px_6px_0_0_#121212] p-8 text-center">
                <p class="font-black uppercase text-gray-500">
                    Belum ada QR aktif.
                </p>
            </div>
        <?php else: ?>

            <?php foreach ($qrList as $qr): ?>
                <div class="bg-white dark:bg-[#181818] border-4 border-[#121212] dark:border-white
                            shadow-[6px_6px_0_0_#121212] dark:shadow-[6px_6px_0_0_#00d982] p-6 mb-6">

                    <!-- Info QR -->
                    <div class="mb-5 pb-5 border-b-2 border-dashed border-gray-300 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <div>
                                <p class="text-xs font-black uppercase tracking-widest text-gray-500 mb-1">
                                    QR #<?= (int) $qr->id ?>
                                </p>
                                <p class="font-black text-xl text-[#121212] dark:text-white">
                                    <?= htmlspecialchars($qr->name) ?>
                                </p>
                                <p class="mt-1 font-mono text-sm text-gray-500 dark:text-gray-400">
                                    <?= htmlspecialchars($qr->code) ?>
                                </p>
                            </div>
                            <span
                                class="inline-block px-3 py-1 bg-[#00d982] text-[#121212] border-2 border-[#121212] text-xs font-black uppercase self-start">
                                Aktif
                            </span>
                        </div>
                    </div>

                    <!-- QR Preview -->
                    <div class="flex flex-col sm:flex-row gap-6 items-start">

                        <div class="bg-white p-6 border-4 border-[#121212] shadow-[5px_5px_0_0_#121212] shrink-0">
                            <div id="qr-<?= (int) $qr->id ?>" class="flex items-center justify-center"></div>
                        </div>

                        <div class="flex-1 space-y-3 w-full">
                            <p class="text-sm font-bold text-gray-600 dark:text-gray-400">
                                QR ini untuk di-print dan ditempel di pintu masuk kantor.
                                Anggota scan QR ini pakai HP mereka.
                            </p>

                            <div class="flex flex-col sm:flex-row gap-3 pt-2">
                                <button type="button"
                                    onclick="downloadQr(<?= (int) $qr->id ?>, '<?= htmlspecialchars($qr->code, ENT_QUOTES) ?>')"
                                    class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-3
                                           bg-[#00d982] text-[#121212] border-4 border-[#121212]
                                           font-black uppercase text-sm
                                           shadow-[5px_5px_0_0_#121212]
                                           hover:shadow-none hover:translate-x-[5px] hover:translate-y-[5px]
                                           transition-all cursor-pointer">
                                    <i data-lucide="download" class="w-5 h-5"></i>
                                    Download PNG
                                </button>

                                <button type="button" onclick="printQr(<?= (int) $qr->id ?>)" class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-3
                                           bg-yellow-400 text-[#121212] border-4 border-[#121212]
                                           font-black uppercase text-sm
                                           shadow-[5px_5px_0_0_#121212]
                                           hover:shadow-none hover:translate-x-[5px] hover:translate-y-[5px]
                                           transition-all cursor-pointer">
                                    <i data-lucide="printer" class="w-5 h-5"></i>
                                    Print
                                </button>
                            </div>

                            <p class="text-xs font-bold text-gray-500 dark:text-gray-400 mt-3">
                                💡 Download PNG → save file → share ke anggota via WA/Telegram, atau print langsung.
                            </p>
                        </div>

                    </div>

                </div>
            <?php endforeach; ?>

        <?php endif; ?>

    </div>
</div>

<!-- QR Code Generator Library -->
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

<script>
    // ============================================================
    // GENERATE QR UNTUK TIAP ITEM
    // ============================================================
    const qrCodes = <?= json_encode(array_map(fn($qr) => [
        'id' => $qr->id,
        'code' => $qr->code,
    ], $qrList)) ?>;

    qrCodes.forEach(function (item) {
        const container = document.getElementById('qr-' + item.id);
        if (!container) return;

        new QRCode(container, {
            text: item.code,
            width: 250,
            height: 250,
            correctLevel: QRCode.CorrectLevel.H,
            colorDark: '#000000',
            colorLight: '#ffffff',
        });
    });

    // ============================================================
    // DOWNLOAD QR SEBAGAI PNG
    // ============================================================
    function downloadQr(id, code) {
        const container = document.getElementById('qr-' + id);
        if (!container) return;

        // QRCode.js render sebagai <img> ATAU <canvas>
        const img = container.querySelector('img');
        const canvas = container.querySelector('canvas');

        // Prioritaskan canvas (kualitas lebih bagus)
        if (canvas) {
            const dataUrl = canvas.toDataURL('image/png');
            triggerDownload(dataUrl, 'qr-' + code + '.png');
            return;
        }

        // Fallback: img (data URL)
        if (img && img.src && img.src.startsWith('data:')) {
            triggerDownload(img.src, 'qr-' + code + '.png');
            return;
        }

        alert('QR belum siap, tunggu sebentar lalu coba lagi.');
    }

    function triggerDownload(dataUrl, filename) {
        const link = document.createElement('a');
        link.href = dataUrl;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }

    // ============================================================
    // PRINT QR
    // ============================================================
    function printQr(id) {
        const container = document.getElementById('qr-' + id);
        if (!container) return;

        const img = container.querySelector('img');
        const canvas = container.querySelector('canvas');

        let dataUrl = '';
        if (canvas) {
            dataUrl = canvas.toDataURL('image/png');
        } else if (img && img.src) {
            dataUrl = img.src;
        }

        if (!dataUrl) {
            alert('QR belum siap, tunggu sebentar lalu coba lagi.');
            return;
        }

        // Buka window print baru
        const win = window.open('', '_blank');
        win.document.write(`
            <html>
                <head>
                    <title>Print QR</title>
                    <style>
                        body {
                            margin: 0;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            min-height: 100vh;
                            font-family: sans-serif;
                        }
                        .container {
                            text-align: center;
                            padding: 40px;
                        }
                        img {
                            width: 400px;
                            height: 400px;
                            image-rendering: pixelated;
                        }
                        .code {
                            margin-top: 20px;
                            font-family: monospace;
                            font-size: 16px;
                            color: #555;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <img src="${dataUrl}" alt="QR">
                        <p class="code">${code = '<?= htmlspecialchars($qrList[0]->code ?? '') ?>'}</p>
                    </div>
                    <script>
                        window.onload = function() {
                            setTimeout(function() {
                                window.print();
                            }, 300);
                        };
                    <\/script>
                </body>
            </html>
        `);
        win.document.close();
    }
</script>