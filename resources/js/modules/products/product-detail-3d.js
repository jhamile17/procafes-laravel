/*=========================================================
    EFECTO 3D — DETALLE DEL PRODUCTO
=========================================================*/

function initializeProductDetail3D() {

    const containers =
        document.querySelectorAll(
            '.product-detail-image'
        );

    if (!containers.length) {
        return;
    }


    containers.forEach(container => {

        const image =
            container.querySelector('img');

        if (!image) {
            return;
        }


        /*=====================================================
            MOVIMIENTO
        =====================================================*/

        container.addEventListener(
            'mousemove',
            event => {

                const rect =
                    container.getBoundingClientRect();

                const x =
                    event.clientX - rect.left;

                const y =
                    event.clientY - rect.top;


                const centerX =
                    rect.width / 2;

                const centerY =
                    rect.height / 2;


                const rotateY =
                    ((x - centerX) / centerX) * 7;

                const rotateX =
                    ((centerY - y) / centerY) * 7;


                image.style.transform = `
                    perspective(900px)
                    rotateX(${rotateX}deg)
                    rotateY(${rotateY}deg)
                    scale(1.04)
                `;

            }
        );


        /*=====================================================
            SALIR DEL ÁREA
        =====================================================*/

        container.addEventListener(
            'mouseleave',
            () => {

                image.style.transform = `
                    perspective(900px)
                    rotateX(0deg)
                    rotateY(0deg)
                    scale(1)
                `;

            }
        );

    });

}


/*=========================================================
    INICIALIZAR
=========================================================*/

document.addEventListener(
    'DOMContentLoaded',
    initializeProductDetail3D
);