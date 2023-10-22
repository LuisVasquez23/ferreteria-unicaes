@extends('layouts/dashboard')
@section('title', 'Administrar clientes')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Administración de empleados</h5>
        <div class="card-body">
            <a href="{{ route('empleados.create') }}" class="btn btn-success mb-3">
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
                                <b>DUI</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Nombre</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Edad</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Teléfono</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Dirección</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Email</b>
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
                        @foreach ($empleados as $empleado)
                            <tr>
                                <td class="border-bottom-0">
                                    {{ $empleado->dui }}
                                </td>
                                <td class="border-bottom-0">
                                    {{ $empleado->nombres }} {{ $empleado->apellidos }}
                                </td>
                                <td class="border-bottom-0">
                                    {{ \Carbon\Carbon::parse($empleado->fecha_nacimiento)->age }} años
                                </td>
                                <td class="border-bottom-0">
                                    {{ $empleado->telefono }}
                                </td>
                                <td class="border-bottom-0">
                                    {{ $empleado->direccion }}
                                </td>
                                <td class="border-bottom-0">
                                    {{ $empleado->email }}
                                </td>
                                @if ($filtro === 'bloqueados')
                                    <td class="border-bottom-0">
                                        <h6>{{ $empleado->bloqueado_por }}</h6>
                                    </td>
                                @endif
                                <td class="d-flex gap-1 justify-content-center">

                                    @if ($filtro !== 'bloqueados')
                                        <a href="{{ route('empleados.edit', $empleado->usuario_id) }}"
                                            class="btn btn-primary">
                                            <i class="ti ti-pencil"></i>
                                        </a>
                                    @endif

                                    @if ($filtro !== 'bloqueados')
                                        <form action="{{ route('empleados.destroy', $empleado->usuario_id) }}"
                                            method="POST" id="block-form-{{ $empleado->usuario_id }}">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="action" value="update">
                                            <button type="button" class="btn btn-danger"
                                                onclick="confirmBlock({{ $empleado->usuario_id }})">
                                                <i class="fa-solid fa-lock"></i>
                                            </button>
                                        </form>
                                    @endif


                                    @if ($filtro === 'bloqueados')
                                        <form action="{{ route('empleados.unblock', $empleado->usuario_id) }}"
                                            method="POST" id="unblock-form-{{ $empleado->usuario_id }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="button" class="btn btn-warning"
                                                onclick="confirmUnblock({{ $empleado->usuario_id }})">
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
                var url = "{{ route('empleados.index') }}?filtro=" + filtro;
                window.location.href = url;
            });
        });
    </script>


@endsection
