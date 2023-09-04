@extends('layouts/dashboard')
@section('title', 'Administración de Proveedores')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Administración de Proveedores</h5>
        <div class="card-body">
            <a href="{{ route('proveedores.create') }}" class="btn btn-success mb-3">Agregar Proveedor</a>
            <div class="table-responsive">
                <table class="table text-nowrap mb-0 align-middle table-striped table-bordered">
                    <thead class="text-dark fs-4">
                        <tr>
                            <th class="border-bottom-0">
                                <b>ID</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Nombre</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Email</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Teléfono</b>
                            </th>
                            <th>
                                <b>Acciones</b>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($proveedores as $proveedor)
                            <tr>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">{{ $proveedor->usuario_id }}</h6>
                                </td>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">{{ $proveedor->nombres }} {{ $proveedor->apellidos }}</h6>
                                </td>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">{{ $proveedor->email }}</h6>
                                </td>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">{{ $proveedor->telefono }}</h6>
                                </td>
                                <td class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('proveedores.edit', $proveedor->usuario_id) }}" class="btn btn-primary">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                                    <form action="{{ route('proveedores.destroy', $proveedor->usuario_id) }}" method="POST"
                                        id="delete-form-{{ $proveedor->usuario_id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger"
                                            onclick="confirmDelete({{ $proveedor->usuario_id }})">
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
