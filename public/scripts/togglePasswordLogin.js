function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleButton = document.querySelector('.password-toggle');
    const eyeClosed = toggleButton.querySelector('.eye-icon--closed');
    const eyeOpen = toggleButton.querySelector('.eye-icon--open');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleButton.setAttribute('aria-label', 'Ukryj hasło');
        eyeClosed.style.display = 'none';
        eyeOpen.style.display = 'block';
    } else {
        passwordInput.type = 'password';
        toggleButton.setAttribute('aria-label', 'Pokaż hasło');
        eyeClosed.style.display = 'block';
        eyeOpen.style.display = 'none';
    }
}