<div class="min-h-screen bg-gray-50 py-12 px-4 mt-24 sm:px-6 lg:px-8">

    <div class="max-w-6xl mx-auto">

        <!-- Hero -->
        <div class="mb-10">

            <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-6">

                <div>

                    <div class="inline-flex items-center bg-black text-white
                                px-3 py-1 mb-4
                                text-xs font-black uppercase tracking-widest
                                border-2 border-black
                                shadow-brutal-dark">

                        Daily Report

                    </div>

                    <h1 class="text-4xl sm:text-5xl font-black
                               text-black uppercase tracking-tight leading-none">

                        Dashboard

                    </h1>

                    <p class="mt-4 text-sm sm:text-base
                              font-medium text-gray-600 max-w-2xl">

                        Selamat datang kembali.
                        Kelola dan pantau laporan harian SITIK Polresta Tuban
                        dari satu tempat.

                    </p>

                </div>


                <!-- User Info -->
                <div class="bg-white border-2 border-black
                            shadow-brutal p-4 min-w-[260px]">

                    <p class="text-xs font-black uppercase
                              tracking-wider text-gray-500">

                        Login sebagai

                    </p>

                    <p class="mt-1 text-base font-black text-black break-all">

                        <?= htmlspecialchars($user->email) ?>

                    </p>

                    <div class="mt-3 inline-flex
                                bg-[#00d982] text-black
                                border-2 border-black
                                px-2.5 py-1
                                text-xs font-black uppercase">

                        USER

                    </div>

                </div>

            </div>

            <div class="mt-8 border-b-4 border-black"></div>

        </div>


        <!-- Quick Actions -->
        <div class="mb-10">

            <div class="flex items-center gap-2 mb-5">

                <span class="w-3 h-3 bg-[#00d982] border-2 border-black"></span>

                <span class="text-xs font-black uppercase
                             tracking-[0.2em] text-gray-600">

                    Akses Cepat

                </span>

            </div>


            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">


                <!-- Buat Laporan -->
                <a href="/report/add"
                   class="group bg-[#00d982]
                          border-4 border-black
                          shadow-[6px_6px_0_0_#121212]
                          p-6
                          hover:translate-x-[6px]
                          hover:translate-y-[6px]
                          hover:shadow-none
                          transition-all duration-150">

                    <div class="flex items-start justify-between">

                        <div class="w-12 h-12
                                    bg-black text-[#00d982]
                                    border-2 border-black
                                    flex items-center justify-center
                                    text-2xl font-black">

                            +

                        </div>

                        <span class="text-2xl font-black">
                            →
                        </span>

                    </div>

                    <h2 class="mt-6 text-xl font-black uppercase">

                        Buat Laporan

                    </h2>

                    <p class="mt-2 text-sm font-bold text-black/70">

                        Buat laporan kegiatan harian SITIK.

                    </p>

                </a>


                <!-- Daftar Laporan -->
                <a href="/reports"
                   class="group bg-white
                          border-4 border-black
                          shadow-[6px_6px_0_0_#121212]
                          p-6
                          hover:translate-x-[6px]
                          hover:translate-y-[6px]
                          hover:shadow-none
                          transition-all duration-150">

                    <div class="flex items-start justify-between">

                        <div class="w-12 h-12
                                    bg-black text-white
                                    border-2 border-black
                                    flex items-center justify-center
                                    text-xl font-black">

                            ≡

                        </div>

                        <span class="text-2xl font-black">
                            →
                        </span>

                    </div>

                    <h2 class="mt-6 text-xl font-black uppercase">

                        Daftar Laporan

                    </h2>

                    <p class="mt-2 text-sm font-bold text-gray-600">

                        Lihat dan kelola laporan yang sudah dibuat.

                    </p>

                </a>


                <!-- Pelacakan -->
                <a href="/report/tracking"
                   class="group bg-black text-white
                          border-4 border-black
                          shadow-[6px_6px_0_0_#00d982]
                          p-6
                          hover:translate-x-[6px]
                          hover:translate-y-[6px]
                          hover:shadow-none
                          transition-all duration-150">

                    <div class="flex items-start justify-between">

                        <div class="w-12 h-12
                                    bg-[#00d982] text-black
                                    border-2 border-black
                                    flex items-center justify-center
                                    text-xl font-black">

                            #

                        </div>

                        <span class="text-2xl font-black">
                            →
                        </span>

                    </div>

                    <h2 class="mt-6 text-xl font-black uppercase">

                        Pelacakan

                    </h2>

                    <p class="mt-2 text-sm font-bold text-gray-300">

                        Analisis penggunaan opsi dalam periode tertentu.

                    </p>

                </a>

            </div>

        </div>


        <!-- Information -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">


            <!-- Main Information -->
            <div class="lg:col-span-2
                        bg-white
                        border-4 border-black
                        shadow-[6px_6px_0_0_#121212]">

                <div class="bg-black text-white
                            px-6 py-4
                            border-b-4 border-black">

                    <div class="flex items-center gap-2">

                        <span class="w-3 h-3
                                     bg-[#00d982]
                                     border-2 border-white"></span>

                        <h2 class="font-black uppercase tracking-wide">

                            Sistem Laporan Harian

                        </h2>

                    </div>

                </div>


                <div class="p-6">

                    <h3 class="text-2xl font-black uppercase">

                        Kelola laporan dengan lebih mudah.

                    </h3>

                    <p class="mt-3 text-sm font-medium
                              leading-relaxed text-gray-600">

                        Gunakan menu laporan untuk membuat,
                        melihat, dan memantau kegiatan harian
                        Sie TIK Polres Tuban.

                    </p>


                    <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-3">

                        <div class="border-2 border-black p-4">

                            <p class="text-xs font-black uppercase
                                      tracking-wider text-gray-500">

                                01

                            </p>

                            <p class="mt-1 font-black uppercase">

                                Buat Laporan

                            </p>

                        </div>


                        <div class="border-2 border-black p-4">

                            <p class="text-xs font-black uppercase
                                      tracking-wider text-gray-500">

                                02

                            </p>

                            <p class="mt-1 font-black uppercase">

                                Pantau Laporan

                            </p>

                        </div>

                    </div>

                </div>

            </div>


            <!-- Account -->
            <div class="bg-[#00d982]
                        border-4 border-black
                        shadow-[6px_6px_0_0_#121212]">

                <div class="p-6">

                    <div class="w-14 h-14
                                bg-black text-[#00d982]
                                border-2 border-black
                                flex items-center justify-center
                                text-2xl font-black">

                        👤

                    </div>


                    <p class="mt-6 text-xs font-black
                              uppercase tracking-widest">

                        Akun Anda

                    </p>


                    <p class="mt-2 text-lg font-black break-all">

                        <?= htmlspecialchars($user->email) ?>

                    </p>


                    <div class="mt-6 pt-5 border-t-2 border-black">

                        <p class="text-xs font-bold">

                            Gunakan akun Anda untuk mengakses
                            fitur pelaporan yang tersedia.

                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>