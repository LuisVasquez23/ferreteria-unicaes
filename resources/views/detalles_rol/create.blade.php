@extends('layouts/dashboard')
@section('title', 'Crear detalle del rol')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Crear detalle para el rol</h5>
        <div class="card-body">
            <form action="{{ route('detalle_rol.store') }}" method="post" class="row needs-validation" novalidate>
                @csrf

                <div class="col-md-6">

                    <div class="form-group">
                        <label for="rol_input">Rol: *</label>
                        <select name="rol_input" id="rol_input" class="form-control">
                            @foreach($roles as $rolId => $rolNombre)
                                <option value="{{ $rolId }}">
                                    {{ $rolNombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>


                <div class="col-md-6">

                    <div class="form-group">
                        <label for="usuario_input">Nombre del usuario: *</label>
                        <select name="usuario_input" id="usuario_input" class="form-control">
                            @foreach($usuarios as $usuarioId => $nombre_completo)
                                <option value="{{ $usuarioId }}">
                                    {{ $nombre_completo }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>

                
         

                <div class="form-group col-md-12 mt-3">
                    <input type="submit" class="btn btn-primary" value="Crear detalle">
                    <a href="{{ route('detalles_roles') }}" class="btn btn-dark">Regresar</a>
                </div>
            </form>
        </div>
    </div>

@endsection
