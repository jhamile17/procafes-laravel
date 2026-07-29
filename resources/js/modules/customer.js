document.addEventListener('DOMContentLoaded', () => {

    const input = document.getElementById('direccion');

    const dropdown = document.getElementById('addressSuggestions');

    if (!input || !dropdown) {

        return;

    }

    let timer = null;

    async function searchAddress(query) {

        try {

            const url = input.dataset.searchUrl;

            const response = await fetch(
                `${url}?q=${encodeURIComponent(query)}`,
                {
                    headers: {
                        Accept: 'application/json'
                    }
                }
            );
            if (!response.ok) {

                dropdown.classList.remove('show');

                return;

            }

            const addresses = await response.json();

            render(addresses);

        } catch (e) {

            dropdown.classList.remove('show');

        }

    }

    function render(addresses) {

        dropdown.innerHTML = '';

        if (!addresses.length) {

            dropdown.classList.remove('show');

            return;

        }

        addresses.forEach(address => {

            const item = document.createElement('div');

            item.className = 'customer-address-item';

            item.innerHTML = `
                <i class="bi bi-geo-alt-fill"></i>
                <span>${address.label}</span>
            `;

            item.addEventListener('click', () => {

                input.value = address.label;

                dropdown.classList.remove('show');

            });

            dropdown.appendChild(item);

        });

        dropdown.classList.add('show');

    }

    input.addEventListener('input', () => {

        clearTimeout(timer);

        const value = input.value.trim();

        if (value.length < 2) {

            dropdown.classList.remove('show');

            return;

        }

        timer = setTimeout(() => {

            searchAddress(value);

        }, 350);

    });

    document.addEventListener('click', e => {

        if (!e.target.closest('.customer-address')) {

            dropdown.classList.remove('show');

        }

    });

});