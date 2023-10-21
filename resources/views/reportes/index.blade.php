@extends('layouts/dashboard')
@section('title', 'Administración de Reportes')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Administración de Reportes</h5>
        <div class="card-body">
            <form method="POST" action="{{ route('reportes.index') }}">
                @csrf
            
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="periodo_id_inicio" class="form-label">Período de Inicio: *</label>
                        <select name="periodo_id_inicio" id="periodo_id_inicio" class="form-select @error('periodo_id_inicio') is-invalid @enderror">
                            @if($periodos->isEmpty())
                                <option value="" disabled selected>No se encontraron períodos</option>
                            @else
                                @foreach($periodos as $periodo_id => $fecha_inicio)
                                    <option value="{{ $fecha_inicio }}">
                                        {{ \Carbon\Carbon::parse($fecha_inicio)->format('Y/m/d') }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('periodo_id_inicio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
            
                    <div class="col-md-4">
                        <label for="periodo_id_fin" class="form-label">Período de Fin: *</label>
                        <select name="periodo_id_fin" id="periodo_id_fin" class="form-select @error('periodo_id_fin') is-invalid @enderror">
                            @if($periodos->isEmpty())
                                <option value="" disabled selected>No se encontraron períodos</option>
                            @else
                                @foreach($periodos as $periodo_id => $fecha_inicio)
                                    <option value="{{ $fecha_inicio }}">
                                        {{ \Carbon\Carbon::parse($fecha_inicio)->format('Y/m/d') }}
                                    </option>
                                @endforeach
                            @endif
                        </select>
                        @error('periodo_id_fin')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            
                <button type="submit" class="btn btn-success mb-3">
                    <i class="fas fa-plus"></i>
                    Consultar
                </button>
            </form>
            


            
            <div class="table-responsive">
                <table id="miTabla" class="table text-nowrap mb-0 align-middle table-striped table-bordered">
                    <thead class="text-dark fs-4">
                        <tr>
                            <th class="border-bottom-0">
                                <b>N Factura</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Monto</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Proveedor</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Periodo compra</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Realizada por</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Acciones</b>
                            </th>
                            
          

                            

                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($resultados as $resultado)
                        <tr>
                            <td>{{ $resultado->numerosfactura }}</td>
                            <td>${{ $resultado->monto }}</td>
                            <td>{{ $resultado->nombres }}</td>
                            <td>{{ date('Y/m/d', strtotime($resultado->fecha_inicio)) }}</td>

                            <td>{{ $resultado->creado_por }}</td>
                            <td class="d-flex gap-1 justify-content-center">
                        

                                <button type="button" class="btn btn-info" data-bs-toggle="modal"
                                    >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-break" viewBox="0 0 16 16">
                                        <path d="M14 4.5V9h-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v7H2V2a2 2 0 0 1 2-2h5.5L14 4.5zM13 12h1v2a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-2h1v2a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-2zM.5 10a.5.5 0 0 0 0 1h15a.5.5 0 0 0 0-1H.5z"/>
                                    </svg>
                                </button>


                            </td>
                        </tr>
                        @endforeach

                       
                    </tbody>
                </table>
            </div>
        </div>

        
    </div>

@endsection


