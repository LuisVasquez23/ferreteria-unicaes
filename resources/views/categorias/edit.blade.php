@extends('layouts/dashboard')
@section('title', 'Editar Categoría')
@section('contenido')

<div class="card mt-3">
    <h5 class="card-header">Editar Categoría</h5>
    <div class="card-body">
        <form action="{{ route('categorias.update', $categoria->categoria_id) }}" method="post" class="row needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="form-group col-md-4">
                <label for="categoria">Nombre: *</label>
                <input type="text" class="form-control {{ $errors->has('categoria') ? 'is-invalid' : '' }}"
                    name="categoria" id="categoria" value="{{ $categoria->categoria }}" required>
                @if ($errors->has('categoria'))
                    <div class="invalid-feedback">
                        {{ $errors->first('categoria') }}
                    </div>
                @endif
            </div>


            <div class="form-group col-md-8">
                <label for="descripcion">Descripción:</label>
                <textarea class="form-control {{ $errors->has('descripcion') ? 'is-invalid' : '' }}" name="descripcion" id="descripcion">{{ $categoria->descripcion }}</textarea>
                @if ($errors->has('descripcion'))
                    <div class="invalid-feedback">
                        {{ $errors->first('descripcion') }}
                    </div>
                @endif
            </div>

            <div class="form-group col-md-12 mt-3">
                <input type="submit" class="btn btn-primary" value="Actualizar">
                <a href="{{ route('categorias') }}" class="btn btn-dark">Regresar</a>
            </div>
        </form>
    </div>
</div>

@endsection
