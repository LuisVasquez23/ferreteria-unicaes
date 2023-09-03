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
                                <h6 class="fw-semibold mb-0">Nombre</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Direccion</h6>
                            </th>
                            <th class="border-bottom-0">
                                <h6 class="fw-semibold mb-0">Rol</h6>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($opcionesMenu as $opcionMenu)
                            <tr>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">{{ $opcionMenu->nombre }}</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">{{ $opcionMenu->direccion }}</h6>
                                </th>
                                <th class="border-bottom-0">
                                    <h6 class="fw-semibold mb-0">{{ $opcionMenu->role->role }}</h6>
                                </th>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
