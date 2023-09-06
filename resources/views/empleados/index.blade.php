@extends('layouts/dashboard')
@section('title', 'Administración de categorías')
@section('contenido')

<div class="card mt-3">
    <h5 class="card-header">Administración de Empleados</h5>
    <div class="card-body">
        <a href="{{ route('empleados.create') }}" class="btn btn-success mb-3">Agregar</a>
        <div class="table-responsive">
            <table class="table text-nowrap mb-0 align-middle table-striped table-bordered" id="miTabla">
                <thead class="text-dark fs-4">
                    <tr>
                        <th class="border-bottom-0">
                            <b>DUI: </b>
                        </th>
                        <th class="border-bottom-0">
                            <b>Nombre: </b>
                        </th>
                        <th class="border-bottom-0">
                            <b>Telefono: </b>
                        </th>
                        <th class="border-bottom-0">
                            <b>Dirección: </b>
                        </th>
                        <th class="border-bottom-0">
                            <b>Correo electronico: </b>
                        </th>
                        <th>
                            <b>Acciones</b>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($empleados as $empleado)
                    <tr>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">{{ $empleado->dui }}</h6>
                        </td>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">{{ $empleado->nombres }} {{ $empleado->apellidos }}</h6>
                        </td>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">{{ $empleado->telefono }}</h6>
                        </td>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">{{ $empleado->direccion }}</h6>
                        </td>
                        <td class="border-bottom-0">
                            <h6 class="fw-semibold mb-0">{{ $empleado->email }}</h6>
                        </td>
                        <td class="d-flex gap-1 justify-content-center">
                            <a href="{{ route('empleados.edit', $empleado->usuario_id) }}" class="btn btn-primary">
                                <i class="ti ti-pencil"></i>
                            </a>
                            <form action="{{ route('empleados.destroy', $empleado->usuario_id) }}" method="POST"
                                id="delete-form-{{ $empleado->usuario_id }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger"
                                    onclick="confirmDelete({{ $empleado->usuario_id }})">
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
