@extends('layouts/dashboard')
@section('title', 'Inventario de productos')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Administración de inventario de productos</h5>
        <div class="card-body">

            <div class="row">
                <div class="col-md-4 mx-auto text-center mb-3">
                    <label class="mb-2" for="filtro-periodo">Filtrar por Periodo:</label>
                    <select id="filtro-periodo" class="form-select">
                        <option>Seleccionar...</option>
                        @foreach ($periodos as $periodo)
                            <option value="{{ $periodo->periodo_id }}">{{ $periodo->fecha_inicio->format('Y-m-d') }} - {{ $periodo->fecha_fin->format('Y-m-d') }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mx-auto text-center mb-3">
                    <label class="mb-2" for="filtro-nombre">Filtrar por Nombre:</label>
                    <select id="filtro-nombre" class="form-select">
                        <option>Seleccionar...</option>
                        @foreach ($productosNombre as $nombre)
                            <option value="{{ $nombre }}" @if(request('nombre') == $nombre) selected @endif>{{ $nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mx-auto text-center mb-3">
                    <label class="mb-2" for="filtro-vencimiento">Filtrar por Fecha de Vencimiento:</label>
                    <select id="filtro-vencimiento" class="form-select">
                        <option>Seleccionar...</option>
                        @foreach ($fechasVencimiento as $fecha)
                            <option value="{{ $fecha }}" @if(request('vencimiento') == $fecha) selected @endif>{{ $fecha }}</option>
                        @endforeach
                    </select>
                </div>
                
            </div>

            @if($productosFiltrados->contains(function ($producto) { 
                $diasParaVencimiento = $producto->fecha_vencimiento ? now()->diffInDays($producto->fecha_vencimiento, false) : null;
                return $producto->cantidad <= 10 || ($diasParaVencimiento !== null && $diasParaVencimiento <= 10);
            }))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>¡Advertencia!</strong>
                    @if($productosFiltrados->contains(function ($producto) { return $producto->cantidad <= 10; }))
                        Hay productos con existencia baja.
                    @endif
            
                    @if($productosFiltrados->contains(function ($producto) { 
                        $diasParaVencimiento = $producto->fecha_vencimiento ? now()->diffInDays($producto->fecha_vencimiento, false) : null;
                        return $diasParaVencimiento !== null && $diasParaVencimiento <= 10;
                    }))
                        @if($productosFiltrados->contains(function ($producto) { return $producto->cantidad <= 10; })) 
                            Además,
                        @endif
                        hay productos con fecha de vencimiento cercana.
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
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($productosFiltrados as $producto)
                            @php
                                $diasParaVencimiento = null;
                        
                                if ($producto->fecha_vencimiento) {
                                    $diasParaVencimiento = now()->diffInDays($producto->fecha_vencimiento, false);
                                }
                            @endphp
                        
                            <tr @if($producto->cantidad <= 10) style="outline: 2px solid #E07164;" @endif
                                @if ($diasParaVencimiento !== null && $diasParaVencimiento <= 10) style="outline: 2px solid #E0BA79;" @endif>
                                <td class="border-bottom-0" 
                                    style="
                                        @if($producto->cantidad <= 10) 
                                            border-left: 2px solid #E07164;
                                        @endif
                                        @if($diasParaVencimiento !== null && $diasParaVencimiento <= 10)
                                            border-left: 2px solid #E0BA79; /* Cambia el color a tu preferencia */
                                        @endif
                                    "
                                >
                                    {{ $producto->nombre }}
                                </td>
                        
                                <!-- Agrega esta celda para mostrar la imagen -->
                                <td class="border-bottom-0">
                                    <img src="{{ asset('storage/upload/productos/' . $producto->img_path) }}" alt="{{ $producto->nombre }}" class="img-thumbnail" width="100">
                                </td>
                        
                                <td class="border-bottom-0">
                                    {{ $producto->cantidad }}
                                </td>
                        
                                <td class="border-bottom-0">
                                    {{ $producto->periodo->fecha_inicio->format('Y-m-d') }} - {{ $producto->periodo->fecha_fin->format('Y-m-d') }}
                                </td>
                        
                                <td class="border-bottom-0">
                                    @if($producto->fecha_vencimiento)
                                        {{ date('d/m/Y', strtotime($producto->fecha_vencimiento)) }}
                                    @endif
                                </td>
                        
                                <td class="border-bottom-0">
                                    {{ $producto->estante->estante }}
                                </td>
                        
                                <td class="border-bottom-0" 
                                    style="
                                        @if($producto->cantidad <= 10) 
                                            border-right: 2px solid #E07164;
                                        @endif
                                        @if($diasParaVencimiento !== null && $diasParaVencimiento <= 10)
                                            border-right: 2px solid #E0BA79; /* Cambia el color a tu preferencia */
                                        @endif
                                    "
                                >
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

@section('AfterScript')

    <script>
 $(document).ready(function() {
    // Obtén el valor del periodo seleccionado de la URL
    var selectedPeriodo = "{{ request('periodo') }}";
    // Obtén el valor del nombre de producto seleccionado de la URL
    var selectedNombre = "{{ request('nombre') }}";
    // Obtén el valor de la fecha de vencimiento seleccionada de la URL
    var selectedVencimiento = "{{ request('vencimiento') }}";

    // Establece el valor seleccionado en el filtro de periodo o deja "Seleccionar..." si no hay valor
    $("#filtro-periodo").val(selectedPeriodo || "Seleccionar...");

    // Establece el valor seleccionado en el filtro de nombre o deja "Seleccionar..." si no hay valor
    $("#filtro-nombre").val(selectedNombre || "Seleccionar...");

    // Establece el valor seleccionado en el filtro de fecha de vencimiento o deja "Seleccionar..." si no hay valor
    $("#filtro-vencimiento").val(selectedVencimiento || "Seleccionar...");

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

        // Agrega el parámetro de periodo solo si no es "Seleccionar..."
        if (periodo !== "Seleccionar...") {
            url += "?periodo=" + periodo;
        }

        // Agrega el parámetro de nombre solo si no es "Seleccionar..."
        if (nombre !== "Seleccionar...") {
            url += (periodo !== "Seleccionar..." ? "&" : "?") + "nombre=" + nombre;
        }

        // Agrega el parámetro de fecha de vencimiento solo si no es "Seleccionar..."
        if (vencimiento !== "Seleccionar...") {
            url += ((periodo !== "Seleccionar..." || nombre !== "Seleccionar...") ? "&" : "?") + "vencimiento=" + vencimiento;
        }

        window.location.href = url;
    }
});

    </script>

@endsection

