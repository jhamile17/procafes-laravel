function togglePassword(event) {

    const button = event.target.closest('.auth-password-toggle');

    if (!button) return;

    const input = document.getElementById(button.dataset.password);

    if (!input) return;

    const icon = button.querySelector('i');

    if (input.type === 'password') {

        input.type = 'text';

        icon.classList.replace('bi-eye', 'bi-eye-slash');

    } else {

        input.type = 'password';

        icon.classList.replace('bi-eye-slash', 'bi-eye');

    }

}

document.addEventListener('click', function (event) {

    if (event.target.closest('.auth-password-toggle')) {

        togglePassword(event);

    }

});