<!-- FOOTER -->
<footer class="bg-black text-white pt-20 pb-10 border-t-8 border-primary">
    <div
        class="max-w-7xl mx-auto px-4 pt-8 border-t-4 border-white/90 flex flex-col md:flex-row justify-between items-center">
        <p class="font-bold text-white-400">© 2026 Teknik Informatika UNIROW Tuban.</p>
    </div>
</footer>


<!-- Scripts -->
<script>
    // Initialize Lucide Icons
    lucide.createIcons();

    // Dark Mode Logic
    const themeToggleBtn = document.getElementById('theme-toggle');
    const themeToggleMobile = document.getElementById('theme-toggle-mobile');

    function toggleTheme() {
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
        } else {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
        }
    }

    themeToggleBtn.addEventListener('click', toggleTheme);
    themeToggleMobile.addEventListener('click', toggleTheme);

    // Check local storage on load
    if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }

    // Mobile Menu Logic
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
        const icon = mobileMenu.classList.contains('hidden') ? 'menu' : 'x';
        // Re-render icon (simplified for this context)
        mobileMenuBtn.innerHTML = `<i data-lucide="${icon}" class="w-8 h-8"></i>`;
        lucide.createIcons();
    });

    // Close mobile menu on link click
    document.querySelectorAll('#mobile-menu a').forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
            mobileMenuBtn.innerHTML = `<i data-lucide="menu" class="w-8 h-8"></i>`;
            lucide.createIcons();
        });
    });
</script>
</body>

</html>