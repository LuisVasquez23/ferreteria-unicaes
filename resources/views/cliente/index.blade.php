@extends('layouts/dashboard')
@section('title', 'Administrar clientes')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Administración de clientes</h5>
        <div class="card-body">
            <a href="{{ route('cliente.create') }}" class="btn btn-success mb-3">
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
                                <b>Teléfono</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Dirección</b>
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
                        @foreach ($clientes as $cliente)
                            <tr>
                                <td class="border-bottom-0">
                                    {{ $cliente->dui }}
                                </td>
                                <td class="border-bottom-0">
                                    {{ $cliente->nombres }} , {{ $cliente->apellidos }}
                                </td>
                                <td class="border-bottom-0">
                                    +503 {{ $cliente->telefono }}
                                </td>
                                <td class="border-bottom-0">
                                    {{ $cliente->direccion }}
                                </td>


                                @if ($filtro === 'bloqueados')
                                    <td class="border-bottom-0">
                                        {{ $cliente->bloqueado_por }}
                                    </td>
                                @endif

                                <td class="d-flex gap-1 justify-content-center">

                                    @if ($filtro !== 'bloqueados')
                                        <a href="{{ route('cliente.edit', $cliente->usuario_id) }}" class="btn btn-primary">
                                            <i class="ti ti-pencil"></i>
                                        </a>
                                    @endif

                                    @if ($filtro !== 'bloqueados')
                                        <form action="{{ route('cliente.destroy', $cliente->usuario_id) }}" method="POST"
                                            id="block-form-{{ $cliente->usuario_id }}">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="action" value="update">
                                            <button type="button" class="btn btn-danger"
                                                onclick="confirmBlock({{ $cliente->usuario_id }})">
                                                <i class="fa-solid fa-lock"></i>
                                            </button>
                                        </form>
                                    @endif


                                    @if ($filtro === 'bloqueados')
                                        <form action="{{ route('cliente.unblock', $cliente->usuario_id) }}" method="POST"
                                            id="unblock-form-{{ $cliente->usuario_id }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="button" class="btn btn-warning"
                                                onclick="confirmUnblock({{ $cliente->usuario_id }})">
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
                var url = "{{ route('clientes') }}?filtro=" + filtro;
                window.location.href = url;
            });
        });
    </script>


@endsection
