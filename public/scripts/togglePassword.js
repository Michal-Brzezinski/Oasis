/**
 * Toggle Password Visibility
 * Unified script for login and register forms
 */

function togglePassword(inputId) {
    const passwordInput = document.getElementById(inputId);
    
    if (!passwordInput) {
        console.error(`Password input with id "${inputId}" not found`);
        return;
    }
    
    const toggleButton = passwordInput.parentElement.querySelector('.password-toggle');
    
    if (!toggleButton) {
        console.error('Password toggle button not found');
        return;
    }
    
    const eyeClosed = toggleButton.querySelector('.eye-icon--closed');
    const eyeOpen = toggleButton.querySelector('.eye-icon--open');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleButton.setAttribute('aria-label', 'Ukryj hasło');
        if (eyeClosed) eyeClosed.style.display = 'none';
        if (eyeOpen) eyeOpen.style.display = 'block';
    } else {
        passwordInput.type = 'password';
        toggleButton.setAttribute('aria-label', 'Pokaż hasło');
        if (eyeClosed) eyeClosed.style.display = 'block';
        if (eyeOpen) eyeOpen.style.display = 'none';
    }
}

// Optional: Add keyboard support for toggle button
document.addEventListener('DOMContentLoaded', function() {
    const toggleButtons = document.querySelectorAll('.password-toggle');
    
    toggleButtons.forEach(button => {
        button.addEventListener('keydown', function(e) {
            // Allow Enter and Space to trigger the button
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                button.click();
            }
        });
    });
});