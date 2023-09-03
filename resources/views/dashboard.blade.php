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
    </div>
@endsection
