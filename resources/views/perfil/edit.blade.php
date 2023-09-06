@extends('layouts/dashboard')
@section('title', 'Administrar perfil')
@section('contenido')

            <div class="card">
                <h5 class="card-header">Modificar información personal</h5>
                <div class="card-body">
                    <form action="{{ route('perfil.update', $usuario->usuario_id) }}" method="post" class="row needs-validation" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="form-group col-md-6">
                            <label for="nombre_opcion">Nombre: </label>
                            <input type="text" class="form-control {{ $errors->has('nombre_opcion') ? 'is-invalid' : '' }}"
                                name="nombre_opcion" id="nombre_opcion" value="{{$usuario->nombres}}" required>
                            @if ($errors->has('nombre_opcion'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('nombre_opcion') }}
                                </div>
                            @endif
                        </div>


                        
                        <div class="form-group col-md-6">
                            <label for="apellido_opcion">Apellido: </label>
                            <input type="text" class="form-control {{ $errors->has('apellido_opcion') ? 'is-invalid' : '' }}"
                                name="apellido_opcion" id="apellido_opcion" value="{{$usuario->apellidos}}" required>
                            @if ($errors->has('apellido_opcion'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('apellido_opcion') }}
                                </div>
                            @endif
                        </div>

                        <div class="form-group col-md-6 mt-2">
                            <label for="telefono_opcion">Teléfono: </label>
                            <input type="text" class="form-control {{ $errors->has('telefono_opcion') ? 'is-invalid' : '' }}"
                                name="telefono_opcion" id="telefono_opcion" value="{{$usuario->telefono}}" required>
                            @if ($errors->has('telefono_opcion'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('telefono_opcion') }}
                                </div>
                            @endif
                        </div>


                        <div class="form-group col-md-6 mt-2">
                            <label for="email_opcion">Email: </label>
                            <input type="text" class="form-control {{ $errors->has('email_opcion') ? 'is-invalid' : '' }}"
                                name="email_opcion" id="email_opcion" value="{{$usuario->email}}">
                            @if ($errors->has('email_opcion'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('email_opcion') }}
                                </div>
                            @endif
                        </div>

                        <h5 class="text-primary mt-5">Seguridad</h5>
                        <hr>
    
                        

                        <div class="form-group col-md-4">
                            <label for="contraseña_antigua">Contraseña actual: </label>
                            <input type="text" class="form-control {{ $errors->has('contraseña_antigua') ? 'is-invalid' : '' }}"
                                name="contraseña_antigua" id="contraseña_antigua" required>
                            @if ($errors->has('contraseña_antigua'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('contraseña_antigua') }}
                                </div>
                            @endif
                        </div>

                        <div class="form-group col-md-4">
                            <label for="contraseña_nueva">Contraseña nueva: </label>
                            <input type="text" class="form-control {{ $errors->has('contraseña_nueva') ? 'is-invalid' : '' }}"
                                name="contraseña_nueva" id="contraseña_nueva">
                            @if ($errors->has('contraseña_nueva'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('contraseña_nueva') }}
                                </div>
                            @endif
                        </div>

                        <div class="form-group col-md-4">
                            <label for="comprobar_contraseña">Confirmar contraseña: </label>
                            <input type="text" class="form-control {{ $errors->has('comprobar_contraseña') ? 'is-invalid' : '' }}"
                                name="comprobar_contraseña" id="comprobar_contraseña">
                            @if ($errors->has('comprobar_contraseña'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('comprobar_contraseña') }}
                                </div>
                            @endif
                        </div>

                       

                        <div class="form-group col-md-12 mt-3">
                            <input type="submit" class="btn btn-primary" value="Actualizar">
                            <a href="{{ route('dashboard') }}" class="btn btn-dark">Regresar</a>
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