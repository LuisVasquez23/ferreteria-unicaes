@extends('layouts/dashboard')
@section('title', 'Editar producto')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Editar información del producto</h5>
        <div class="card-body">
            <form action="{{ route('producto.update', $producto->producto_id) }}" method="post" class="row needs-validation"
                novalidate>
                @csrf
                @method('PUT')



                <div class="form-group col-md-6">
                    <label for="nombre_opcion">Nombre: </label>
                    <input type="text" class="form-control {{ $errors->has('nombre_opcion') ? 'is-invalid' : '' }}"
                        name="nombre_opcion" id="nombre_opcion" value="{{ $producto->nombre }}" required>
                    @if ($errors->has('nombre_opcion'))
                        <div class="invalid-feedback">
                            {{ $errors->first('nombre_opcion') }}
                        </div>
                    @endif
                </div>


                <div class="form-group col-md-6">
                    <label for="descripcion_opcion">Descripcion: </label>
                    <input type="text" class="form-control {{ $errors->has('descripcion_opcion') ? 'is-invalid' : '' }}"
                        name="descripcion_opcion" id="descripcion_opcion" value="{{ $producto->descripcion }}" required>
                    @if ($errors->has('descripcion_opcion'))
                        <div class="invalid-feedback">
                            {{ $errors->first('descripcion_opcion') }}
                        </div>
                    @endif
                </div>

                <div class="col-md-6 mt-2">
                    <div class="form-group">
                        <label for="usuario_id">Proveedor:</label>
                        <select name="usuario_id" id="usuario_id" class="form-control">
                            @foreach ($proveedores as $usuario_id => $nombres)
                                <option value="{{ $usuario_id }}"
                                    {{ $producto->proveedor_id == $usuario_id ? 'selected' : '' }}>
                                    {{ $nombres }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="col-md-6 mt-2">
                    <div class="form-group">
                        <label for="categoria_id">Categoria:</label>
                        <select name="categoria_id" id="categoria_id" class="form-control">
                            @foreach ($categorias as $categoria_id => $nombres)
                                <option value="{{ $categoria_id }}"
                                    {{ $producto->categoria_id == $categoria_id ? 'selected' : '' }}>
                                    {{ $nombres }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="col-md-6 mt-2">
                    <div class="form-group">
                        <label for="estante_id">Estante:</label>
                        <select name="estante_id" id="estante_id" class="form-control">
                            @foreach ($estantes as $estante_id => $estante)
                                <option value="{{ $estante_id }}"
                                    {{ $producto->estante_id == $estante_id ? 'selected' : '' }}>
                                    {{ $estante }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6 mt-2">
                    <div class="form-group">
                        <label for="unidad_medida_id">Unidad de Medida:</label>
                        <select name="unidad_medida_id" id="unidad_medida_id" class="form-control">
                            @foreach ($unidades as $unidad_medida_id => $nombreUnidad)
                                <option value="{{ $unidad_medida_id }}"
                                    {{ $producto->unidad_medida_id == $unidad_medida_id ? 'selected' : '' }}>
                                    {{ $nombreUnidad }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6 mt-2">
                    <div class="form-group">
                        <label for="periodo_id">Período:</label>
                        <select name="periodo_id" id="periodo_id" class="form-control">
                            @foreach ($periodos as $periodo_id => $fecha)
                                <option value="{{ $periodo_id }}"
                                    {{ $producto->periodo_id == $periodo_id ? 'selected' : '' }}>
                                    {{ \Carbon\Carbon::parse($fecha)->format('Y/m/d') }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>



                <div class="form-group col-md-12 mt-3">
                    <input type="submit" class="btn btn-primary" value="Actualizar">
                    <a href="{{ route('productos') }}" class="btn btn-dark">Regresar</a>
                </div>
            </form>
        </div>
    </div>

@endsection
