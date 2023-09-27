@extends('layouts/dashboard')
@section('title', 'Editar Proveedor')
@section('contenido')

<div class="card mt-3">
    <h5 class="card-header">Editar Proveedor</h5>
    <div class="card-body">
        <form action="{{ route('proveedores.update', $proveedor->usuario_id) }}" method="POST" class="row needs-validation" novalidate>
            @csrf
            @method('PUT') <!-- Indica que es una solicitud PUT para actualizar el proveedor -->

            <div class="form-group col-md-4">
                <label for="nit_opcion">NIT: *</label>
                <input type="text" class="form-control {{ $errors->has('nit_opcion') ? 'is-invalid' : '' }}"
                    name="nit_opcion" id="nit_opcion" value="{{ old('nit_opcion', $proveedor->nit) }}" required>
                @if ($errors->has('nit_opcion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nit_opcion') }}
                    </div>
                @endif
            </div>

            <div class="form-group col-md-4">
                <label for="nombre_opcion">Nombre: *</label>
                <input type="text" class="form-control {{ $errors->has('nombre_opcion') ? 'is-invalid' : '' }}"
                    name="nombre_opcion" id="nombre_opcion" value="{{ old('nombre_opcion', $proveedor->nombres) }}" required>
                @if ($errors->has('nombre_opcion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nombre_opcion') }}
                    </div>
                @endif
            </div>

            <div class="form-group col-md-4">
                <label for="telefono_opcion">Teléfono: *</label>
                <input type="text" class="form-control {{ $errors->has('telefono_opcion') ? 'is-invalid' : '' }}"
                    name="telefono_opcion" id="telefono_opcion" value="{{ old('telefono_opcion', $proveedor->telefono) }}" required>
                @if ($errors->has('telefono_opcion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('telefono_opcion') }}
                    </div>
                @endif
            </div>

            <div class="col-md-6 mt-2">
                <!-- Departamento -->
                <div class="form-group">
                    <label for="departamento">Departamento *</label>
                    <select name="departamento" id="departamento" class="form-control{{ $errors->has('municipio') ? 'is-invalid' : '' }}" required>
                        <!-- Opciones de departamentos aquí -->
                    </select>
                    <input type="hidden" name="departamento_antiguo" id="departamento_antiguo"
                        class="form-control" value="{{$proveedor->departamento }}">

                </div>
            </div>

            <div class="col-md-6 mt-2">
                <!-- Municipio -->
                <div class="form-group">
                    <label for="municipio">Municipio *</label>
                    <select name="municipio" id="municipio" class="form-control {{ $errors->has('municipio') ? 'is-invalid' : '' }}" required>
                        <option value="{{ $proveedor->municipio }}" hidden></option>
                        <input type="hidden" name="municipio_antiguo" id="municipio_antiguo"
                        class="form-control" value="{{ $proveedor->municipio }}">
                    </select>
            
                </div>
            </div>

            <div class="form-group col-md-6 mt-2">
                <label for="direccion_opcion">Dirección:</label>
                <input type="text" class="form-control"
                    name="direccion_opcion" id="direccion_opcion" value="{{ old('direccion_opcion', $proveedor->direccion) }}">
            </div>

            <div class="form-group col-md-6 mt-2">
                <label for="email_opcion">Email: *</label>
                <input type="text" class="form-control {{ $errors->has('email_opcion') ? 'is-invalid' : '' }}"
                    name="email_opcion" id="email_opcion" value="{{ old('email_opcion', $proveedor->email) }}" required>
                @if ($errors->has('email_opcion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email_opcion') }}
                    </div>
                @endif
            </div>

            <div class="form-group col-md-12 mt-3">
                <input type="submit" class="btn btn-primary" value="Actualizar">
                <a href="{{ route('proveedores.index') }}" class="btn btn-dark">Regresar</a>
            </div>
        </form>
    </div>
</div>

@endsection

@section('AfterScript')

<script>
    // Validar NIT
    $(document).ready(function() {
    $('#nit_opcion').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');

        if (value.length <= 8) {
            // Formato DUI (########)
            value = value.substr(0, 8);
        } else if (value.length === 9) {
            // Formato DUI con guion (########-#)
            value = value.substr(0, 8) + '-' + value.substr(8, 1);
        } else if (value.length > 9 && value.length <= 14) {
            // Formato NIT (####-######-###-#)
            value = value.substr(0, 4) + '-' + value.substr(4, 6) + '-' + value.substr(10, 3) + '-' + value.substr(13, 1);
        } else if (value.length > 14) {
            // Limitar la longitud a 14 dígitos
            value = value.substr(0, 4) + '-' + value.substr(4, 6) + '-' + value.substr(10, 3) + '-' + value.substr(13, 1);
        }

        $(this).val(value);
    });
});

    // Validar número de teléfono
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
        const departamentoAlmacenado = document.getElementById('departamento_antiguo').value;
        const municipioAlmacenado = document.getElementById('municipio_antiguo').value;


        document.addEventListener('DOMContentLoaded', async () => {
            showLoadingModal('datos');

            // Cargar los departamentos
            await ajaxCountries({
                url: 'https://api.countrystatecity.in/v1/countries/SV/states',
                cbSuccess: async (json) => {
                    await loadDepartamentos({
                        departamentos: json,
                        departamentoGuardado: departamentoAlmacenado ?? null
                    });

                    // Verificar si hay una opción seleccionada en el select de departamentos
                    if ($departamento.selectedIndex !== -1) {
                        // Obtener el atributo 'data-iso2' del departamento seleccionado previamente
                        const selectedOption = $departamento.options[$departamento.selectedIndex];
                        const codigoDepartamento = selectedOption.getAttribute('data-iso2');

                        // Cargar los municipios del departamento seleccionado previamente
                        ajaxCountries({
                            url: `https://api.countrystatecity.in/v1/countries/SV/states/${codigoDepartamento}/cities`,
                            cbSuccess: async (json) => {
                                await loadMunicipios({
                                    municipios: json,
                                    municipioAlmacenado: municipioAlmacenado ??
                                        null
                                });
                                hideLoadingModal();
                            }
                        });
                    }

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
                        municipioAlmacenado: municipioAlmacenado ?? null
                    })
                    hideLoadingModal();
                }
            });

        });

</script>
@endsection
