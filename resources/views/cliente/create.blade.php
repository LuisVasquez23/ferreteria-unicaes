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

         <div class="form-group col-md-6 mt-2">
            <label for="telefono_opcion">Teléfono: *</label>
            <input type="text" class="form-control {{ $errors->has('telefono_opcion') ? 'is-invalid' : '' }}"
                name="telefono_opcion" id="telefono_opcion" required>
            @if ($errors->has('telefono_opcion'))
                <div class="invalid-feedback">
                    {{ $errors->first('telefono_opcion') }}
                </div>
            @endif
        </div>

        <div class="form-group col-md-6 mt-2">
                    <label for="email_opcion">Email: *</label>
                    <input type="text" class="form-control {{ $errors->has('email_opcion') ? 'is-invalid' : '' }}"
                        name="email_opcion" id="email_opcion" required
                        pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}"
                        >
                    @if ($errors->has('email_opcion'))
                        <div class="invalid-feedback">
                            {{ $errors->first('email_opcion') }}
                        </div>
                    @endif
                </div>

        <div class="form-group col-md-12 mt-2">
            <label for="direccion_opcion">Dirección:</label>
            <input type="text" class="form-control {{ $errors->has('direccion_opcion') ? 'is-invalid' : '' }} "
                name="direccion_opcion" id="direccion_opcion" required>
                @if ($errors->has('direccion_opcion'))
                        <div class="invalid-feedback">
                            {{ $errors->first('direccion_opcion') }}
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



//validar DUI
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

