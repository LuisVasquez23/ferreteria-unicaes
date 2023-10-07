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
                            <th class="border-bottom-0">
                                <b>Acciones</b>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($compras as $compra)
                            @if ($compra->numerosfactura != 1)
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
                                        {{ $compra->periodo->fecha_inicio->format('Y/m/d')  }}
                                        
                                    </td>
                                    <td class="d-flex gap-1 justify-content-center">
                                        <!-- Botón para ver el detalle de la compra con modal -->
                                        <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                            data-bs-target="#detalleCompraModal{{ $compra->compra_id }}">
                                            <i class="fas fa-eye"></i> Ver detalle
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

    <!-- Modal para el detalle de compra -->
    @foreach ($compras as $compra)
        <div class="modal fade" id="detalleCompraModal{{ $compra->compra_id }}" tabindex="-1" role="dialog"
            aria-labelledby="detalleCompraModalLabel{{ $compra->compra_id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="detalleCompraModalLabel{{ $compra->compra_id }}">Detalle de Compra</h5>
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
                                @foreach ($compra->detalle_compras as $detalle)
                                    <tr>
                                        <td class="border-bottom-0">
                                            <h6 class="mb-0">{{ $detalle->producto->nombre }}</h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="mb-0">{{ $detalle->cantidad }}</h6>
                                        </td>
                                        <td class="border-bottom-0">
                                            <h6 class="mb-0">${{ $detalle->precioUnitario }}</h6>
                                        </td>
                                        <td class="border-botteom-0">

                                            <h6 class="mb-0">${{ $detalle->precioUnitario * $detalle->cantidad }}</h6>
                                        </td>

                                    </tr>
                                @endforeach

                            </tbody>


                        </table>
                        <div class="row">
                            <div class="justify-contentend end">
                                <h4>IVA: ${{ $detalle->precioUnitario * $detalle->cantidad * 0.13 }}</h4>
                            </div>
                            <div class="justify-contentend end">
                                <h4>Total con IVA: ${{ $compra->monto }}</h4>
                            </div>

                        </div>

                        <!-- Aquí puedes mostrar la información detallada de la compra, por ejemplo: -->

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
            // Agrega aquí el código JavaScript para filtrar las compras si es necesario
        });
    </script>
@endsection
