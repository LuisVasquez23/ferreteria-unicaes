@extends('layouts/dashboard')
@section('title', 'Editar rol')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Editar información del rol</h5>
        <div class="card-body">
            <form action="{{ route('rol.update', $rol->role_id) }}" method="post" class="row needs-validation" novalidate>
                @csrf
                @method('PUT')


                <div class="form-group col-md-6">
                    <label for="nombreOpcion">Nombre: </label>
                    <input type="text" class="form-control {{ $errors->has('nombreOpcion') ? 'is-invalid' : '' }}"
                        name="nombreOpcion" id="nombreOpcion" value="{{$rol->role}}" readonly>
                    @if ($errors->has('nombreOpcion'))
                        <div class="invalid-feedback">
                            {{ $errors->first('nombreOpcion') }}
                        </div>
                    @endif
                </div>


                <div class="form-group col-md-6">
                    <label for="descripcion_opcion">Descripción: *</label>
                    <input type="text" class="form-control {{ $errors->has('descripcion_opcion') ? 'is-invalid' : '' }}"
                        name="descripcion_opcion" id="descripcion_opcion" value="{{$rol->descripcion}}" required>
                    @if ($errors->has('descripcion_opcion'))
                        <div class="invalid-feedback">
                            {{ $errors->first('descripcion_opcion') }}
                        </div>
                    @endif
                </div>
    


                <div class="form-group col-md-12 mt-3">
                    <input type="submit" class="btn btn-primary" value="Actualizar">
                    <a href="{{ route('roles') }}" class="btn btn-dark">Regresar</a>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('AfterScript')

<style>
    .form-control[readonly] {
        background-color: #f7f7f7; 
        color: #555; 
        cursor: not-allowed;
    }
</style>

@endsection


