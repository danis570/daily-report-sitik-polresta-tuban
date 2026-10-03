        </div><!-- /.flex-1 -->

        <!-- FOOTER -->
        <footer class="bg-black text-white py-6 border-t-4 border-primary">
            <div class="max-w-7xl mx-auto px-6
                        flex flex-col sm:flex-row
                        justify-between items-center gap-2">
                <p class="font-bold text-sm">
                    © 2026 Teknik Informatika UNIROW Tuban.
                </p>
                <p class="text-xs text-gray-400 font-bold">
                    SITIK Polresta Tuban — Admin Panel
                </p>
            </div>
        </footer>
    </main>


    <!-- ============================== -->
    <!-- SCRIPT -->
    <!-- ============================== -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            /* ==============================
             * 1. INIT LUCIDE
             * ============================== */
            if (window.lucide) lucide.createIcons();


            /* ==============================
             * 2. DARK MODE
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
             * 3. SIDEBAR MOBILE
             * ============================== */
            const sidebar  = document.getElementById('sidebar');
            const overlay  = document.getElementById('sidebar-overlay');
            const openBtn  = document.getElementById('sidebar-open');
            const closeBtn = document.getElementById('sidebar-close');

            function openSidebar() {
                if (!sidebar) return;
                sidebar.classList.remove('-translate-x-full');
                overlay?.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                if (!sidebar) return;
                sidebar.classList.add('-translate-x-full');
                overlay?.classList.add('hidden');
                document.body.style.overflow = '';
            }

            openBtn?.addEventListener('click', openSidebar);
            closeBtn?.addEventListener('click', closeSidebar);
            overlay?.addEventListener('click', closeSidebar);

            // Tutup sidebar saat resize ke desktop
            window.addEventListener('resize', function () {
                if (window.innerWidth >= 1024) closeSidebar();
            });

        });
    </script>
</body>

</html>