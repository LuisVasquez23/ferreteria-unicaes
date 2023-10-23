@extends('layouts/dashboard')

@section('title', 'Administración de Reportes de Ventas')

@section('contenido')
    <div class="card mt-3">
        <h5 class="card-header">Administración de Reportes de Ventas</h5>
        <div class="card-body">
            <form method="POST" action="{{ route('reporteVenta.index') }}">
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
                        <label for "periodo_id_fin" class="form-label">Período de Fin: *</label>
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
                    <i class="fas fa-search"></i> Consultar
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
                                <b>Cliente</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Acciones</b>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resultados as $resultado)
                            <tr>
                                <td class="border-bottom-0">
                                    {{ $resultado->numerosfactura }}
                                </td>
                                <td class="border-bottom-0">
                                    ${{ $resultado->monto }}
                                </td>
                                <td class="border-bottom-0">
                                    {{ $resultado->nombres }}
                                </td>
                                <td class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('reporteVenta.pdf', ['num_factura' => $resultado->numerosfactura]) }}" class="btn btn-danger" title="Generar PDF" target="_blank">
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
