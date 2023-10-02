@extends('layouts/dashboard')
@section('title', 'Administrar unidades')
@section('contenido')


    <div class="card mt-3">
        <h5 class="card-header">Administración de unidades</h5>
        <div class="card-body">
            <a href="{{ route('unidad.create') }}" class="btn btn-success mb-3">
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
                                <b>Nombre</b>
                            </th>
                            <th class="border-bottom-0">
                                <b>Descripción</b>
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
                        @foreach ($medidas as $medida)
                            <tr>
                                <td class="border-bottom-0">
                                    {{ $medida->nombre }}
                                </td>
                                <td class="border-bottom-0">
                                    {{ $medida->descripcion }}
                                </td>

                                @if ($filtro === 'bloqueados')
                                    <td class="border-bottom-0">
                                        {{ $medida->bloqueado_por }}
                                    </td>
                                @endif

                                <td class="d-flex gap-1 justify-content-center">

                                    @if ($filtro !== 'bloqueados')
                                        <a href="{{ route('unidad.edit', $medida->unidad_medida_id) }}"
                                            class="btn btn-primary">
                                            <i class="ti ti-pencil"></i>
                                        </a>
                                    @endif

                                    @if ($filtro !== 'bloqueados')
                                        <form action="{{ route('unidad.destroy', $medida->unidad_medida_id) }}"
                                            method="POST" id="block-form-{{ $medida->unidad_medida_id }}">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="action" value="update">
                                            <button type="button" class="btn btn-danger"
                                                onclick="confirmBlock({{ $medida->unidad_medida_id }})">
                                                <i class="fa-solid fa-lock"></i>
                                            </button>
                                        </form>
                                    @endif


                                    @if ($filtro === 'bloqueados')
                                        <form action="{{ route('unidad.unblock', $medida->unidad_medida_id) }}"
                                            method="POST" id="unblock-form-{{ $medida->unidad_medida_id }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="button" class="btn btn-warning"
                                                onclick="confirmUnblock({{ $medida->unidad_medida_id }})">
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
                var url = "{{ route('unidades') }}?filtro=" + filtro;
                window.location.href = url;
            });
        });
    </script>


@endsection
