@extends('layouts/dashboard')
@section('title', 'Dashboard')
@section('contenido')
    <div class="fs-6">
        <h1>Bienvenido, {{ Auth::user()->nombres }} {{ Auth::user()->apellidos }}</h1>
        @if (!$usuario->detalle_roles->isEmpty())
            @foreach ($usuario->detalle_roles as $detalleRole)
                @php
                    $colors = ['primary', 'secondary', 'success', 'dark'];
                    $randomColor = $colors[array_rand($colors)];
                @endphp
                <span class="badge text-bg-{{ $randomColor }}">{{ $detalleRole->role->role }}</span>
            @endforeach
        @else
            <p>No se encontraron roles para este usuario.</p>
        @endif

        @auth
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-warning mt-3" id="advertencia" style="display: none;">
                        <span id="advertenciaMensaje"></span>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card mt-3">
                        <div class="card-body">
                            <h5 class="card-title">Productos con existencias bajas</h5>
                            <ul class="list-group list-group-flush" id="listaProductos">
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endauth

    </div>
@endsection
@section('AfterScript')
    <script>
        fetch("{{ route('inventario.productos_cantidad') }}", {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {

                // Renderizar la lista de productos
                var listaHtml = '';

                document.getElementById('advertenciaMensaje').innerHTML = data.advertencia || "";

                if (data.advertencia) {
                    document.getElementById('advertencia').style.display = 'block'; // Mostrar el alert
                } else {
                    document.getElementById('advertencia').style.display = 'none'; // Ocultar el alert
                }


                if (data.productosAdvertencia && Array.isArray(data.productosAdvertencia) && data.productosAdvertencia
                    .length > 0) {
                    data.productosAdvertencia.forEach(producto => {
                        listaHtml += '<li class="list-group-item">' + producto.nombre + ' (Cantidad: ' +
                            producto.cantidad + ')</li>';
                    });
                } else {
                    listaHtml = '<li class="list-group-item">No hay productos con existencias bajas</li>';
                }

                document.getElementById('listaProductos').innerHTML = listaHtml;
            })
            .catch(error => {
                console.error('Error:', error);
            });
    </script>
@endsection
