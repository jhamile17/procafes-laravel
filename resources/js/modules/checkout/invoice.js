document.addEventListener('DOMContentLoaded', () => {

    const boleta = document.getElementById('boleta');
    const factura = document.getElementById('factura');

    const tipoDocumento = document.getElementById('tipo_documento');

    const documentLabel = document.getElementById('documentLabel');

    const numeroDocumento = document.getElementById('numero_documento');

    const nombre = document.getElementById('nombre');

    const razonSocial = document.getElementById('razon_social');

    const direccionFiscal = document.getElementById('direccion_fiscal');

    const boletaFields = document.getElementById('boletaFields');

    const facturaFields = document.getElementById('facturaFields');

    const documentStatus = document.getElementById('documentStatus');

    /*
    |--------------------------------------------------------------------------
    | Validación
    |--------------------------------------------------------------------------
    */

    if (

        !boleta ||

        !factura ||

        !numeroDocumento

    ) {

        return;

    }

    /*
    |--------------------------------------------------------------------------
    | Variables
    |--------------------------------------------------------------------------
    */

    let debounceTimer = null;

    /*
    |--------------------------------------------------------------------------
    | Inicializar
    |--------------------------------------------------------------------------
    */

    initialize();

    /*
    |--------------------------------------------------------------------------
    | Eventos
    |--------------------------------------------------------------------------
    */

    boleta.addEventListener(
        'change',
        changeDocumentType
    );

    factura.addEventListener(
        'change',
        changeDocumentType
    );

    numeroDocumento.addEventListener(
        'input',
        handleDocumentInput
    );

    /*
    |--------------------------------------------------------------------------
    | Inicializar componente
    |--------------------------------------------------------------------------
    */

    function initialize()
    {
        changeDocumentType();
    }

    /*
    |--------------------------------------------------------------------------
    | Cambiar tipo de comprobante
    |--------------------------------------------------------------------------
    */

    function changeDocumentType()
    {
        numeroDocumento.value = '';

        resetData();

        if (boleta.checked) {

            tipoDocumento.value = 'DNI';

            documentLabel.textContent = 'DNI';

            numeroDocumento.placeholder = 'Ingrese su DNI';

            numeroDocumento.maxLength = 8;

            boletaFields.classList.remove('d-none');

            facturaFields.classList.add('d-none');

            return;

        }

        tipoDocumento.value = 'RUC';

        documentLabel.textContent = 'RUC';

        numeroDocumento.placeholder = 'Ingrese su RUC';

        numeroDocumento.maxLength = 11;

        facturaFields.classList.remove('d-none');

        boletaFields.classList.add('d-none');
    }

    /*
    |--------------------------------------------------------------------------
    | Documento
    |--------------------------------------------------------------------------
    */

    function handleDocumentInput()
    {
        clearTimeout(debounceTimer);

        debounceTimer = setTimeout(() => {

            const documento = numeroDocumento.value.trim();

            if (

                boleta.checked &&

                documento.length !== 8

            ) {

                resetData();

                return;

            }

            if (

                factura.checked &&

                documento.length !== 11

            ) {

                resetData();

                return;

            }

            fetchDocument(documento);

        }, 400);

    }

    /*
    |--------------------------------------------------------------------------
    | Consultar documento
    |--------------------------------------------------------------------------
    */

    async function fetchDocument(documento)
    {
        showLoading();

        const endpoint = boleta.checked

            ? window.Laravel.routes.documents.consultarDni

            : window.Laravel.routes.documents.consultarRuc;

        const payload = boleta.checked

            ? {

                dni: documento,

            }

            : {

                ruc: documento,

            };

        try {

            const response = await fetch(

                endpoint,

                {

                    method: 'POST',

                    headers: {

                        'Accept': 'application/json',

                        'Content-Type': 'application/json',

                        'X-CSRF-TOKEN': window.Laravel.csrfToken,

                    },

                    body: JSON.stringify(payload),

                }

            );

            let result = {};

            try {

                result = await response.json();

            } catch {

                throw new Error(

                    'Respuesta inválida del servidor.'

                );

            }

            if (

                !response.ok ||

                !result.success

            ) {

                showError(

                    result.message ??

                    'No fue posible consultar el documento.'

                );

                resetData();

                return;

            }

            fillDocument(

                result.data

            );

            showSuccess(

                result.message ??

                'Documento encontrado.'

            );

        } catch (error) {

            console.error(error);

            showError(

                'Ocurrió un error al consultar el documento.'

            );

            resetData();

        }

    }

    /*
    |--------------------------------------------------------------------------
    | Completar formulario
    |--------------------------------------------------------------------------
    */

    function fillDocument(data)
    {
        if (boleta.checked) {

            nombre.value =

                data.nombre ?? '';

        } else {

            razonSocial.value =

                data.razon_social ?? '';

        }

        direccionFiscal.value =

            data.direccion_fiscal ?? '';

    }

    /*
    |--------------------------------------------------------------------------
    | Limpiar datos
    |--------------------------------------------------------------------------
    */

    function resetData()
    {
        nombre.value = '';

        razonSocial.value = '';

        direccionFiscal.value = '';

        clearStatus();
    }

    /*
    |--------------------------------------------------------------------------
    | Estado
    |--------------------------------------------------------------------------
    */

    function showLoading()
    {
        documentStatus.className =
            'checkout-document-status loading';

        documentStatus.textContent =
            'Consultando documento...';
    }

    function showSuccess(message)
    {
        documentStatus.className =
            'checkout-document-status success';

        documentStatus.textContent = message;
    }

    function showError(message)
    {
        documentStatus.className =
            'checkout-document-status error';

        documentStatus.textContent = message;
    }

    function clearStatus()
    {
        documentStatus.className =
            'checkout-document-status';

        documentStatus.textContent = '';
    }

});