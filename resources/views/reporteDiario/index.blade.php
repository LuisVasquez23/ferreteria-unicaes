@extends('layouts/dashboard')
@section('title', 'Administración de Reportes')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Administración de Reportes Diarios de Ventas</h5>
        <div class="card-body">

            <div class="table-responsive mt-4">
                <table id="miTabla" class="table text-nowrap mb-0 align-middle table-striped table-bordered">
                    <thead class="text-dark fs-4">
                        <tr>
                            <th class="border-bottom-0">
                                <b>Fecha</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Acciones</b>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($fechasDetalleVenta as $fecha)
                            <tr>
                                <td class="border-bottom-0">
                                    
                                   <p>{{ \Carbon\Carbon::parse($fecha->created_date)->format('d \d\\e F \d\\e Y') }}</p>

                                </td>
                                <td class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('reporteDiario.pdf', ['id' => $fecha->created_date]) }}" class="btn btn-danger" title="Generar PDF" target="_blank">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            
            
            
            
                       
            
        </div>

        
    </div>

@endsection


