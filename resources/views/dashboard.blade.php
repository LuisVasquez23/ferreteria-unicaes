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

        @if(session('advertencia'))
            <div class="alert alert-warning mt-3">
                {{ session('advertencia') }}
            </div>
        @endif

        @if(session('productosAdvertencia'))
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Productos con existencias bajas</h5>
                    <ul class="list-group list-group-flush">
                        @foreach(session('productosAdvertencia') as $producto)
                            <li class="list-group-item">{{ $producto->nombre }} - Cantidad: {{ $producto->cantidad }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>
@endsection


