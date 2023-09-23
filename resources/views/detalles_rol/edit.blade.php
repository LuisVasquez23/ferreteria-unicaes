@extends('layouts/dashboard')
@section('title', 'Editar detalle')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Editar información para el detalle del rol</h5>
        <div class="card-body">
            <form action="{{ route('detalle_rol.update', $detalle->detalle_id) }}" method="post" class="row needs-validation" novalidate>
                @csrf
                @method('PUT')


                <div class="col-md-6">

                    <div class="form-group">
                        <label for="rol_input">Rol:</label>
                        <select name="rol_input" id="rol_input" class="form-control">
                            @foreach($roles as $rolId => $rolNombre)
                                <option value="{{ $rolId }}" {{ $detalle->role_id == $rolId ? 'selected' : '' }}>
                                    {{ $rolNombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                </div>


            <div class="col-md-6">
                <div class="form-group">
                    <label for="usuario_input">Nombre del usuario:</label>
                    <select name="usuario_input" id="usuario_input" class="form-control">
                        @foreach($usuarios as $usuarioId => $nombre_completo)
                            <option value="{{ $usuarioId }}" {{ $detalle->usuario_id == $usuarioId ? 'selected' : '' }}>
                                {{ $nombre_completo }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
    


                <div class="form-group col-md-12 mt-3">
                    <input type="submit" class="btn btn-primary" value="Actualizar">
                    <a href="{{ route('detalles_roles') }}" class="btn btn-dark">Regresar</a>
                </div>
            </form>
        </div>
    </div>

@endsection




