@extends('layouts/dashboard')
@section('title', 'Inventario de productos')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Administración de inventario de productos</h5>
        <div class="card-body">
            <div class="col-md-4 mx-auto text-center mb-3">
                <label class="mb-2" for="filtro-bloqueo">Filtrar por Periodo:</label>
                <select id="filtro-bloqueo" class="form-select">
                    <option>Seleccionar...</option>
                    <option value="no-bloqueados">No Bloqueados</option>
                    <option value="bloqueados">Bloqueados</option>
                </select>
            </div>

            @if($productos->contains(function ($producto) { return $producto->cantidad <= 10; }))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>¡Advertencia!</strong> Hay productos con existencia baja. 
                    <a href="/compras" class="btn btn-warning btn-sm ml-2 ms-1">Ir a Compras</a>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table id="miTabla" class="table text-nowrap mb-0 align-middle table-striped table-bordered">
                    <thead class="text-dark fs-4">
                        <tr>
                            <th class="border-bottom-0">
                                <b>Nombre</b>
                            </th>
                            <th>
                                Imagen
                            </th>
                            <th class="border-bottom-0">
                                <b>Cantidad</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Periodo</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Fecha vencimiento</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Estante</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Unidad de medida</b>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productos as $producto)
                            <tr @if($producto->cantidad <= 10) style="outline: 2px solid #FFCCCC;" @endif>
                                <td class="border-bottom-0" @if($producto->cantidad <= 10) style="border-left: 2px solid #FFCCCC;" @endif>
                                    {{ $producto->nombre }}
                                </td>

                                <!-- Agrega esta celda para mostrar la imagen -->
                                <td class="border-bottom-0">
                                    <img src="{{ asset('storage/upload/productos/' . $producto->img_path) }}"
                                        alt="{{ $producto->nombre }}" class="img-thumbnail" width="100">
                                </td>

                                <td class="border-bottom-0">
                                    {{ $producto->cantidad }}
                                </td>

                                <td class="border-bottom-0">
                                    {{ $producto->periodo->fecha_inicio->format('Y-m-d') }} - {{ $producto->periodo->fecha_fin->format('Y-m-d') }}
                                </td>

                                <td class="border-bottom-0">
                                    {{ $producto->fecha_vencimiento == null ? '' : date('d/m/Y', strtotime($producto->fecha_vencimiento)) }}
                                </td>

                                <td class="border-bottom-0">
                                    {{ $producto->estante->estante }}
                                </td>

      

                                <td class="border-bottom-0" @if($producto->cantidad <= 10) style="border-right: 2px solid #FFCCCC;" @endif>
                                    {{ $producto->medida->nombre }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
