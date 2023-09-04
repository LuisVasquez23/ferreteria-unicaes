@extends('layouts/dashboard')
@section('title', 'Ingresar clientes')
@section('contenido')



<div class="card mt-3">
    <h5 class="card-header">Ingresar Cliente</h5>
    <div class="card-body">
        <form action="{{ route('cliente.store') }}" method="post" class="row needs-validation" novalidate>
            @csrf

            <div class="form-group col-md-4">
                <label for="dui_opcion">DUI: *</label>
                <input type="text" class="form-control {{ $errors->has('dui_opcion') ? 'is-invalid' : '' }}"
                    name="dui_opcion" id="dui_opcion" required>
                @if ($errors->has('dui_opcion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('dui_opcion') }}
                    </div>
                @endif
            </div>


            <div class="form-group col-md-4">
                <label for="nombre_opcion">Nombre: *</label>
                <input type="text" class="form-control {{ $errors->has('nombre_opcion') ? 'is-invalid' : '' }}"
                    name="nombre_opcion" id="nombre_opcion" required>
                @if ($errors->has('nombre_opcion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre_opcion') }}
                    </div>
                @endif
            </div>


            <div class="form-group col-md-4">
                <label for="apellido_opcion">Apellido: *</label>
                <input type="text" class="form-control {{ $errors->has('apellido_opcion') ? 'is-invalid' : '' }}"
                    name="apellido_opcion" id="apellido_opcion" required>
                @if ($errors->has('apellido_opcion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('apellido_opcion') }}
                    </div>
                @endif
            </div>

         <div class="form-group col-md-4 mt-2">
            <label for="telefono_opcion">Teléfono: *</label>
            <input type="text" class="form-control {{ $errors->has('telefono_opcion') ? 'is-invalid' : '' }}"
                name="telefono_opcion" id="telefono_opcion" required>
            @if ($errors->has('telefono_opcion'))
                <div class="invalid-feedback">
                    {{ $errors->first('telefono_opcion') }}
                </div>
            @endif
        </div>

        <div class="col-md-4 mt-2">
            <!-- Departamento -->
            <div class="form-group">
                <label for="departamento">Departamento *</label>
                <select name="departamento" id="departamento" class="form-control" required>
                    <option value=""></option>
                    <!-- Opciones de departamentos aquí -->
                </select>
                @if ($errors->has('departamento'))
                <div class="invalid-feedback">
                    {{ $errors->first('departamento') }}
                </div>
                @endif
            </div>
        </div>
        
        <div class="col-md-4 mt-2">
            <!-- Municipio -->
            <div class="form-group">
                <label for="municipio">Municipio *</label>
                <select name="municipio" id="municipio" class="form-control" required>
                    <option value="">Seleccionar ...</option>
                    <!-- Opciones de municipios aquí -->
                </select>
                @if ($errors->has('municipio'))
                <div class="invalid-feedback">
                    {{ $errors->first('municipio') }}
                </div>
                @endif
            </div>
        </div>
        



        <div class="form-group col-md-6 mt-2">
            <label for="direccion_opcion">Dirección:</label>
            <input type="text" class="form-control"
                name="direccion_opcion" id="direccion_opcion">
        </div>


        <div class="form-group col-md-6 mt-2">
            <label for="email_opcion">Email: *</label>
            <input type="text" class="form-control {{ $errors->has('email_opcion') ? 'is-invalid' : '' }}"
                name="email_opcion" id="email_opcion">
            @if ($errors->has('email_opcion'))
                <div class="invalid-feedback">
                    {{ $errors->first('email_opcion') }}
                </div>
            @endif
        </div>

            <div class="form-group col-md-12 mt-3">
                <input type="submit" class="btn btn-primary" value="Registrar">
                <a href="{{ route('clientes') }}" class="btn btn-dark">Regresar</a>
            </div>
        </form>
    </div>
</div>

@endsection


@section('AfterScript')

<script>

$(document).ready(function() {
    $('#dui_opcion').on('input', function() {
        let dui = $(this).val();
        dui = dui.replace(/\D/g, '');
        if (dui.length >= 8) {
            dui = dui.substr(0, 8) + '-' + dui.substr(8, 1);
        }
        $(this).val(dui);
    });
});


$(document).ready(function() {
    $('#telefono_opcion').on('input', function() {
        let telefono = $(this).val();
        telefono = telefono.replace(/\D/g, ''); 
        if (telefono.length >= 4) {
            telefono = telefono.substr(0, 4) + '-' + telefono.substr(4, 4);
        }
        $(this).val(telefono);
    });
});


const $departamento = document.getElementById('departamento');
        const $municipio = document.getElementById('municipio');
        document.addEventListener('DOMContentLoaded', () => {
            showLoadingModal('departamentos');
            // Cargar los departamentos
            ajaxCountries({
                url: 'https://api.countrystatecity.in/v1/countries/SV/states',
                cbSuccess: async (json) => {
                    await loadDepartamentos({
                        departamentos: json,
                        departamentoAlmacenado: null
                    });
                    hideLoadingModal();
                }
            });


        });

        $departamento.addEventListener('change', () => {

            const selectedOption = $departamento.options[$departamento.selectedIndex];
            const codigoDepartamento = selectedOption.getAttribute('data-iso2');

            showLoadingModal('municipios');

            ajaxCountries({
                url: `https://api.countrystatecity.in/v1/countries/SV/states/${codigoDepartamento}/cities`,
                cbSuccess: async (json) => {
                    await loadMunicipios({
                        municipios: json,
                        municipioAlmacenado: null
                    })
                    hideLoadingModal();
                }
            });

        })
        



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
    </script>
@endsection

