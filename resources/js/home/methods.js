/*
|--------------------------------------------------------------------------
| METHODS SLIDER
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', () => {

    const slider = document.getElementById('methodsSlider');

    if (!slider) return;

    const prev = document.querySelector('.methods-prev');
    const next = document.querySelector('.methods-next');

    /*
    |--------------------------------------------------------------------------
    | BOTONES
    |--------------------------------------------------------------------------
    */

    const scrollAmount = 280;

    next?.addEventListener('click', () => {

        slider.scrollBy({

            left: scrollAmount,

            behavior: 'smooth'

        });

    });

    prev?.addEventListener('click', () => {

        slider.scrollBy({

            left: -scrollAmount,

            behavior: 'smooth'

        });

    });

    /*
    |--------------------------------------------------------------------------
    | DRAG CON EL MOUSE
    |--------------------------------------------------------------------------
    */

    let isDown = false;
    let startX = 0;
    let scrollLeft = 0;

    slider.addEventListener('mousedown', (e) => {

        isDown = true;

        slider.classList.add('dragging');

        startX = e.pageX - slider.offsetLeft;

        scrollLeft = slider.scrollLeft;

    });

    slider.addEventListener('mouseleave', () => {

        isDown = false;

        slider.classList.remove('dragging');

    });

    slider.addEventListener('mouseup', () => {

        isDown = false;

        slider.classList.remove('dragging');

    });

    slider.addEventListener('mousemove', (e) => {

        if (!isDown) return;

        e.preventDefault();

        const x = e.pageX - slider.offsetLeft;

        const walk = (x - startX) * 2;

        slider.scrollLeft = scrollLeft - walk;

    });

    /*
    |--------------------------------------------------------------------------
    | RUEDA DEL MOUSE
    |--------------------------------------------------------------------------
    */

    slider.addEventListener('wheel', (e) => {

        if (Math.abs(e.deltaY) > Math.abs(e.deltaX)) {

            e.preventDefault();

            slider.scrollLeft += e.deltaY;

        }

    });

});