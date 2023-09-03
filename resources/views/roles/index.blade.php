@extends('layouts/dashboard')
@section('title', 'Administracion de menu')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Administracion del menú</h5>
        <div class="card-body">
            <a href="{{ route('menu.create') }}" class="btn btn-success mb-3">Agregar</a>
            <div class="table-responsive">
                <table class="table text-nowrap mb-0 align-middle table-striped table-bordered">
                    <thead class="text-dark fs-4">
                        <tr>
                            <th class="border-bottom-0">
                                <b>Role</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Descripcion</b>
                            </th>
                            <th>
                                <b>Acciones</b>
                            </th>
                        </tr>
                    </thead>
                    <tbody>

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
