@extends('layouts.dashboard')
@section('title', 'Inventario de productos')

@section('afterCss')
    <style>
        .vencimiento-cercano>*,
        {
        background-color: #ffece6 !important;
        --bs-table-accent-bg: #ffd8d8 !important;
        }

        .baja-existencia>* {
            background-color: #FFF3E6 !important;
            --bs-table-accent-bg: #FFF3E6 !important;
        }

        .color-box {
            width: 30px;
            height: 30px;
            display: inline-block;
            margin-right: 10px;
        }

        /* Estilos para los textos de descripción */
        .description {
            display: inline-block;
            vertical-align: middle;
        }

        .card-content {
            display: flex;
            justify-content: start;
            align-items: center;
            margin: 10px;
            margin-bottom: 20px
        }
    </style>
@endsection

@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Administración de inventario de productos</h5>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mx-auto text-center mb-3">
                    <label class="mb-2" for="filtro-periodo">Filtrar por Periodo:</label>
                    <select id="filtro-periodo" class="form-select">
                        <option value="MostrarTodos">Mostrar todos</option>
                        @foreach ($periodos as $periodo)
                            <option value="{{ $periodo->periodo_id }}">{{ $periodo->fecha_inicio->format('Y-m-d') }} -
                                {{ $periodo->fecha_fin->format('Y-m-d') }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mx-auto text-center mb-3">
                    <label class="mb-2" for="filtro-nombre">Filtrar por Nombre:</label>
                    <select id="filtro-nombre" class="form-select">
                        <option value="MostrarTodos">Mostrar todos</option>
                        @foreach ($productosNombre as $nombre)
                            <option value="{{ $nombre }}" @if (request('nombre') == $nombre) selected @endif>
                                {{ $nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mx-auto text-center mb-3">
                    <label class="mb-2" for="filtro-vencimiento">Filtrar por Fecha de Vencimiento:</label>
                    <select id="filtro-vencimiento" class="form-select">
                        <option value="MostrarTodos">Mostrar todos</option>
                        @foreach ($fechasVencimiento as $fecha)
                            <option value="{{ $fecha }}" @if (request('vencimiento') == $fecha) selected @endif>
                                {{ $fecha }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            @if ($productosConPocaExistencia || $productosConVencimientoCercano)
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>¡Advertencia!</strong>

                    @if ($productosConPocaExistencia)
                        Hay productos con existencia baja.
                    @endif

                    @if ($productosConPocaExistencia && $productosConVencimientoCercano)
                        Además,
                    @endif

                    @if ($productosConVencimientoCercano)
                        Hay productos con fecha de vencimiento cercana.
                    @endif

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
                            <th>
                                <b>Acciones</b>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productosFiltrados as $producto)
                            @php
                                $diasParaVencimiento = null;

                                $primerDetalleCompra = $producto->detalle_compras->first();

                                if ($primerDetalleCompra) {
                                    $fechaVencimiento = $primerDetalleCompra->fecha_vencimiento;

                                    if ($fechaVencimiento) {
                                        $diasParaVencimiento = now()->diffInDays($fechaVencimiento, false);
                                    }
                                }

                            @endphp

                            <tr @if ($producto->cantidad <= 10) class="baja-existencia" @endif
                                @if ($diasParaVencimiento !== null && $diasParaVencimiento <= 10) class="vencimiento-cercano" @endif>

                                <td class="border-bottom-0">
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
                                    {{ $producto->periodo->fecha_inicio->format('Y-m-d') }} -
                                    {{ $producto->periodo->fecha_fin->format('Y-m-d') }}
                                </td>

                                <td class="border-bottom-0">
                                    @isset($producto->detalle_compras)
                                        @php
                                            $primerDetalleCompra = $producto->detalle_compras->first();
                                        @endphp

                                        @if ($primerDetalleCompra && $primerDetalleCompra->fecha_vencimiento)
                                            {{ date('d/m/Y', strtotime($primerDetalleCompra->fecha_vencimiento)) }}
                                        @endif
                                    @endisset

                                </td>

                                <td class="border-bottom-0">
                                    {{ $producto->estante->estante }}
                                </td>

                                <td class="border-bottom-0">
                                    {{ $producto->medida->nombre }}
                                </td>

                                <td style="height:auto !important;">
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-warning"
                                            onclick="openModal('{{ $producto->producto_id }}')">
                                            <i class="fa-solid fa-eye"></i>
                                        </button>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row">
                <div class="col-md-12 d-flex ">
                    <div class="card-content">
                        <div class="color-box" style="background-color: #ffd8d8;"></div>
                        <span class="description">Vencimiento cercano</span>
                    </div>

                    <div class="card-content">
                        <div class="color-box" style="background-color: #FFF3E6;"></div>
                        <span class="description">Poca cantidad de producto</span>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Modal para los lotes -->
    @foreach ($productosFiltrados as $producto)
        <div class="modal fade" id="lotesModal{{ $producto->producto_id }}" tabindex="-1"
            aria-labelledby="lotesModalLabel{{ $producto->producto_id }}" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="lotesModalLabel{{ $producto->producto_id }}">Lotes de
                            {{ $producto->nombre }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Número de Lote</th>
                                    <th>Cantidad</th>
                                    <th>Precio Unitario</th>
                                    <!-- Agrega más columnas si es necesario -->
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($producto->detalle_compras as $detalleCompra)
                                    @php
                                        $cantidadVenta = $detalleCompra->producto->detalle_ventas->where('numero_lote', $detalleCompra->numero_lote)->sum('cantidad');
                                    @endphp
                                    <tr>
                                        <td>{{ $detalleCompra->numero_lote }}</td>
                                        <td>
                                            {{ $detalleCompra->cantidad - $cantidadVenta }}
                                        </td>
                                        <td>{{ number_format($detalleCompra->precioUnitario, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach



@endsection

@section('AfterScript')
    <script>
        function openModal(productId) {
            var modalId = '#lotesModal' + productId;
            $(modalId).modal('show');
        }

        $(document).ready(function() {
            // Obtén el valor del periodo seleccionado de la URL
            var selectedPeriodo = "{{ request('periodo') }}";
            // Obtén el valor del nombre de producto seleccionado de la URL
            var selectedNombre = "{{ request('nombre') }}";
            // Obtén el valor de la fecha de vencimiento seleccionada de la URL
            var selectedVencimiento = "{{ request('vencimiento') }}";

            // Establece el valor seleccionado en el filtro de periodo o deja "Seleccionar..." si no hay valor
            $("#filtro-periodo").val(selectedPeriodo || "MostrarTodos");

            // Establece el valor seleccionado en el filtro de nombre o deja "Seleccionar..." si no hay valor
            $("#filtro-nombre").val(selectedNombre || "MostrarTodos");

            // Establece el valor seleccionado en el filtro de fecha de vencimiento o deja "Seleccionar..." si no hay valor
            $("#filtro-vencimiento").val(selectedVencimiento || "MostrarTodos");

            // Maneja el cambio en el filtro de periodo
            $("#filtro-periodo").on("change", function() {
                updateUrlAndRedirect();
            });

            // Maneja el cambio en el filtro de nombre
            $("#filtro-nombre").on("change", function() {
                updateUrlAndRedirect();
            });

            // Maneja el cambio en el filtro de fecha de vencimiento
            $("#filtro-vencimiento").on("change", function() {
                updateUrlAndRedirect();
            });

            // Función para construir la URL con los parámetros y redirigir
            function updateUrlAndRedirect() {
                var periodo = $("#filtro-periodo").val();
                var nombre = $("#filtro-nombre").val();
                var vencimiento = $("#filtro-vencimiento").val();
                var url = "{{ route('inventario.index') }}";

                // Agrega el parámetro de periodo solo si no es "MostrarTodos"
                if (periodo !== "MostrarTodos") {
                    url += "?periodo=" + periodo;
                }

                // Agrega el parámetro de nombre solo si no es "MostrarTodos"
                if (nombre !== "MostrarTodos") {
                    url += (periodo !== "MostrarTodos" ? "&" : "?") + "nombre=" + nombre;
                }

                // Agrega el parámetro de fecha de vencimiento solo si no es "MostrarTodos"
                if (vencimiento !== "MostrarTodos") {
                    url += ((periodo !== "MostrarTodos" || nombre !== "MostrarTodos") ? "&" : "?") +
                        "vencimiento=" + vencimiento;
                }

                window.location.href = url;
            }
        });
    </script>
@endsection
