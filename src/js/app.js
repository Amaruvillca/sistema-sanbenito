
document.getElementById('customCheck1').addEventListener('change', function () {
    const passwordInput = document.getElementById('inputPassword');
    if (this.checked) {
        passwordInput.type = 'text';
    } else {
        passwordInput.type = 'password';
    }
});

document.getElementById('loginForm').addEventListener('submit', function(event) {
    const email = document.getElementById('inputEmail').value.trim();
    const password = document.getElementById('inputPassword').value.trim();
    const emailErrorDiv = document.getElementById('emailError');
    const passwordErrorDiv = document.getElementById('passwordError');
    let isValid = true;

    // Limpia los mensajes de error anteriores
    emailErrorDiv.textContent = '';
    passwordErrorDiv.textContent = '';

    // Validación del email
    if (!email) {
        emailErrorDiv.textContent = 'El email es obligatorio';
        isValid = false;
    } else {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            emailErrorDiv.textContent = 'El formato del email no es válido';
            isValid = false;
        }
    }

    // Validación de la contraseña
    if (!password) {
        passwordErrorDiv.textContent = 'La contraseña debe ser llenada';
        isValid = false;
    } else if (password.length < 5) {
        passwordErrorDiv.textContent = 'La contraseña debe tener al menos 5 caracteres';
        isValid = false;
    }

    // Evitar que se envíe el formulario si hay errores
    if (!isValid) {
        event.preventDefault();
    }
});
document.addEventListener('DOMContentLoaded', function() {
    // Obtiene todos los elementos que tienen el ID que comienza con "error-"
    document.querySelectorAll('[id^="error-"]').forEach(function(element) {
        setTimeout(function() {
            element.style.transition = 'opacity 1s'; // Transición suave
            element.style.opacity = '0'; // Desaparecer el mensaje
            setTimeout(function() {
                //element.style.display = 'none'; // Eliminar el mensaje del flujo del documento
                element.remove(); // Eliminar el mensaje del DOM
            }/*, 500*/); // Tiempo para coincidir con la duración de la transición
        }, 8000); // Tiempo en milisegundos (5 segundos)
    });
});
