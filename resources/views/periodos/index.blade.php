@extends('layouts/dashboard')
@section('title', 'Administrador de periodos')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Administración de periodos</h5>
        <div class="card-body">
            <a href="{{ route('periodos.create') }}" class="btn btn-success mb-3">
                <i class="fas fa-plus"></i>
                Agregar
            </a>
            <div class="col-md-4 mx-auto text-center">
                <label class="mb-2" for="filtro-bloqueo">Filtrar por Estado:</label>
                <select id="filtro-bloqueo" class="form-select">
                    <option>Seleccionar...</option>
                    <option value="bloqueados">Bloqueados</option>
                    <option value="no-bloqueados">No Bloqueados</option>
                </select>
            </div>
            <div class="table-responsive">
                <table class="table text-nowrap mb-0 align-middle table-striped table-bordered" id="miTabla">
                    <thead class="text-dark fs-4">
                        <tr>
                            <th class="border-bottom-0">
                                <b>Fecha inicio</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Fecha fin</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Año</b> <!-- Nueva columna para el año -->
                            </th>
                            <th class="border-bottom-0">
                                <b>Creado Por</b>
                            </th>
                            <th>
                                <b>Acciones</b>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($periodos as $periodo)
                            <tr>
                                <td class="border-bottom-0">
                                    @if ($periodo->fecha_inicio)
                                        {{ date('d/m/Y', strtotime($periodo->fecha_inicio)) }}
                                    @endif
                                </td>
                                <td class="border-bottom-0">
                                    @if ($periodo->fecha_fin)
                                        {{ date('d/m/Y', strtotime($periodo->fecha_fin)) }}
                                    @endif
                                </td>
                                <td class="border-bottom-0">
                                    @if ($periodo->fecha_inicio)
                                        {{ date('Y', strtotime($periodo->fecha_inicio)) }}
                                    @endif
                                </td>
                                <td class="border-bottom-0">
                                    {{ $periodo->creado_por }}
                                </td>
                                <td class="d-flex gap-1 justify-content-center">
                                    @if ($filtro !== 'bloqueados')
                                        {{-- <a href="{{ route('periodos.edit', $periodo->periodo_id) }}"
                                            class="btn btn-primary">
                                            <i class="ti ti-pencil"></i>
                                        </a> --}}
                                        <form action="{{ route('periodos.bloquear', $periodo->periodo_id) }}" method="POST"
                                            id="block-form-{{ $periodo->periodo_id }}">
                                            @csrf
                                            @method('PUT')
                                            <!-- Agrega esta línea para indicar que es una solicitud PUT -->

                                            <button type="button" class="btn btn-danger"
                                                onclick="confirmBlock({{ $periodo->periodo_id }})">
                                                <i class="fa-solid fa-lock"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if ($filtro === 'bloqueados')
                                        <form action="{{ route('periodos.unblock', $periodo->periodo_id) }}" method="POST"
                                            id="unblock-form-{{ $periodo->periodo_id }}">
                                            @csrf
                                            @method('PUT')
                                            <!-- Agrega esta línea para indicar que es una solicitud PUT -->

                                            <button type="button" class="btn btn-danger"
                                                onclick="confirmUnblock({{ $periodo->periodo_id }})">
                                                <i class="fa-solid fa-unlock"></i>
                                            </button>
                                        </form>
                                    @endif
                                    </form>
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
            $("#filtro-bloqueo").on("change", function() {
                var filtro = $(this).val();
                var url = "{{ route('periodos') }}?filtro=" + filtro;
                window.location.href = url;
            });
        });
    </script>
@endsection
