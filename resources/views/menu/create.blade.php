@extends('layouts/dashboard')
@section('title', 'Crear opcion del menu')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Crear opcion menú</h5>
        <div class="card-body">
            <form action="{{ route('menu.store') }}" method="post" class="row needs-validation" novalidate>
                @csrf

                <div class="form-group col-md-4">
                    <label for="nombreOpcion">Nombre: *</label>
                    <input type="text" class="form-control {{ $errors->has('nombreOpcion') ? 'is-invalid' : '' }}"
                        name="nombreOpcion" id="nombreOpcion" required>
                    @if ($errors->has('nombreOpcion'))
                        <div class="invalid-feedback">
                            {{ $errors->first('nombreOpcion') }}
                        </div>
                    @endif
                </div>

                <div class="form-group col-md-4">
                    <label for="direccion">URL: </label>
                    <input type="text" class="form-control" name="direccion" id="direccion" required>
                    <!-- No se muestra mensaje de error para 'direccion' -->
                    @if ($errors->has('direccion'))
                        <div class="invalid-feedback">
                            {{ $errors->first('direccion') }}
                        </div>
                    @endif
                </div>

                <div class="form-group col-md-4">
                    <label for="direccion">Padre: </label>
                    {!! Form::select('parent_id', ['' => 'Seleccionar ...'] + $menusOption->pluck('nombre', 'id')->toArray(), null, [
                        'class' => 'form-control',
                    ]) !!}
                    <!-- No se muestra mensaje de error para 'parent_id' -->
                </div>

                <div class="form-group col-md-4">
                    <label for="role_id">Role: *</label>
                    {!! Form::select('role_id', ['' => 'Seleccionar ...'] + $roles->pluck('role', 'role_id')->toArray(), null, [
                        'class' => 'form-control',
                        'required' => true,
                    ]) !!}

                    @if ($errors->has('role_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('role_id') }}
                        </div>
                    @endif
                </div>

                <div class="form-group col-md-12 mt-3">
                    <input type="submit" class="btn btn-primary" value="Crear">
                    <a href="{{ route('menu') }}" class="btn btn-dark">Regresar</a>
                </div>
            </form>
        </div>
    </div>

@endsection
