@extends('layouts/dashboard')
@section('title', 'Editar cliente')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Editar información del cliente</h5>
        <div class="card-body">
            <form action="{{ route('cliente.update', $usuario->usuario_id) }}" method="post" class="row needs-validation" novalidate>
                @csrf
                @method('PUT')


                <div class="form-group col-md-4">
                    <label for="dui_opcion">DUI: </label>
                    <input type="text" class="form-control" style="background-color: #f5f5f5; border: 1px solid #ddd; color: #888;"
                        name="dui_opcion" id="dui_opcion" value="{{$usuario->dui}}" readonly>
                </div>

                <div class="form-group col-md-4">
                    <label for="nombre_opcion">Nombre: </label>
                    <input type="text" class="form-control {{ $errors->has('nombre_opcion') ? 'is-invalid' : '' }}"
                        name="nombre_opcion" id="nombre_opcion" value="{{$usuario->nombres}}" required>
                    @if ($errors->has('nombre_opcion'))
                        <div class="invalid-feedback">
                            {{ $errors->first('nombre_opcion') }}
                        </div>
                    @endif
                </div>


                <div class="form-group col-md-4">
                    <label for="apellido_opcion">Apellido: </label>
                    <input type="text" class="form-control {{ $errors->has('apellido_opcion') ? 'is-invalid' : '' }}"
                        name="apellido_opcion" id="apellido_opcion" value="{{$usuario->apellidos}}" required>
                    @if ($errors->has('apellido_opcion'))
                        <div class="invalid-feedback">
                            {{ $errors->first('apellido_opcion') }}
                        </div>
                    @endif
                </div>
    
             <div class="form-group col-md-4 mt-2">
                <label for="telefono_opcion">Teléfono: </label>
                <input type="text" class="form-control {{ $errors->has('telefono_opcion') ? 'is-invalid' : '' }}"
                    name="telefono_opcion" id="telefono_opcion" value="{{$usuario->telefono}}" required>
                @if ($errors->has('telefono_opcion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('telefono_opcion') }}
                    </div>
                @endif
            </div>


            <div class="col-md-4 mt-2">
                <!-- Departamento -->
                <div class="form-group">
                    <label for="departamento">Departamento</label>
                    <select name="departamento" id="departamento" class="form-control">
                    </select>

                    <input type="hidden" name="departamento_antiguo" id="departamento_antiguo"
                        class="form-control" value="{{ $usuario->departamento }}">

                </div>
            </div>

            <div class="col-md-4 mt-2">
                <!-- Municipio -->
                <div class="form-group">
                    <label for="municipio">Municipio</label>
                    <select name="municipio" id="municipio" class="form-control">
                        <option value="{{ $usuario->municipio }}" hidden></option>
                    </select>

                    <input type="hidden" name="municipio_antiguo" id="municipio_antiguo"
                        class="form-control" value="{{ $usuario->municipio }}">
                </div>
            </div>


            <div class="form-group col-md-6 mt-2">
                <label for="direccion_opcion">Dirección:</label>
                <input type="text" class="form-control"
                    name="direccion_opcion" id="direccion_opcion" value="{{$usuario->direccion}}">
            </div>
    
    
            <div class="form-group col-md-6 mt-2">
                <label for="email_opcion">Email: *</label>
                <input type="text" class="form-control {{ $errors->has('email_opcion') ? 'is-invalid' : '' }}"
                    name="email_opcion" id="email_opcion" value="{{$usuario->email}}"
                    pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}">
                @if ($errors->has('email_opcion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email_opcion') }}
                    </div>
                @endif
            </div>

             

                <div class="form-group col-md-12 mt-3">
                    <input type="submit" class="btn btn-primary" value="Actualizar">
                    <a href="{{ route('clientes') }}" class="btn btn-dark">Regresar</a>
                </div>
            </form>
        </div>
    </div>

@endsection




@section('AfterScript')
    <script>

        //validar numero de telefono
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