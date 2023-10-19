@extends('layouts/dashboard')
@section('title', 'Configuraciones')
@section('contenido')

    <div class="card mt-3">
        <h5 class="card-header">Configuraciones</h5>
        <div class="card-body">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            Configuración del logo
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Cambia el logo de tu empresa</h5>
                            <p class="card-text">
                                Personaliza la imagen de tu empresa cargando y cambiando el logo aquí.
                            </p>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#cambiarLogoModal">
                                Cambiar logo
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md">
                    <div class="card">
                        <div class="card-header">
                            Configuración del nombre de la empresa
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Cambia el nombre de tu empresa</h5>
                            <p class="card-text">
                                Personaliza el nombre de tu empresa fácilmente.
                            </p>
                            <button type="button" class="btn btn-primary mt-3 mb-1" data-bs-toggle="modal"
                                data-bs-target="#cambiarNombreModal">
                                Cambiar nombre
                            </button>
                        </div>
                    </div>

                </div>
            </div>
            <a href="{{ route('dashboard') }}" class="btn btn-dark">Regresar</a>
        </div>
    </div>

    <!-- INICIO MODAL PARA CAMBIAR NOMBRE -->

    <div class="modal fade" id="cambiarNombreModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cambiar nombre de la empresa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('settings.nombreEmpresa') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="nuevoNombre">Nombre:</label>
                            <input type="text" class="form-control" id="nuevoNombre" name="nuevoNombre"
                                value="{{ $nombreEmpresa }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- FIN MODAL PARA CAMBIAR NOMBRE -->

    <!-- INICIO MODAL PARA CAMBIAR EL LOGO -->
    <div class="modal fade" id="cambiarLogoModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cambiar logo de la empresa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulario para cargar el nuevo logo -->
                    <form method="POST" action="{{ route('settings.logo') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="nuevoLogo" class="form-label">Selecciona un nuevo logo:</label>
                            <input type="file" class="form-control" id="nuevoLogo" name="nuevoLogo" accept="image/*">
                        </div>
                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- FIN MODAL PARA CAMBIAR EL LOGO -->
@endsection


@section('AfterScript')



@endsection
