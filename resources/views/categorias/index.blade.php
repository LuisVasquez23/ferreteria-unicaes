@extends('layouts/dashboard')
@section('title', 'Administración de categorías')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Administración de categorías</h5>
        <div class="card-body">
            <a href="{{ route('categorias.create') }}" class="btn btn-success mb-3">Agregar</a>

            <div class="table-responsive">
                <table id="miTabla" class="table text-nowrap mb-0 align-middle table-striped table-bordered">
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
                                    <form action="{{ route('categorias.destroy', $categoria->categoria_id) }}"
                                        method="POST" id="delete-form-{{ $categoria->categoria_id }}">
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
@endsection
