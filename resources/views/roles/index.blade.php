@extends('layouts/dashboard')
@section('title', 'Administracion de roles')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Administracion del roles</h5>
        <div class="card-body">
            <a href="{{ route('roles.create') }}" class="btn btn-success mb-3">
                <i class="fas fa-plus"></i> Agregar role
            </a>
            <div class="table-responsive">
                <table class="table text-nowrap mb-0 align-middle table-striped table-bordered" id="miTabla">
                    <thead class="text-dark fs-4">
                        <tr>
                            <th class="border-bottom-0">
                                <b>Role</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Descripción</b>
                            </th>
                            <th>
                                <b>Acciones</b>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role)
                            <tr>
                                <td>
                                    <h6 class="fw-semibold mb-0">{{ $role->role }}</h6>
                                </td>
                                <td>
                                    <h6 class="fw-semibold mb-0">{{ $role->descripcion }}</h6>
                                </td>
                                <td class="d-flex gap-1 justify-content-center">
                                    @if ($filtro !== 'bloqueados')
                                        <a href="{{ route('roles.edit', $role->role_id) }}" class="btn btn-primary">
                                            <i class="ti ti-pencil"></i>
                                        </a>
                                    @endif

                                    @if ($filtro !== 'bloqueados')
                                        <form action="{{ route('roles.destroy', $role->role_id) }}" method="POST"
                                            id="block-form-{{ $role->role_id }}">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="action" value="update">
                                            <button type="button" class="btn btn-danger"
                                                onclick="confirmBlock({{ $role->role_id }})">
                                                <i class="fa-solid fa-lock"></i>
                                            </button>
                                        </form>
                                    @endif


                                    @if ($filtro === 'bloqueados')
                                        <form action="{{ route('cliente.unblock', $role->role_id) }}" method="POST"
                                            id="unblock-form-{{ $role->role_id }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="button" class="btn btn-warning"
                                                onclick="confirmUnblock({{ $role->role_id }})">
                                                <i class="fa-solid fa-unlock"></i>
                                            </button>
                                        </form>
                                    @endif
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
        function confirmDelete() {
            Swal.fire({
                title: '¿Estás seguro en eliminar este registro?',
                text: 'Esta acción no se puede deshacer.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, envía el formulario de eliminación
                    document.getElementById('delete-form').submit();
                }
            });
        }
    </script>

@endsection
