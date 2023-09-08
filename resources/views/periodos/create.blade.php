@extends('layouts/dashboard')
@section('title', 'Crear  Período')
@section('contenido')

<div class="card mt-3">
    <h5 class="card-header">Crear Nuevo Período</h5>
    <div class="card-body">
        <form action="{{ route('periodos.store') }}" method="POST" class="row needs-validation" novalidate>
            @csrf

            <div class="form-group col-md-6">
                <label for="fecha_inicio">Fecha de Inicio: *</label>
                <input type="date" class="form-control {{ $errors->has('fecha_inicio') ? 'is-invalid' : '' }}" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio') }}" required>
                @if ($errors->has('fecha_inicio'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fecha_inicio') }}
                    </div>
                @endif
            </div>

            <div class="form-group col-md-6">
                <label for="fecha_fin">Fecha de Fin: *</label>
                <input type="date" class="form-control {{ $errors->has('fecha_fin') ? 'is-invalid' : '' }}" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin') }}" required>
                @if ($errors->has('fecha_fin'))
                    <div class="invalid-feedback">
                        {{ $errors->first('fecha_fin') }}
                    </div>
                @endif
            </div>

            <div class="col-md-12 mt-3">
                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="{{ route('periodos') }}" class="btn btn-dark">Regresar</a>
            </div>
        </form>
    </div>
</div>

@endsection
