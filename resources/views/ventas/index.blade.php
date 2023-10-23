@extends('layouts/dashboard')

@section('title', 'Administrar ventas')

@section('contenido')
    <div class="card mt-3">
        <h5 class="card-header">Administración de ventas</h5>
        <div class="card-body">
            <a href="{{ route('ventas.create') }}" class="btn btn-success mb-3">
                <i class="fas fa-plus"></i> Agregar
            </a>

            <div class="table-responsive">
                <table id="miTabla" class="table text-nowrap mb-0 align-middle table-striped table-bordered">
                    <thead class="text-dark fs-4">
                        <tr>
                            <th class="border-bottom-0">
                                <b>Factura</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Total</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Cliente</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Acciones</b>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ventas as $venta)
                            <tr>
                                <td class="border-bottom-0">
                                    {{ $venta->numerosfactura }}
                                </td>
                                <td class="border-bottom-0">
                                    ${{ $venta->monto }}
                                </td>
                                <td class="border-bottom-0">
                                    {{ $venta->cliente->nombres }} {{ $venta->cliente->apellidos }}
                                </td>
                                <td class="d-flex gap-1 justify-content-center">
                                    <!-- Botón para ver el detalle de la venta con modal -->
                                    <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                        data-bs-target="#detalleVentaModal{{ $venta->venta_id }} " title="Ver detalle" >
                                        <i class="fas fa-eye"></i> 
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para el detalle de venta -->
    @foreach ($ventas as $venta)
        <div class="modal fade" id="detalleVentaModal{{ $venta->venta_id }}" tabindex="-1" role="dialog"
            aria-labelledby="detalleVentaModalLabel{{ $venta->venta_id }}" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detalleVentaModalLabel{{ $venta->venta_id }}">Detalle de Venta</h5>
                    </div>
                    <div class="modal-body">
                        <table id="miTabla" class="table text-nowrap mb-0 align-middle table-striped table-bordered">
                            <thead class="text-dark fs-4">
                                <tr>
                                    <th class="border-bottom-0">
                                        <b>Producto</b>
                                    </th>
                                    <th class="border-bottom-0">
                                        <b>Cantidad</b>
                                    </th>
                                    <th class="border-bottom-0">
                                        <b>Precio Unitario</b>
                                    </th>
                                    <th class="border-bottom-0">
                                        <b>Total</b>
                                    </th>
                                    <th class="border-bottom-0">
                                        <b>IVA (13%)</b>
                                    </th>
                                    <th class="border-bottom-0">
                                        <b>Total con IVA</b>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($venta->detalle_ventas as $detalle)
                                    <tr>
                                        <td class="border-bottom-0">
                                            <h6 class="mb-0">{{ $detalle->producto->nombre }}</h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="mb-0">{{ $detalle->cantidad }}</h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="mb-0">${{ $detalle->precio }}</h6>
                                        </td>
                                        <td class="border-bottom-0 text-center">
                                            <h6 class="mb-0">${{ $detalle->precio * $detalle->cantidad }}</h6>
                                        </td>
                                        <td class="border-bottom-0 text-center">
                                            <h6 class="mb-0">${{ number_format($detalle->precio * $detalle->cantidad * 0.13, 2) }}</h6>
                                        </td>
                                        <td class="border-bottom-0 text-center">
                                            <h6 class="mb-0">${{ number_format($detalle->precio * $detalle->cantidad * 1.13, 2) }}</h6>
                                        </td>
                                        
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="row">
                           
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <div class="mb-1 mt-4 me-2">
                                  <h5>Total de la venta + IVA: $ {{ $venta->monto}} </h5>
        
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('reporteVenta.pdf', ['num_factura' => $venta->numerosfactura]) }}" class="btn btn-danger" title="Generar PDF" target="_blank">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

@endsection

@section('AfterScript')
    <script>
        $(document).ready(function() {
            // Agrega aquí el código JavaScript para filtrar las ventas si es necesario
        });
    </script>
@endsection
