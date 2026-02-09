import './bootstrap';

// Apply theme based on localStorage
function applyTheme() {
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
}

// Theme toggle functionality
function initThemeToggle() {
    const themeToggleBtn = document.getElementById('theme-toggle');
    const themeToggleDarkIcon = document.getElementById('theme-toggle-dark-icon');
    const themeToggleLightIcon = document.getElementById('theme-toggle-light-icon');

    if (!themeToggleBtn) return;

    // Apply theme first
    applyTheme();

    // Show the correct icon based on current theme
    if (document.documentElement.classList.contains('dark')) {
        themeToggleLightIcon.classList.remove('hidden');
        themeToggleDarkIcon.classList.add('hidden');
    } else {
        themeToggleDarkIcon.classList.remove('hidden');
        themeToggleLightIcon.classList.add('hidden');
    }

    // Remove old event listeners to prevent duplicates
    const newBtn = themeToggleBtn.cloneNode(true);
    themeToggleBtn.parentNode.replaceChild(newBtn, themeToggleBtn);

    // Toggle theme on button click
    document.getElementById('theme-toggle').addEventListener('click', function () {
        const darkIcon = document.getElementById('theme-toggle-dark-icon');
        const lightIcon = document.getElementById('theme-toggle-light-icon');

        // Add transition class to html element
        document.documentElement.classList.add('theme-transition');

        // Toggle icons
        darkIcon.classList.toggle('hidden');
        lightIcon.classList.toggle('hidden');

        // Toggle theme
        if (document.documentElement.classList.contains('dark')) {
            document.documentElement.classList.remove('dark');
            localStorage.theme = 'light';
        } else {
            document.documentElement.classList.add('dark');
            localStorage.theme = 'dark';
        }

        // Remove transition class after animation completes
        setTimeout(() => {
            document.documentElement.classList.remove('theme-transition');
        }, 300);
    });
}

// Initialize on page load
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initThemeToggle);
} else {
    initThemeToggle();
}

// Re-apply theme and re-initialize after Livewire navigation
document.addEventListener('livewire:navigating', applyTheme);
document.addEventListener('livewire:navigated', function () {
    applyTheme();
    initThemeToggle();
});
