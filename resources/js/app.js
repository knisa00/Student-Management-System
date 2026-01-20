document.addEventListener('DOMContentLoaded', function () {
    console.log('Student Management System loaded');

    // Add global confirm function
    window.confirmAction = function(message) {
        return confirm(message || 'Are you sure you want to proceed?');
    };

    // Optional: Add smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Auto-focus first input on login/register forms
    const firstInput = document.querySelector('input:not([type="hidden"]):not([type="submit"])');
    if (firstInput) {
        firstInput.focus();
    }
});