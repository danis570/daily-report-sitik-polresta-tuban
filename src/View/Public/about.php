<!-- ABOUT SECTION -->
<section id="about" class="pt-32 pb-20 bg-white dark:bg-dark relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">

        <!-- Decoration -->
        <div class="absolute -right-20 top-10 w-72 h-72 z-0 flex items-center justify-center opacity-30 dark:opacity-10 pointer-events-none">
            <div class="absolute w-72 h-72 border-4 border-black dark:border-white rounded-full animate-ping [animation-duration:3s]"></div>
            <div class="absolute w-56 h-56 border-4 border-black dark:border-white rounded-full"></div>
            <div class="absolute w-40 h-40 border-4 border-dashed border-primary rounded-full"></div>
            <div class="absolute w-20 h-20 bg-black dark:bg-primary rounded-full"></div>
        </div>
        
        <!-- Judul Section Utama -->
        <div class="inline-block bg-black text-white dark:bg-primary dark:text-black border-4 border-black dark:border-white p-4 mb-16 shadow-[8px_8px_0px_#00d982] dark:shadow-[8px_8px_0px_#000]">
            <h1 class="font-display text-4xl md:text-6xl tracking-tight uppercase">Tentang Sistem</h1>
        </div>

        <!-- Konten Grid Utama (DIUBAH: Ditambahkan items-stretch agar tinggi kolom kiri & kanan sama) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-stretch">
            
            <!-- Sisi Kiri: Profil Visual & Pancaran Sinyal Animasi (DIUBAH: Menggunakan h-full agar meregang sempurna) -->
            <div class="lg:col-span-5 relative h-full min-h-[320px]">

                <!-- Kotak Informasi Utama Sistem (DIUBAH: Menghilangkan absolute inset-0 agar tinggi aslinya mengisi container h-full) -->
                <div class="w-full h-full bg-primary/10 border-4 border-black dark:border-white p-6 shadow-[12px_12px_0px_0px_rgba(0,0,0,1)] dark:shadow-[12px_12px_0px_0px_#00d982] flex flex-col justify-between z-20 bg-white dark:bg-dark">
                    <div class="flex justify-between items-start">
                        <span class="font-mono text-xs uppercase tracking-wider opacity-60">SYSTEM // V.1.0-LIVE</span>
                        <div class="w-3 h-3 rounded-full bg-green-500 border border-black animate-pulse"></div>
                    </div>
                    <div class="my-6">
                        <h2 class="font-display text-3xl md:text-4xl text-black dark:text-white uppercase leading-none mb-2">
                            Aplikasi SITIK
                        </h2>
                        <p class="font-mono text-sm tracking-tight text-gray-500 dark:text-primary">E-REPORTING SYSTEM</p>
                    </div>
                    <div class="border-t-4 border-black dark:border-white pt-4 flex justify-between items-center font-mono text-xs">
                        <span>DATA SECURE: SSL</span>
                        <span>INTERNAL NET</span>
                    </div>
                </div>
            </div>

            <!-- Sisi Kanan: Deskripsi Sistem & Visi Misi -->
            <div class="lg:col-span-7 space-y-8 z-20 flex flex-col justify-between">
                <!-- Deskripsi Umum Berfokus pada Sistem -->
                <div class="bg-white dark:bg-dark border-4 border-black dark:border-white p-6 md:p-8 shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] dark:shadow-[8px_8px_0px_0px_rgba(255,255,255,0.15)] flex-grow">
                    <h3 class="font-display text-2xl uppercase mb-4 border-b-2 border-black dark:border-primary pb-2">
                        Fungsi Utama Aplikasi
                    </h3>
                    <p class="text-lg leading-relaxed font-medium">
                        Sistem Informasi Laporan Harian TIK Polresta Tuban adalah platform digital terintegrasi yang dirancang khusus untuk memodernisasi pencatatan kinerja operasional. Sistem ini mempermudah personel dalam menginput log harian, memantau infrastruktur teknologi secara *real-time*, serta menyajikan data valid guna mendukung pengambilan keputusan pimpinan secara cepat dan akurat.
                    </p>
                </div>

                <!-- Fokus Layanan / Kapabilitas Sistem -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Fitur 1: E-Reporting -->
                    <div class="border-4 border-black dark:border-white p-6 bg-primary text-black shadow-[4px_4px_0px_0px_#000]">
                        <div class="flex items-center gap-3 mb-2">
                            <i data-lucide="file-text" class="w-8 h-8"></i>
                            <h4 class="font-bold text-xl uppercase tracking-tight">Pelaporan Instan</h4>
                        </div>
                        <p class="text-sm font-medium">Pencatatan aktivitas tugas dan pemeliharaan perangkat yang ringkas, memotong birokrasi manual yang lambat.</p>
                    </div>

                    <!-- Fitur 2: Sinkronisasi Data -->
                    <div class="border-4 border-black dark:border-white p-6 bg-white dark:bg-dark shadow-[4px_4px_0px_0px_#000] dark:shadow-[4px_4px_0px_0px_#00d982]">
                        <div class="flex items-center gap-3 mb-2">
                            <i data-lucide="database" class="w-8 h-8 text-primary"></i>
                            <h4 class="font-bold text-xl uppercase tracking-tight">Sentralisasi Data</h4>
                        </div>
                        <p class="text-sm font-medium opacity-80">Seluruh data laporan dari jajaran Polsek tersimpan aman dalam satu basis data server internal yang terenkripsi.</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
