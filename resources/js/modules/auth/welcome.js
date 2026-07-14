document.addEventListener('DOMContentLoaded', () => {

    const container = document.querySelector('.auth-success');

    if (!container) {
        return;
    }

    const redirect = container.dataset.redirect;

    setTimeout(() => {

        window.location.href = redirect;

    }, 2000);

});