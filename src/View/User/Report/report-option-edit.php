<div class="min-h-screen bg-gray-50 flex items-center justify-center mt-24 px-4 py-12">
    <div class="max-w-xl w-full">

        <!-- Header -->
        <div class="mb-6">

            <div class="inline-flex items-center
                        bg-black text-white
                        border-2 border-black
                        px-3 py-1
                        text-xs font-black uppercase tracking-widest
                        shadow-brutal-dark">
                Report Options
            </div>

            <h1 class="mt-4 text-4xl sm:text-5xl
                       font-black text-black
                       uppercase tracking-tight">
                <?= htmlspecialchars($title ?? 'Ubah Pilihan Laporan') ?>
            </h1>

            <p class="mt-3 text-sm font-medium text-gray-600">
                SITIK Polresta Tuban — Edit kategori atau opsi #<?= $option->id ?>
            </p>

        </div>


        <!-- Error -->
        <?php if (!empty($error)) { ?>

            <div class="mb-6
                        bg-red-500 text-white
                        border-2 border-black
                        shadow-brutal
                        p-4" role="alert">

                <div class="flex items-start gap-3">

                    <div class="w-7 h-7 shrink-0
                                bg-white text-red-500
                                border-2 border-black
                                flex items-center justify-center
                                font-black">
                        !
                    </div>

                    <div>
                        <p class="font-black uppercase text-sm">
                            Gagal Menyimpan
                        </p>

                        <p class="mt-1 text-sm font-medium">
                            <?= htmlspecialchars($error) ?>
                        </p>
                    </div>

                </div>

            </div>

        <?php } ?>


        <!-- Form Card -->
        <div class="bg-white
                    border-2 border-black
                    shadow-brutal
                    p-6 sm:p-8">

            <form action="/report/option/edit/<?= $option->id ?>" method="POST" class="space-y-6">

                <!-- ID -->
                <div class="flex items-center justify-between
                            bg-gray-100
                            border-2 border-black
                            px-4 py-3">

                    <span class="text-xs font-black uppercase tracking-wide">
                        ID Opsi
                    </span>

                    <span class="bg-black text-white
                                 px-2 py-1
                                 font-mono font-bold text-xs">
                        #<?= $option->id ?>
                    </span>

                </div>


                <!-- Kategori -->
                <div>

                    <label for="category" class="block mb-2
                               text-sm font-black
                               uppercase tracking-wide text-black">
                        Kategori
                    </label>

                    <input type="text" id="category" name="category"
                        value="<?= htmlspecialchars($_POST['category'] ?? $option->category) ?>" required class="block w-full
                               px-4 py-3
                               bg-gray-50 text-black
                               border-2 border-black
                               font-medium
                               outline-none
                               placeholder:text-gray-400
                               focus:bg-white
                               focus:ring-0
                               focus:shadow-[4px_4px_0px_0px_#00d982]
                               transition-all duration-150">

                </div>


                <!-- Nama Opsi -->
                <div>

                    <label for="name" class="block mb-2
                               text-sm font-black
                               uppercase tracking-wide text-black">
                        Nama Pilihan
                    </label>

                    <input type="text" id="name" name="name"
                        value="<?= htmlspecialchars($_POST['name'] ?? $option->name) ?>" required class="block w-full
                               px-4 py-3
                               bg-gray-50 text-black
                               border-2 border-black
                               font-medium
                               outline-none
                               placeholder:text-gray-400
                               focus:bg-white
                               focus:ring-0
                               focus:shadow-[4px_4px_0px_0px_#00d982]
                               transition-all duration-150">

                </div>


                <!-- Deskripsi -->
                <div>

                    <label for="description" class="block mb-2
                               text-sm font-black
                               uppercase tracking-wide text-black">
                        Deskripsi
                        <span class="text-gray-400">(Opsional)</span>
                    </label>

                    <textarea id="description" name="description" rows="4"
                        class="block w-full
                               px-4 py-3
                               bg-gray-50 text-black
                               border-2 border-black
                               font-medium
                               outline-none
                               resize-y
                               placeholder:text-gray-400
                               focus:bg-white
                               focus:ring-0
                               focus:shadow-[4px_4px_0px_0px_#00d982]
                               transition-all duration-150"><?= htmlspecialchars($_POST['description'] ?? $option->description ?? '') ?></textarea>

                </div>


                <!-- Actions -->
                <div class="pt-5
                            border-t-2 border-black
                            flex flex-col-reverse sm:flex-row
                            sm:items-center
                            sm:justify-between
                            gap-3">

                    <!-- Batal -->
                    <a href="/report/option" class="inline-flex items-center justify-center
                               px-5 py-3
                               bg-white text-black
                               border-2 border-black
                               font-black uppercase text-sm
                               shadow-[4px_4px_0px_0px_#000]
                               hover:translate-x-[4px]
                               hover:translate-y-[4px]
                               hover:shadow-none
                               transition-all duration-150">
                        Batal
                    </a>


                    <!-- Simpan -->
                    <button type="submit" class="inline-flex items-center justify-center
                               px-6 py-3
                               bg-[#00d982] text-black
                               border-2 border-black
                               font-black uppercase text-sm
                               shadow-brutal
                               hover:translate-x-[6px]
                               hover:translate-y-[6px]
                               hover:shadow-none
                               transition-all duration-150">

                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>