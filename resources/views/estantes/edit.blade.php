@extends('layouts/dashboard')
@section('title', 'Editar estante')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Editar información del estante</h5>
        <div class="card-body">
            <form action="{{ route('estante.update', $estante->estante_id) }}" method="post" class="row needs-validation" novalidate>
                @csrf
                @method('PUT')


                <div class="form-group col-md-4">
                    <label for="estante_opcion">Estante: </label>
                    <input type="text" class="form-control {{ $errors->has('estante_opcion') ? 'is-invalid' : '' }}"
                        name="estante_opcion" id="estante_opcion" value="{{$estante->estante}}" required>
                    @if ($errors->has('estante_opcion'))
                        <div class="invalid-feedback">
                            {{ $errors->first('estante_opcion') }}
                        </div>
                    @endif
                </div>


                <div class="form-group col-md-4">
                    <label for="ubicacion_opcion">Ubicación:</label>
                    <input type="text" class="form-control {{ $errors->has('ubicacion_opcion') ? 'is-invalid' : '' }}"
                        name="ubicacion_opcion" id="ubicacion_opcion" value="{{$estante->ubicacion}}" required>
                    @if ($errors->has('ubicacion_opcion'))
                        <div class="invalid-feedback">
                            {{ $errors->first('ubicacion_opcion') }}
                        </div>
                    @endif
                </div>
    
             <div class="form-group col-md-4">
                <label for="descripcion_opcion">Descripción: </label>
                <input type="text" class="form-control"
                    name="descripcion_opcion" id="descripcion_opcion" value="{{$estante->descripcion}}">
            </div>


                <div class="form-group col-md-12 mt-3">
                    <input type="submit" class="btn btn-primary" value="Actualizar">
                    <a href="{{ route('estantes') }}" class="btn btn-dark">Regresar</a>
                </div>
            </form>
        </div>
    </div>

@endsection




