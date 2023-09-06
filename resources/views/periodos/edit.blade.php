@extends('layouts/dashboard')
@section('title', 'Editar Período')
@section('contenido')

<div class="card mt-3">
    <h5 class="card-header">Editar Período</h5>
    <div class="card-body">
        <form action="{{ route('periodos.update', $periodo->periodo_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                        <input type="date" class="form-control {{ $errors->has('fecha_inicio') ? 'is-invalid' : '' }}" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', date('Y-m-d', strtotime($periodo->fecha_inicio))) }}" required>
                        @if ($errors->has('fecha_inicio'))
                            <div class="invalid-feedback">
                                {{ $errors->first('fecha_inicio') }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="fecha_fin" class="form-label">Fecha de Fin</label>
                        <input type="date" class="form-control {{ $errors->has('fecha_fin') ? 'is-invalid' : '' }}" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin', date('Y-m-d', strtotime($periodo->fecha_fin))) }}" required>
                        @if ($errors->has('fecha_fin'))
                            <div class="invalid-feedback">
                                {{ $errors->first('fecha_fin') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-12 mt-3">
                <button type="submit" class="btn btn-primary">Actualizar</button>
                <a href="{{ route('periodos') }}" class="btn btn-dark">Regresar</a>
            </div>
        </form>
    </div>
</div>

@endsection
