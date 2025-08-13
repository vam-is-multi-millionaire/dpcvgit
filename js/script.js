document.addEventListener('DOMContentLoaded', function() {

    // --- Theme (Dark/Light Mode) Toggle ---
    const themeToggle = document.getElementById('theme-toggle');
    const body = document.body;
    const header = document.querySelector('.header');
    const navbar = header ? header.querySelector('.navbar') : null;
    const navbarCollapse = navbar ? navbar.querySelector('.navbar-collapse') : null;

    const applyTheme = (theme) => {
        body.classList.toggle('dark-mode', theme === 'dark');
        
        if (navbar) {
            navbar.classList.toggle('navbar-dark', theme === 'dark');
            navbar.classList.toggle('bg-dark', theme === 'dark');
            navbar.classList.toggle('navbar-light', theme !== 'dark');
            navbar.classList.toggle('bg-light', theme !== 'dark');
        }
        
        if (header) {
            header.classList.toggle('bg-dark', theme === 'dark');
            header.classList.toggle('bg-light', theme !== 'dark');
        }

        if (navbarCollapse) {
            navbarCollapse.classList.toggle('bg-dark', theme === 'dark');
        }
    };

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const newTheme = body.classList.contains('dark-mode') ? 'light' : 'dark';
            localStorage.setItem('theme', newTheme);
            applyTheme(newTheme);
        });
    }

    // Apply theme on initial load
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        applyTheme(savedTheme);
    }

});



