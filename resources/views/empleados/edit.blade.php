@extends('layouts/dashboard')
@section('title', 'Editar cliente')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Editar información del cliente</h5>
        <div class="card-body">
            <form action="{{ route('empleados.update', $empleados->usuario_id) }}" method="post" class="row needs-validation" novalidate>
                @csrf
                @method('PUT')


                <div class="form-group col-md-4">
                    <label for="dui_opcion">DUI: </label>
                    <input type="text" class="form-control" style="background-color: #f5f5f5; border: 1px solid #ddd; color: #888;"
                        name="dui_opcion" id="dui_opcion" value="{{$empleados->dui}}" readonly>
                </div>

                <div class="form-group col-md-4">
                    <label for="nombre_opcion">Nombre: </label>
                    <input type="text" class="form-control {{ $errors->has('nombre_opcion') ? 'is-invalid' : '' }}"
                        name="nombre_opcion" id="nombre_opcion" value="{{$empleados->nombres}}" required>
                    @if ($errors->has('nombre_opcion'))
                        <div class="invalid-feedback">
                            {{ $errors->first('nombre_opcion') }}
                        </div>
                    @endif
                </div>


                <div class="form-group col-md-4">
                    <label for="apellido_opcion">Apellido: </label>
                    <input type="text" class="form-control {{ $errors->has('apellido_opcion') ? 'is-invalid' : '' }}"
                        name="apellido_opcion" id="apellido_opcion" value="{{$empleados->apellidos}}" required>
                    @if ($errors->has('apellido_opcion'))
                        <div class="invalid-feedback">
                            {{ $errors->first('apellido_opcion') }}
                        </div>
                    @endif
                </div>
    
             <div class="form-group col-md-4 mt-2">
                <label for="telefono_opcion">Teléfono: </label>
                <input type="text" class="form-control {{ $errors->has('telefono_opcion') ? 'is-invalid' : '' }}"
                    name="telefono_opcion" id="telefono_opcion" value="{{$empleados->telefono}}" required>
                @if ($errors->has('telefono_opcion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('telefono_opcion') }}
                    </div>
                @endif
            </div>

            <div class="form-group col-md-4 mt-2">
                <label for="direccion_opcion">Dirección:</label>
                <input type="text" class="form-control"
                    name="direccion_opcion" id="direccion_opcion" value="{{$empleados->direccion}}">
            </div>
    
    
            <div class="form-group col-md-4 mt-2">
                <label for="email_opcion">Email: *</label>
                <input type="text" class="form-control {{ $errors->has('email_opcion') ? 'is-invalid' : '' }}"
                    name="email_opcion" id="email_opcion" value="{{$empleados->email}}"
                    pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}">
                @if ($errors->has('email_opcion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email_opcion') }}
                    </div>
                @endif
            </div>

            <div class="form-group col-md-4 mt-3">
                <label for="password">Contraseña actual: </label>
                <input type="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                    name="password" id="password" required>
                @if ($errors->has('password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                @endif
            </div>

            <div class="form-group col-md-4 mt-3">
                <label for="new_password">Contraseña nueva: </label>
                <input type="password" class="form-control {{ $errors->has('new_password') ? 'is-invalid' : '' }}"
                    name="new_password" id="new_password">
                @if ($errors->has('new_password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('new_password') }}
                    </div>
                @endif
            </div>

            <div class="form-group col-md-4 mt-3">
                <label for="confirm_password">Confirmar contraseña: </label>
                <input type="password" class="form-control {{ $errors->has('confirm_password') ? 'is-invalid' : '' }}"
                    name="confirm_password" id="confirm_password">
                @if ($errors->has('confirm_password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('confirm_password') }}
                    </div>
                @endif
            </div>
             

                <div class="form-group col-md-12 mt-3">
                    <input type="submit" class="btn btn-primary" value="Actualizar">
                    <a href="{{ route('empleados.index') }}" class="btn btn-dark">Regresar</a>
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
 
    </script>
@endsection