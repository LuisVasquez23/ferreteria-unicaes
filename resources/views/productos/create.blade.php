@extends('layouts/dashboard') @section('title', 'Ingresar producto')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Ingresar producto</h5>
        <div class="card-body">
            <form action="{{ route('producto.store') }}" method="post" class="row needs-validation"
                enctype="multipart/form-data" novalidate>
                @csrf

                <!-- NOMBRE DEL PRODUCTO -->
                <div class="form-group col-md-6">
                    <label for="nombre_opcion">Nombre: *</label>
                    <input type="text" class="form-control {{ $errors->has('nombre_opcion') ? 'is-invalid' : '' }}"
                        name="nombre_opcion" id="nombre_opcion" required />
                    @if ($errors->has('nombre_opcion'))
                        <div class="invalid-feedback">
                            {{ $errors->first('nombre_opcion') }}
                        </div>
                    @endif
                </div>

                <!-- DESCRIPCION DEL PRODUCTO -->
                <div class="form-group col-md-6">
                    <label for="descripcion_opcion">Descripcion: *</label>
                    <input type="text" class="form-control {{ $errors->has('descripcion_opcion') ? 'is-invalid' : '' }}"
                        name="descripcion_opcion" id="descripcion_opcion" required />
                    @if ($errors->has('descripcion_opcion'))
                        <div class="invalid-feedback">
                            {{ $errors->first('descripcion_opcion') }}
                        </div>
                    @endif
                </div>

                <!-- IMAGEN DEL PRODUCTO -->
                <div class="form-group col-md-6 mt-2">
                    <label for="imagenProducto">Imagen: *</label>

                    <input type="file" class="form-control {{ $errors->has('imagenProducto') ? 'is-invalid' : '' }}"
                        name="imagenProducto" id="imagenProducto" accept="image/*" required />

                    @if ($errors->has('imagenProducto'))
                        <div class="invalid-feedback">
                            {{ $errors->first('imagenProducto') }}
                        </div>
                    @endif
                </div>

                <!-- PROVEEDOR DEL PRODUCTO -->
                <div class="col-md-6 mt-2">
                    <div class="form-group">
                        <label for="usuario_id">Proveedor: *</label>
                        <select name="usuario_id" id="usuario_id" class="form-control">
                            @if ($proveedores->isEmpty())
                                <option value="" disabled selected>
                                    No se encontraron proveedores
                                </option>
                            @else
                                @foreach ($proveedores as $usuario_id => $nombres)
                                    <option value="{{ $usuario_id }}">
                                        {{ $nombres }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <!-- CATEGORIA DEL PRODUCTO -->
                <div class="col-md-6 mt-2">
                    <div class="form-group">
                        <label for="categoria_id">Categoria: *</label>
                        <select name="categoria_id" id="categoria_id" class="form-control">
                            @if ($categorias->isEmpty())
                                <option value="" disabled selected>
                                    No se encontraron categorías
                                </option>
                            @else
                                @foreach ($categorias as $categoria_id => $categoria)
                                    <option value="{{ $categoria_id }}">
                                        {{ $categoria }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <!-- ESTANTE DEL PRODUCTO -->
                <div class="col-md-6 mt-2">
                    <div class="form-group">
                        <label for="estante_id">Estante: *</label>
                        <select name="estante_id" id="estante_id" class="form-control">
                            @if ($estantes->isEmpty())
                                <option value="" disabled selected>
                                    No se encontraron estantes
                                </option>
                            @else
                                @foreach ($estantes as $estante_id => $nombreEstante)
                                    <option value="{{ $estante_id }}">
                                        {{ $nombreEstante }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <!-- UNIDAD DE MEDIDA DEL PRODUCTO -->
                <div class="col-md-6 mt-2">
                    <div class="form-group">
                        <label for="unidad_medida_id">Unidad de Medida: *</label>
                        <select name="unidad_medida_id" id="unidad_medida_id" class="form-control">
                            @if ($unidades->isEmpty())
                                <option value="" disabled selected>
                                    No se encontraron unidades de medida
                                </option>
                            @else
                                @foreach ($unidades as $unidad_medida_id => $nombreUnidad)
                                    <option value="{{ $unidad_medida_id }}">
                                        {{ $nombreUnidad }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <!-- PERIODO DE REGISTRO DEL PRODUCTO -->
                <div class="col-md-6 mt-2">
                    <div class="form-group">
                        <label for="periodo_id">Período: *</label>
                        <select name="periodo_id" id="periodo_id" class="form-control">
                            @if ($periodos->isEmpty())
                                <option value="" disabled selected>
                                    No se encontraron períodos
                                </option>
                            @else
                                @foreach ($periodos as $periodo_id => $fecha)
                                    <option value="{{ $periodo_id }}">
                                        {{ \Carbon\Carbon::parse($fecha)->format('Y/m/d') }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="form-group col-md-12 mt-3">
                    <input type="submit" class="btn btn-primary" value="Ingresar" />
                    <a href="{{ route('productos') }}" class="btn btn-dark">Regresar</a>
                </div>
            </form>
        </div>
    </div>

    @endsection @section('AfterScript')
    <script>
        // DECLARACION DE VARIABLES
        const d = document;
        const vencimientoFechaCheck = d.getElementById("activarFechaVencimiento");
        const vencimientoFechaInput = d.getElementById("fechaVencimientoProducto");

        // DECLARACION DE FUNCIONES
        const onElementChecked = () => {
            vencimientoFechaInput.disabled = vencimientoFechaInput.disabled ?
                false :
                true;
        };

        d.addEventListener("DOMContentLoaded", () => {
            // Asignar el evento al checkbox
            vencimientoFechaCheck.addEventListener("change", onElementChecked);
        });
    </script>
@endsection
