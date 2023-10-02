@extends('layouts/dashboard')
@section('title', 'Administracion de roles')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Administracion de roles</h5>
        <div class="card-body">
            <a href="{{ route('rol.create') }}" class="btn btn-success mb-3">
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
                <table class="table text-nowrap mb-0 align-middle table-striped table-bordered" id="miTabla">
                    <thead class="text-dark fs-4">
                        <tr>
                            <th class="border-bottom-0">
                                <b>Rol</b>
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
                        @foreach ($roles as $role)
                            <tr>
                                <td>
                                    {{ $role->role }}
                                </td>
                                <td>
                                    {{ $role->descripcion }}
                                </td>

                                @if ($filtro === 'bloqueados')
                                    <td class="border-bottom-0">
                                        {{ $role->bloqueado_por }}
                                    </td>
                                @endif
                                <td class="d-flex gap-1 justify-content-center">


                                    @if ($role->role == 'MegaAdmin')
                                        <p class="mt-2">Nada por hacer</p>
                                    @endif



                                    @if ($filtro !== 'bloqueados')
                                        @if ($role->role !== 'MegaAdmin')
                                            <a href="{{ route('rol.edit', $role->role_id) }}" class="btn btn-primary">
                                                <i class="ti ti-pencil"></i>
                                            </a>
                                        @endif
                                    @endif

                                    @if ($filtro !== 'bloqueados')
                                        @if ($role->role !== 'MegaAdmin')
                                            <form action="{{ route('rol.destroy', $role->role_id) }}" method="POST"
                                                id="block-form-{{ $role->role_id }}">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="action" value="update">
                                                <button type="button" class="btn btn-danger"
                                                    onclick="confirmBlock({{ $role->role_id }})">
                                                    <i class="fa-solid fa-lock"></i>
                                                </button>
                                            </form>
                                        @endif
                                    @endif


                                    @if ($filtro === 'bloqueados')
                                        <form action="{{ route('rol.unblock', $role->role_id) }}" method="POST"
                                            id="unblock-form-{{ $role->role_id }}">
                                            @csrf
                                            @method('PUT')
                                            <button type="button" class="btn btn-warning"
                                                onclick="confirmUnblock({{ $role->role_id }})">
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
                var url = "{{ route('roles') }}?filtro=" + filtro;
                window.location.href = url;
            });
        });
    </script>


@endsection
