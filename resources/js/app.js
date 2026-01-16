import './bootstrap';
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';

Alpine.plugin(persist);
window.Alpine = Alpine;
Alpine.start();

// Toast notification system
window.showToast = function(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 ${
        type === 'success' ? 'bg-green-500 text-white' :
        type === 'error' ? 'bg-red-500 text-white' :
        type === 'warning' ? 'bg-yellow-500 text-white' :
        'bg-blue-500 text-white'
    }`;
    toast.textContent = message;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.classList.add('translate-x-full', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 3000);
};

// Handle Laravel session flash messages on page load
document.addEventListener('DOMContentLoaded', function() {
    // Check for flash messages in meta tags or data attributes
    const successMessage = document.querySelector('[data-success-message]');
    const errorMessage = document.querySelector('[data-error-message]');
    
    if (successMessage) {
        window.showToast(successMessage.getAttribute('data-success-message'), 'success');
    }
    
    if (errorMessage) {
        window.showToast(errorMessage.getAttribute('data-error-message'), 'error');
    }
});
