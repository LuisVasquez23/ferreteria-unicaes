@extends('layouts/guest')
@section('title', 'Login')
@section('contenido')
    <a href="{{ route('login') }}" class="text-nowrap logo-img text-center d-block py-3 w-100">
        <img src="{{ asset('images/logos/unicaes.jpg') }}" width="180" alt="Logo sistema" />
    </a>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Correo: </label>
            <input type="email" class="form-control" name="email" id="email" :value="old('email')" />
        </div>
        <div class="mb-4">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" />
        </div>
        <input type="submit" class="btn btn-primary w-100 py-8 fs-4 mb-4 rounded-2" value="Iniciar sesion">
    </form>
@endsection
