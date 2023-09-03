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
                                <b>Nombre</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Direccion</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Rol</b>
                            </th>
                            <th>
                                <b>Acciones</b>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($opcionesMenu as $opcionMenu)
                            <tr>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">{{ $opcionMenu->nombre }}</h6>
                                </td>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">{{ $opcionMenu->direccion }}</h6>
                                </td>
                                <td class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">{{ $opcionMenu->role->role }}</h6>
                                </td>
                                <td class="d-flex gap-1 justify-content-center">
                                    <a href="{{ route('menu.edit', $opcionMenu->id) }}" class="btn btn-primary">
                                        <i class="ti ti-pencil"></i>
                                    </a>
                                    <form action="{{ route('menu.destroy', $opcionMenu->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
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
