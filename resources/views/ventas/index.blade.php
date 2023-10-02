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
                            <h6 class="mb-0">{{ $venta->numerosfactura }}</h6>
                        </td>
                        <td class="border-bottom-0">
                            <h6 class="mb-0">${{ $venta->monto }}</h6>
                        </td>
                        <td class="border-bottom-0">
                            <h6 class="mb-0">{{ $venta->cliente->nombres }} {{ $venta->cliente->apellidos }}</h6>
                        </td>
                        <td class="d-flex gap-1 justify-content-center">
                            <!-- Botón para ver el detalle de la venta con modal -->
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#detalleVentaModal{{ $venta->venta_id }}">
                                <i class="fas fa-eye"></i> Ver detalle
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
<div class="modal fade" id="detalleVentaModal{{ $venta->venta_id }}" tabindex="-1" role="dialog" aria-labelledby="detalleVentaModalLabel{{ $venta->venta_id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detalleVentaModalLabel{{ $venta->venta_id  }}">Detalle de Venta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <td class="border-botteom-0">
                            <h6 class="mb-0">${{ $detalle->precio * $detalle->cantidad }}</h6>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
                <div class="row">
                    <div class="justify-contentend end">
                        <h4>IVA: ${{ ($detalle->precio * $detalle->cantidad)*0.13 }}</h4>
                    </div>

                    <div class="justify-contentend end">
                        <h4>Total con IVA: ${{ $venta->monto }}</h4>
                    </div>
                </div>
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
    $(document).ready(function() {
        // Agrega aquí el código JavaScript para filtrar las ventas si es necesario
    });
</script>
@endsection
