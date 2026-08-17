function initAppAlert() {

    const overlay = document.getElementById('appAlert');

    if (!overlay) {
        return;
    }

    const closeButton = overlay.querySelector('.app-alert-close');

    /*
    |--------------------------------------------------------------------------
    | Mostrar
    |--------------------------------------------------------------------------
    */

    requestAnimationFrame(() => {
        overlay.classList.add('show');
    });

    /*
    |--------------------------------------------------------------------------
    | Cerrar manualmente
    |--------------------------------------------------------------------------
    */

    if (closeButton) {

        closeButton.addEventListener('click', () => {

            closeAlert();

        });

    }

    /*
    |--------------------------------------------------------------------------
    | Cerrar automáticamente
    |--------------------------------------------------------------------------
    */

    setTimeout(() => {

        closeAlert();

    }, 4000);

    /*
    |--------------------------------------------------------------------------
    | Función cerrar
    |--------------------------------------------------------------------------
    */

    function closeAlert() {

        overlay.classList.remove('show');

        setTimeout(() => {

            overlay.remove();

        }, 300);

    }

}


/*
|--------------------------------------------------------------------------
| Página normal
|--------------------------------------------------------------------------
*/

if (document.readyState === 'loading') {

    document.addEventListener(
        'DOMContentLoaded',
        initAppAlert
    );

} else {

    initAppAlert();

}