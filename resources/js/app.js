import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Initialize dark mode on page load
document.addEventListener('DOMContentLoaded', function() {
    const isDark = localStorage.getItem('darkMode') === 'true';
    
    if (isDark) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
});

Alpine.start();
