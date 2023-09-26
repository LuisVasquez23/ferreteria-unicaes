@extends('layouts/dashboard')
@section('title', 'Ingresar unidades de medida')
@section('contenido')



<div class="card mt-3">
    <h5 class="card-header">Ingresar unidad de medida</h5>
    <div class="card-body">
        <form action="{{ route('unidad.store') }}" method="post" class="row needs-validation" novalidate>
            @csrf


            <div class="form-group col-md-6">
                <label for="unidad_opcion">Nombre: *</label>
                <input type="text" class="form-control {{ $errors->has('unidad_opcion') ? 'is-invalid' : '' }}"
                    name="unidad_opcion" id="unidad_opcion" required>
                @if ($errors->has('unidad_opcion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('unidad_opcion') }}
                    </div>
                @endif
            </div>



        <div class="form-group col-md-6">
            <label for="descripcion_opcion">Descripción:</label>
            <input type="text" class="form-control"
                name="descripcion_opcion" id="descripcion_opcion">
        </div>


   

            <div class="form-group col-md-12 mt-3">
                <input type="submit" class="btn btn-primary" value="Registrar">
                <a href="{{ route('unidades') }}" class="btn btn-dark">Regresar</a>
            </div>
        </form>
    </div>
</div>

@endsection
