document.addEventListener('DOMContentLoaded', () => {
    console.log('alert.js cargado');

    const overlay = document.getElementById('appAlert');

    if (!overlay) return;

    overlay.classList.add('show');

    setTimeout(() => {

        overlay.classList.remove('show');

        setTimeout(() => {

            overlay.remove();

        }, 250);

    }, 2500);

});