const Delivery = {
    init(){
        this.cache();
        if(!this.pickupRadio){
            return;
        }
        this.bindEvents();
        this.updatePanels();
    },

    /*Cache DOM*/

    cache(){
        this.routes = window.Laravel.routes;
        this.csrfToken = window.Laravel.csrfToken;
        this.toast = document.getElementById('toast');
        this.toastMessage = document.getElementById('toastMessage');
        
        /*delivery */
        this.pickupRadio = document.getElementById('deliveryPickup');
        this.deliveryRadio = document.getElementById('deliveryShipping');
        this.pickupPanel = document.getElementById('pickupPanel');
        this.deliveryPanel = document.getElementById('deliveryPanel');
        /*direccion */
        this.addressView = document.getElementById('addressView');
        this.addressEmpty = document.getElementById('addressEmpty');
        this.addressForm = document.getElementById('addressForm');
        this.addressTitle = document.getElementById('addressTitle');
        this.addressLocation = document.getElementById('addressLocation');
        this.addressReference = document.getElementById('addressReference');
        /*Botones */
        this.btnAdd = document.getElementById('btnAddAddress');
        this.btnEdit = document.getElementById('btnEditAddress');
        this.btnCancel = document.getElementById('btnCancelAddress');
        this.btnSave = document.getElementById('btnSaveAddress');
        /* buscador */
        this.searchInput = document.getElementById('addressSearch');
        this.results = document.getElementById('addressResults');
        /*campos a rellenar */
        this.direccion = document.getElementById('direccion');
        this.numero = document.getElementById('numero')
        this.departamento = document.getElementById('departamento');
        this.provincia = document.getElementById('provincia');
        this.distrito = document.getElementById('distrito');
        this.referencia = document.getElementById('referencia');
        this.latitude = document.getElementById('latitude');
        this.longitude = document.getElementById('longitude');
        /*timer */
        this.searchTimer = null;
    },
    /*Eventos*/
    bindEvents(){
        this.pickupRadio?.addEventListener(
            'change',
            () => this.updatePanels()
        );
        this.deliveryRadio?.addEventListener(
            'change',
            () => this.updatePanels()
        );
        this.btnAdd?.addEventListener(
            'click',
            ()=> this.showForm()
        );
        this.btnEdit?.addEventListener(
            'click',
            () => this.showForm()
        );
        this.btnCancel?.addEventListener(
            'click',
            () => this.hideForm()
        );
        this.searchInput?.addEventListener(
            'input',
            () => this.debounce()
        );
        this.btnSave?.addEventListener(
            'click',
            () => this.saveAddress()
        );
    },
    /*Actualizar Paneles*/

    updatePanels(){
        const pickup = this.pickupRadio.checked;
        this.pickupPanel?.classList.toggle(
            'd-none',
            !pickup
        );
        this.deliveryPanel?.classList.toggle(
            'd-none',
            pickup
        );
    },

    showForm(){
        this.addressView?.classList.add('d-none');
        this.addressEmpty?.classList.add('d-none');
        this.addressForm?.classList.remove('d-none');
        this.searchInput?.focus();
    },
    /*ocultar formulario */
    hideForm(){
        this.addressForm?.classList.add('d-none');
        const hasAddress = this.direccion.value.trim() !=='';
        this.addressView?.classList.toggle(
            'd-none',
            !hasAddress
        );
        this.addressEmpty?.classList.toggle(
            'd-none',
            hasAddress
        );
    },

    /*
    |--------------------------------------------------------------------------
    | LocationIQ
    |--------------------------------------------------------------------------
    */

    debounce(){
        clearTimeout(this.searchTimer);
        this.searchTimer = setTimeout(() =>{
            this.searchAddress();
        },400);
    },

    async searchAddress(){
        const query = this.searchInput.value.trim();
        if (query.length < 2){
            this.clearResults();
            return;
        }
        try{
            const response = await fetch(
            `${this.routes.address.search}?q=${encodeURIComponent(query)}`,
            {
                headers:{
                    'Accept':'application/json',
                }
            }
        );
        const json = await response.json();
        if(!response.ok || !json.success){
            throw new Error(
                'No fue posible obtener las direcciones'
            );
        }
        this.renderResults(json.data);
        }
        catch(error){
            console.error(error);
            this.clearResults();
            this.showMessage(
                'No fue posible buscar direcciones',
                'error'
            );
        }
    },

    renderResults(addresses){
        this.clearResults();
        if(!addresses.length){
            this.results.innerHTML = `
            <div class="checkout-search-empty">
                <i class="bi bi-search"></i>
                <span>No encontramos direcciones</span>
            </div>`;
            return;
        }
        addresses.forEach(address => {
            const item = document.createElement('div');
            item.className = 'checkout-search-item';
            item.innerHTML =`
                <i class="bi bi-geo-alt-fill"></i>
                <span>${address.label}</span>
            `;
            item.addEventListener(
                'click',
                () => this.selectAddress(address));
            this.results.appendChild(item);
        });
    },

    selectAddress(address){
        this.searchInput.value = address.direccion;
        /*campos ocultos */
        this.direccion.value = address.direccion;
        this.numero.value = address.numero ?? '';
        this.departamento.value = address.departamento;
        this.provincia.value = address.provincia;
        this.distrito.value = address.distrito;
        this.latitude.value = address.latitude;
        this.longitude.value = address.longitude;
        this.clearResults();
    },

    clearResults(){
        if (this.results){
            this.results.innerHTML = '';
        }
    },

    /*
    |--------------------------------------------------------------------------
    | Guardar
    |--------------------------------------------------------------------------
    */

    async saveAddress(){
        if (!this.direccion.value.trim()){
            this.showMessage(
                'Selecciona una direccion antes de guardar',
                'error'
            );
            return;
        }
        this.setLoading(true);
        try{
            const response = await fetch(
                this.routes.address.update,
                {
                    method:'POST',
                    credentials: 'same-origin',
                    headers : {
                        'Content-Type':'application/json',
                        'Accept':'application/json',
                        'X-CSRF-TOKEN': this.csrfToken,
                    },
                    body: JSON.stringify({
                        direccion: this.direccion.value,
                        numero: this.numero.value,
                        referencia:this.referencia.value,
                        departamento:this.departamento.value,
                        provincia:this.provincia.value,
                        distrito:this.distrito.value,
                        latitude:this.latitude.value,
                        longitude:this.longitude.value,
                    })
                }
            );
            const json = await response.json();
            if (!response.ok || !json.success){
                throw new Error(
                    json.message ||
                    'No fue posible guardar la direccion'
                );
            }
        
        this.updateAddressCard(
            json.data
        );
        this.hideForm();
        this.showMessage(
            json.message,
            'success'
        );
    }
    catch (error){
        console.error(error);
        this.showMessage(
            error.message,
            'error'
        );
    }
    finally{
        this.setLoading(false);
    }
    },

    updateAddressCard(address){
        this.addressTitle.textContent = [
            address.direccion,
            address.numero,
        ] 
        .filter(Boolean)
        .join(' ');
        this.addressLocation.textContent =[
            address.distrito,
            address.provincia,
            address.departamento,
        ]
        .filter(Boolean)
        .join(', ');
        
        if(address.referencia){
            this.addressReference.textContent = 
            `Referencia: ${address.referencia}`;
            this.addressReference.classList.remove(
                'd-none'
            );
        }
        else{
            this.addressReference.classList.add(
                'd-none'
            );
        }
        this.addressView.classList.remove(
            'd-none'
        );
        this.addressEmpty.classList.add(
            'd-none'
        );
    },

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    setLoading(status){
        this.btnSave.disabled=status;
        this.searchInput.disabled = status;
        if(status){
            this.btnSave.innerHTML = `
            <i class="bi bi-arrow-repeat spinner-border spinner-border-sm"></i>"
            Guardando...`;
        }
        else {
            this.btnSave.innerHTML =`
            <i class="bi bi-check-circle"></i>
            <span>Guardar direccion </span>`;
        }
    },

    showMessage(message, type = 'success'){
        const toast = this.toast;
        const text = this.toastMessage;
        if (!toast || !text){
            console.log(message);
            return;
        }
        text.textContent = message;
        toast.classList.remove('show');
        toast.style.background = type === 'success'
            ? '#16a34a'
            : '#dc2626';
        setTimeout(() => {
        toast.classList.add('show');
        }, 50);
        clearTimeout(this.toastTimer);
        this.toastTimer = setTimeout(() => {
        toast.classList.remove('show');

    }, 3000);
}
};
document.addEventListener(
    'DOMContentLoaded',
    () => Delivery.init()
);

    