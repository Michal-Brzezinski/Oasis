    function togglePassword(inputId) {
        const passwordInput = document.getElementById(inputId);
        const toggleButton = passwordInput.parentElement.querySelector('.password-toggle');
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

    // Walidacja po stronie klienta
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.login-form-container');
        const password1 = document.getElementById('password1');
        const password2 = document.getElementById('password2');
        
        // Sprawdzanie zgodności haseł
        password2.addEventListener('input', function() {
            if (password2.value !== password1.value) {
                password2.setCustomValidity('Hasła muszą być identyczne');
            } else {
                password2.setCustomValidity('');
            }
        });
        
        password1.addEventListener('input', function() {
            if (password2.value !== '') {
                if (password2.value !== password1.value) {
                    password2.setCustomValidity('Hasła muszą być identyczne');
                } else {
                    password2.setCustomValidity('');
                }
            }
        });
        
        // Walidacja formularza przy submit
        form.addEventListener('submit', function(e) {
            if (password1.value !== password2.value) {
                e.preventDefault();
                password2.setCustomValidity('Hasła muszą być identyczne');
                password2.reportValidity();
            }
        });
    });