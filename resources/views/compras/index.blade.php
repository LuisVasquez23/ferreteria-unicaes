@extends('layouts/dashboard')

@section('title', 'Administrar compras')

@section('contenido')
    <div class="card mt-3">
        <h5 class="card-header">Administración de compras</h5>
        <div class="card-body">
            <a href="{{ route('compras.create') }}" class="btn btn-success mb-3">
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
                                <b>Monto</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Proveedor</b>
                            </th>
                            <th class="borde-bottom-o">
                                <b>Fecha de compra</b>
                            </th>
                            <th class="borde-bottom-o">
                                <b>Realizada por</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Acciones</b>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($compras as $compra)
                            @if ($compra->numerosfactura > 0)
                                <tr>
                                    <td class="border-bottom-0">
                                        {{ $compra->numerosfactura }}
                                    </td>
                                    <td class="border-bottom-0">
                                        ${{ $compra->monto }}
                                    </td>
                                    <td class="border-bottom-0">
                                        {{ $compra->comprador->nombres }}
                                        {{ $compra->comprador->apellidos }}
                                    </td>
                                    <td class="border-bottom-0">
                                        {{ $compra->periodo->fecha_inicio->format('Y/m/d') }}
                                    </td>

                                    <td class="border-bottom-0">
                                        {{ $compra->creado_por}}
                                    </td>

                                    <td class="d-flex gap-1 justify-content-center">
                                        <!-- Botón para ver el detalle de la compra con modal -->
                                        <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                            data-bs-target="#detalleCompraModal{{ $compra->compra_id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
    /* Estilo para limitar la altura máxima del modal y permitir desplazamiento vertical */
    .modal-content {
        max-height: 80vh; /* Ajusta la altura máxima según lo necesites, aquí se usa el 80% de la altura visible */
        overflow-y: auto; /* Habilita el desplazamiento vertical si es necesario */
    }
</style>

    <!-- Modal para el detalle de compra -->
    @foreach ($compras as $compra)
        <div class="modal fade" id="detalleCompraModal{{ $compra->compra_id }}" tabindex="-1" role="dialog"
            aria-labelledby="detalleCompraModalLabel{{ $compra->compra_id }}" aria-hidden="true" data-bs-backdrop="static" >
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detalleCompraModalLabel{{ $compra->compra_id }}">Detalle de Compra
                        </h5>
                        
                    </div>
                    <div class="modal-body">
                    <div class="table-responsive">
                        <table id="miTabla" class="table text-nowrap mb-0 align-middle table-striped table-bordered">
                            <thead class="text-dark fs-4">
                                <tr>
                                    <th class="border-bottom-0 text-center">
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
                                @foreach ($compra->detalle_compras as $detalle)
                                    <tr>
                                        <td class="border-bottom-0 text-center">
                                            <h6 class="mb-0">{{ $detalle->producto->nombre }}</h6>
                                        </td>
                                        <td class="border-bottom-0 text-center">
                                            <h6 class="mb-0">{{ $detalle->cantidad }}</h6>
                                        </td>
                                        <td class="border-bottom-0 text-center">
                                            <h6 class="mb-0">${{ $detalle->precioUnitario }}</h6>
                                        </td>
                                        <td class="border-bottom-0 text-center">
                                            <h6 class="mb-0">${{ $detalle->precioUnitario * $detalle->cantidad }}</h6>
                                        </td>
                                        <td class="border-bottom-0 text-center">
                                            <h6 class="mb-0">${{ $detalle->precioUnitario * $detalle->cantidad * 0.13 }}</h6>
                                        </td>
                                        <td class="border-bottom-0 text-center">
                                            <h6 class="mb-0">${{ $detalle->precioUnitario * $detalle->cantidad * 1.13 }}</h6>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="row">
                            <!-- Columna para el botón "Agregar Producto" -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <div class="mb-1 mt-4 me-2">
                                  <h5>Total de la compra + IVA: $ {{ $compra->monto * 1.13 }} </h5>
        
                                </div>
                            </div>
                        </div>
    
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <a href="{{ route('reportes.pdf', ['num_factura' => $compra->numerosfactura]) }}" class="btn btn-danger" title="Generar PDF" target="_blank">
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
            // Agrega aquí el código JavaScript para filtrar las compras si es necesario
        });
    </script>
@endsection
