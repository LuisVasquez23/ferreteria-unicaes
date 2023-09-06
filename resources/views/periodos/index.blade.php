@extends('layouts/dashboard')
@section('title', 'Administrador de periodos')
@section('contenido')

<div class="card mt-3">
    <h5 class="card-header">Administración de periodos</h5>
    <div class="card-body">
        <a href="{{ route('periodos.create') }}" class="btn btn-success mb-3">Agregar</a>
        <div class="table-responsive">
            <table class="table text-nowrap mb-0 align-middle table-striped table-bordered">
                <thead class="text-dark fs-4">
                    <tr>
                        <th class="border-bottom-0">
                            <b>Fecha inicio</b>
                        </th>
                        <th class="border-bottom-0">
                            <b>Fecha fin</b>
                        </th>
                        <th class="border-bottom-0">
                            <b>Creado Por</b>
                        </th>
                        <th class="border-bottom-0">
                            <b>Fecha Creación</b>
                        </th>
                        <th class="border-bottom-0">
                            <b>Actualizado Por</b>
                        </th>
                        <th class="border-bottom-0">
                            <b>Fecha Actualización</b>
                        </th>
                        <th class="border-bottom-0">
                            <b>Bloqueado Por</b>
                        </th>
                        <th class="border-bottom-0">
                            <b>Fecha Bloqueo</b>
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
                                <h6 class="fw-semibold mb-0">{{ date('d/m/Y', strtotime($periodo->fecha_inicio)) }}</h6>
                            @endif
                        </td>
                        <td class="border-bottom-0">
                            @if ($periodo->fecha_fin)
                                <h6 class="fw-semibold mb-0">{{ date('d/m/Y', strtotime($periodo->fecha_fin)) }}</h6>
                            @endif
                        </td>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">{{ $periodo->creado_por }}</h6>
                        </td>
                        <td class="border-bottom-0">
                            @if ($periodo->fecha_creacion)
                                <h6 class="fw-semibold mb-0">{{ $periodo->fecha_creacion }}</h6>
                            @endif
                        </td>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">{{ $periodo->actualizado_por }}</h6>
                        </td>
                        <td class="border-bottom-0">
                            @if ($periodo->fecha_actualizacion)
                                <h6 class="fw-semibold mb-0">{{$periodo->fecha_actualizacion }}</h6>
                            @endif
                        </td>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">{{ $periodo->bloqueado_por }}</h6>
                        </td>
                        <td class="border-bottom-0">
                            @if ($periodo->fecha_bloqueo)
                                <h6 class="fw-semibold mb-0">{{ date('d/m/Y', strtotime($periodo->fecha_bloqueo)) }}</h6>
                            @endif
                        </td>
                        <td class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('periodos.edit', $periodo->periodo_id) }}" class="btn btn-primary">
                                <i class="ti ti-pencil"></i>
                            </a>
                            <form action="{{ route('periodos.destroy', $periodo->periodo_id) }}" method="POST"
                                id="delete-form-{{ $periodo->periodo_id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger"
                                    onclick="confirmDelete({{ $periodo->periodo_id }})">
                                    <i class="ti ti-trash-x"></i>
                                </button>
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
    function confirmDelete(id) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: 'Esta acción no se puede deshacer.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, envía el formulario de eliminación correspondiente
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }
</script>

@endsection
