 // Definir la función ajaxCountries
 function ajaxCountries(props) {
    const API_KEY = 'Z2NFeGlpTXY2WDJwM05hdkhYQkxHRXdjdDdTVktXeDVSeW1LMmdQOA==';

    // Destructurando las props
    let {
        url,
        cbSuccess
    } = props;

    // Declarando los headers y requestOptions
    let headers = new Headers();
    headers.append("X-CSCAPI-KEY", API_KEY);

    let requestOptions = {
        method: 'GET',
        headers: headers,
        redirect: 'follow'
    };

    // Realizar la petición fetch
    fetch(url, requestOptions)
        .then(res => res.ok ? res.json() : Promise.reject(res))
        .then(json => cbSuccess(json))
        .catch(err => {
            let mensaje = err.statusText || 'Error de la petición a la API';
            console.log(mensaje);
            Swal.close();
        });
}

    function loadDepartamentos(props) {

            let {departamentos , departamentoGuardado} = props;

            const $fragment = document.createDocumentFragment();

            $departamento.innerHTML = '';

            departamentos.unshift({
                id: '',
                name: 'Seleccionar ...'
            });


            departamentos.forEach(departamento => {
                const $option = document.createElement("option");
                $option.value = departamento.name.replace('Department', '');
                $option.textContent = departamento.name.replace('Department', '');
                $option.setAttribute('data-iso2' , departamento.iso2);

                let isSelected = (departamentoGuardado != null && departamentoGuardado === departamento.name.replace('Department', '').trim())

                if(isSelected){
                $option.setAttribute('selected' , isSelected);
                }

                $fragment.appendChild($option);
            });

            $departamento.appendChild($fragment);

            }

            function loadMunicipios(props) {

            let {municipios , municipioAlmacenado} = props;

            const $fragment = document.createDocumentFragment();

            $municipio.innerHTML = '';

            municipios.unshift({
            id: '',
            name: 'Seleccionar ...'
            });

            municipios.forEach(municipio => {
            const $option = document.createElement("option");
            $option.value = municipio.name;
            $option.textContent = municipio.name;

            let isSelected = (municipioAlmacenado != null && municipioAlmacenado === municipio.name.trim())

            if(isSelected){
                $option.setAttribute('selected' , isSelected);
            }


            $fragment.appendChild($option);
            });

            $municipio.appendChild($fragment);

            }

            function showLoadingModal(mensaje) {
            Swal.fire({
            title: 'Cargando ' + mensaje+' ...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            },
            });
            }
            // Función para ocultar el modal de carga
            function hideLoadingModal() {
            Swal.close();
            }