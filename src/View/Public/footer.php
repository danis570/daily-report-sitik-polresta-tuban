    </main>

    <!-- FOOTER -->
    <footer class="bg-black text-white py-6 border-t-4 border-primary">
        <div class="max-w-7xl mx-auto px-6
                    flex flex-col sm:flex-row
                    justify-between items-center gap-2">
            <p class="font-bold text-sm">© 2026 Teknik Informatika UNIROW Tuban.</p>
            <p class="text-xs text-gray-400 font-bold">SITIK Polresta Tuban</p>
        </div>
    </footer>


    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /* ==============================
             * 1. INIT LUCIDE
             * ============================== */
            if (window.lucide) lucide.createIcons();


            /* ==============================
             * 2. THEME TOGGLE (1 tombol, global)
             * ============================== */
            const themeToggle = document.getElementById('theme-toggle');

            if (themeToggle) {
                themeToggle.addEventListener('click', function (e) {
                    e.preventDefault();

                    const isDark = document.documentElement.classList.toggle('dark');
                    localStorage.setItem('theme', isDark ? 'dark' : 'light');
                });
            }

            // Init dari localStorage / preferensi sistem
            const saved = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            if (saved === 'dark' || (!saved && prefersDark)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }


            /* ==============================
             * 3. MOBILE MENU TOGGLE
             * ============================== */
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu    = document.getElementById('mobile-menu');

            if (mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', function () {
                    mobileMenu.classList.toggle('hidden');

                    const isOpen = !mobileMenu.classList.contains('hidden');
                    mobileMenuBtn.innerHTML = `<i data-lucide="${isOpen ? 'x' : 'menu'}" class="w-5 h-5"></i>`;

                    if (window.lucide) lucide.createIcons();
                });

                // Tutup mobile menu saat klik link di dalamnya
                mobileMenu.querySelectorAll('a').forEach(function (link) {
                    link.addEventListener('click', function () {
                        mobileMenu.classList.add('hidden');
                        mobileMenuBtn.innerHTML = `<i data-lucide="menu" class="w-5 h-5"></i>`;

                        if (window.lucide) lucide.createIcons();
                    });
                });
            }

        });
    </script>
</body>

</html>