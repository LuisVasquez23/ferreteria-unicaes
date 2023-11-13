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

        {{-- @if (Auth::user()->role === 'admin' || Auth::user()->role === 'empleado') --}}
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
        {{-- @endif --}}

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
        console.log('Data:', data);
        console.log('Productos Advertencia:', data.productosAdvertencia);

        var listaHtml = '';

        document.getElementById('advertenciaMensaje').innerHTML = data.advertencia || "";

        if (data.advertencia) {
            document.getElementById('advertencia').style.display = 'block'; // Mostrar el alert
        } else {
            document.getElementById('advertencia').style.display = 'none'; // Ocultar el alert
        }

        // Nueva funcion de Iterar 
        Object.keys(data.productosAdvertencia).forEach(key => {
            const producto = data.productosAdvertencia[key];
            console.log('Nombre:', producto.nombre);
            console.log('Cantidad:', producto.cantidad);

            listaHtml += '<li class="list-group-item">' + producto.nombre + ' (Cantidad: ' +
                producto.cantidad + ')</li>';
        });

       
        document.getElementById('listaProductos').innerHTML = listaHtml;
    })
    .catch(error => {
        console.log('Error:', error);
    });
</script>
@endsection
