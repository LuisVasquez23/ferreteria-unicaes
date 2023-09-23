@extends('layouts/dashboard')
@section('title', 'Editar unidad')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Editar información para la unidad de medida</h5>
        <div class="card-body">
            <form action="{{ route('unidad.update', $unidad->unidad_medida_id) }}" method="post" class="row needs-validation" novalidate>
                @csrf
                @method('PUT')


                <div class="form-group col-md-6">
                    <label for="nombre_opcion">Nombre: </label>
                    <input type="text" class="form-control {{ $errors->has('nombre_opcion') ? 'is-invalid' : '' }}"
                        name="nombre_opcion" id="nombre_opcion" value="{{$unidad->nombre}}" required>
                    @if ($errors->has('nombre_opcion'))
                        <div class="invalid-feedback">
                            {{ $errors->first('nombre_opcion') }}
                        </div>
                    @endif
                </div>
    
             <div class="form-group col-md-6">
                <label for="descripcion_opcion">Descripción: </label>
                <input type="text" class="form-control"
                    name="descripcion_opcion" id="descripcion_opcion" value="{{$unidad->descripcion}}">
            </div>


                <div class="form-group col-md-12 mt-3">
                    <input type="submit" class="btn btn-primary" value="Actualizar">
                    <a href="{{ route('unidades') }}" class="btn btn-dark">Regresar</a>
                </div>
            </form>
        </div>
    </div>

@endsection




