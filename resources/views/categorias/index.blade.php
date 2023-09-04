@extends('layouts/dashboard')
@section('title', 'Administración de categorías')
@section('contenido')

<div class="card mt-3">
    <h5 class="card-header">Administración de categorías</h5>
    <div class="card-body">
        <a href="{{ route('categorias.create') }}" class="btn btn-success mb-3">Agregar</a>

        <div class="table-responsive">
            <table id="categorias-table" class="table text-nowrap mb-0 align-middle table-striped table-bordered">
                <thead class="text-dark fs-4">
                    <tr>
                        <th class="border-bottom-0">
                            <b>Categoría</b>
                        </th>
                        <th class="border-bottom-0">
                            <b>Descripción</b>
                        </th>
                        <th class="border-bottom-0">
                            <b>Creado Por</b>
                        </th>
                        <th class="border-bottom-0">
                            <b>Actualizado Por</b>
                        </th>
                        <th>
                            <b>Acciones</b>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categorias as $categoria)
                    <tr>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">{{ $categoria->categoria }}</h6>
                        </td>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">{{ $categoria->descripcion }}</h6>
                        </td>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">{{ $categoria->creado_por }}</h6>
                        </td>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">{{ $categoria->actualizado_por }}</h6>
                        </td>
                        <td class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('categorias.edit', $categoria->categoria_id) }}"
                                class="btn btn-primary">
                                <i class="ti ti-pencil"></i>
                            </a>
                            <form action="{{ route('categorias.destroy', $categoria->categoria_id) }}" method="POST"
                                id="delete-form-{{ $categoria->categoria_id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger"
                                    onclick="confirmDelete({{ $categoria->categoria_id }})">
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function () {
    // Inicializa DataTables en tu tabla
    const table = $('#categorias-table').DataTable({
        language: {
            lengthMenu: "Mostrar _MENU_ entradas por página", // Personaliza el texto del desplegable
        },
    });

    // Aplica estilos personalizados al lengthMenu
    $('.dataTables_length').css({
        'margin-right': '20px', // Espacio entre el lengthMenu y el cuadro de búsqueda
    });

    // Personaliza el cuadro de búsqueda
    $('.dataTables_filter input[type="search"]').addClass('custom-search-input');
});

// Llama a performSearch cuando el usuario escriba en el campo de búsqueda
$('#search').on('input', function () {
    performSearch();
});

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
<style>
/* Estilos personalizados para el lengthMenu */
.dataTables_length {
    margin-top: 20px;
    margin-right: 5px; /* Espacio entre el lengthMenu y el cuadro de búsqueda */
}

/* Estilos para el cuadro de búsqueda */
.custom-search-input {
    width: 100%; /* Ancho del cuadro de búsqueda al 100% */
    padding: 10px; /* Espacio interno */
    border: 2px solid #ccc; /* Borde personalizado */
    border-radius: 5px; /* Bordes redondeados */
    background-color: #f5f5f5; /* Color de fondo */
    font-size: 16px; /* Tamaño de fuente */
}

.custom-search-input:focus {
    outline: none; /* Quita el contorno en enfoque */
    border-color: #007bff; /* Cambia el color del borde en enfoque */
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5); /* Agrega sombra en enfoque */
}
</style>
@endsection
