@extends('layouts/dashboard')
@section('title', 'Ingresar Proveedor')
@section('contenido')

<div class="card mt-3">
    <h5 class="card-header">Ingresar Proveedor</h5>
    <div class="card-body">
        <form action="{{ route('proveedores.store') }}" method="post" class="row needs-validation" novalidate>
            @csrf

            <div class="form-group col-md-4">
                <label for="nit_opcion">NIT/DUI: *</label>
                <input type="text" class="form-control {{ $errors->has('nit_opcion') ? 'is-invalid' : '' }}"
                    name="nit_opcion" id="nit_opcion" required>
                @if ($errors->has('nit_opcion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nit_opcion') }}
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
                <label for="direccion_opcion">Dirección:</label>
                <input type="text" class="form-control"
                    name="direccion_opcion" id="direccion_opcion">
            </div>

            <div class="form-group col-md-6 mt-2">
                <label for="email_opcion">Email: *</label>
                <input type="text" class="form-control {{ $errors->has('email_opcion') ? 'is-invalid' : '' }}"
                    name="email_opcion" id="email_opcion" required>
                @if ($errors->has('email_opcion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email_opcion') }}
                    </div>
                @endif
            </div>

            <div class="form-group col-md-12 mt-3">
                <input type="submit" class="btn btn-primary" value="Registrar">
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



</script>
@endsection
