@extends('layouts/dashboard')
@section('title', 'Administración de Proveedores')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Administración de Proveedores</h5>
        <div class="card-body">
            <a href="{{ route('proveedores.create') }}" class="btn btn-success mb-3">
                <i class="fas fa-plus"></i>
                Agregar
            </a>
            <div class="col-md-4 mx-auto text-center">
                <label class="mb-2" for="filtro-bloqueo">Filtrar por Estado:</label>
                <select id="filtro-bloqueo" class="form-select">
                    <option>Seleccionar...</option>
                    <option value="no-bloqueados">No Bloqueados</option>
                    <option value="bloqueados">Bloqueados</option>
                </select>
            </div>
            <div class="table-responsive">
                <table id="miTabla" class="table text-nowrap mb-0 align-middle table-striped table-bordered">
                    <thead class="text-dark fs-4">
                        <tr>
                            <th class="border-bottom-0">
                                <b>NIT/DUI</b>
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
                            <th class="border-bottom-0">
                                <b>Locación</b>
                            </th>
                            @if ($filtro === 'bloqueados')
                                <th class="border-bottom-0">
                                    <b>Bloqueado por</b>
                                </th>
                            @endif

                            <th>
                                <b>Acciones</b>
                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($proveedores as $proveedor)
                            <tr>

                                <td class="border-bottom-0">
                                    {{ $proveedor->nit }}
                                </td>
                                <td class="border-bottom-0">
                                    {{ $proveedor->nombres }}
                                </td>
                                <td class="border-bottom-0">
                                    {{ $proveedor->email }}
                                </td>
                                <td class="border-bottom-0">
                                    +503 {{ $proveedor->telefono }}
                                </td>
                                <td class="border-bottom-0">
                                    {{ $proveedor->direccion }}
                                </td>
                                @if ($filtro === 'bloqueados')
                                    <td class="border-bottom-0">
                                        {{ $proveedor->bloqueado_por }}
                                    </td>
                                @endif
                                <td class="d-flex gap-1 justify-content-center">

                                    @if ($filtro !== 'bloqueados')
                                        <a href="{{ route('proveedores.edit', $proveedor->usuario_id) }}"
                                            class="btn btn-primary">
                                            <i class="ti ti-pencil"></i>
                                        </a>
                                        <!-- Formulario para bloquear -->
                                        <form action="{{ route('proveedores.bloquear', $proveedor->usuario_id) }}"
                                            method="POST" id="block-form-{{ $proveedor->usuario_id }}">
                                            @csrf
                                            @method('PUT')
                                            <!-- Agrega esta línea para indicar que es una solicitud PUT -->
                                            <button type="button" class="btn btn-danger"
                                                onclick="confirmBlock({{ $proveedor->usuario_id }})">
                                                <i class="fa-solid fa-lock"></i>
                                            </button>
                                        </form>
                                    @endif
                                    @if ($filtro === 'bloqueados')
                                        <!-- Formulario para desbloquear -->
                                        <form action="{{ route('proveedores.unblock', $proveedor->usuario_id) }}"
                                            method="POST" id="unblock-form-{{ $proveedor->usuario_id }}">
                                            @csrf
                                            @method('PUT')
                                            <!-- Agrega esta línea para indicar que es una solicitud PUT -->
                                            <button type="button" class="btn btn-warning"
                                                onclick="confirmUnblock({{ $proveedor->usuario_id }})">
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
        $(document).ready(function() {
            $("#filtro-bloqueo").on("change", function() {
                var filtro = $(this).val();
                var url = "{{ route('proveedores.index') }}?filtro=" + filtro;
                window.location.href = url;
            });
        });
    </script>
@endsection
