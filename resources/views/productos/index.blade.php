@extends('layouts/dashboard')
@section('title', 'Administrar productos')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Administración de productos</h5>
        <div class="card-body">
            <a href="{{ route('producto.create') }}" class="btn btn-success mb-3">
                <i class="fas fa-plus"></i>
                Agregar
            </a>

            <div class="col-md-4 mx-auto text-center mb-3">
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
                                <b>Nombre</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Descripcion</b>
                            </th>

                            <th class="border-bottom-0">
                                <b>Cantidad</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Proveedor</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Categoria</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Estante</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Unidad de medida</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Periodo</b>
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
                        @foreach ($productos as $producto)
                            <tr>
                                <td class="border-bottom-0">
                                    {{ $producto->nombre }}
                                </td>
                                <td class="border-bottom-0">
                                    {{ $producto->descripcion }}
                                </td>

                                <td class="border-bottom-0">
                                    {{ $producto->cantidad }}
                                </td>
                                <td class="border-bottom-0">
                                    {{ $producto->usuario->nombres }}
                                </td>
                                <td class="border-bottom-0">
                                    {{ $producto->categoria->categoria }}
                                </td>

                                <td class="border-bottom-0">
                                    {{ $producto->estante->estante }}
                                </td>
                                <td class="border-bottom-0">
                                    {{ $producto->medida->nombre }}
                                </td>

                                <td class="border-bottom-0">
                                    {{ $producto->periodo->fecha_inicio->format('Y/m/d') }}
                                </td>


                                @if ($filtro === 'bloqueados')
                                    <td class="border-bottom-0">
                                        {{ $producto->bloqueado_por }}
                                    </td>
                                @endif

                                <td class="d-flex gap-1 justify-content-center">

                                    @if ($filtro !== 'bloqueados')
                                        <a href="{{ route('producto.edit', $producto->producto_id) }}"
                                            class="btn btn-primary">
                                            <i class="ti ti-pencil"></i>
                                        </a>
                                    @endif

                                    @if ($filtro !== 'bloqueados')
                                        <form action="{{ route('producto.destroy', $producto->producto_id) }}"
                                            method="POST" id="block-form-{{ $producto->producto_id }}">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="action" value="update">
                                            <button type="button" class="btn btn-danger"
                                                onclick="confirmBlock({{ $producto->producto_id }})">
                                                <i class="fa-solid fa-lock"></i>
                                            </button>
                                        </form>
                                    @endif


                                    @if ($filtro === 'bloqueados')
                                        <form action="{{ route('producto.unblock', $producto->producto_id) }}"
                                            method="POST" id="unblock-form-{{ $producto->producto_id }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="button" class="btn btn-warning"
                                                onclick="confirmUnblock({{ $producto->producto_id }})">
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
                var url = "{{ route('productos') }}?filtro=" + filtro;
                window.location.href = url;
            });
        });
    </script>


@endsection
