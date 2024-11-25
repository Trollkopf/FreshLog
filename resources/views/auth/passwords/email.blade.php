@extends('layouts.app')


@section('content')
<div class="container">
    <h2>¿Olvidaste tu contraseña?</h2>
    <form action="{{ route('password.email') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="email" class="form-label">Correo electrónico</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Enviar enlace</button>
    </form>
    @if (session('status'))
        <div class="alert alert-success mt-3">
            {{ session('status') }}
        </div>
    @endif
</div>
@endsection
