import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Global dark mode functions
window.toggleDarkMode = function() {
    console.log('toggleDarkMode function called!');
    const currentMode = localStorage.getItem('darkMode') === 'true';
    const newMode = !currentMode;
    
    console.log('Current mode:', currentMode, 'New mode:', newMode);
    
    localStorage.setItem('darkMode', newMode);
    
    if (newMode) {
        document.documentElement.classList.add('dark');
        console.log('Added dark class');
    } else {
        document.documentElement.classList.remove('dark');
        console.log('Removed dark class');
    }
    
    console.log('HTML classes:', document.documentElement.className);
    
    return newMode;
};

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
