@extends('layouts.app')

@section('title', 'Registro de Limpieza')

@section('content')
    <h1>Registro de Limpieza</h1>

    <p>Selecciona el operario que ha limpiado el baño:</p>

    @foreach ($users as $user)
        <form action="/clean" method="POST" class="me-2">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-lg btn-info mb-2">{{ ucfirst($user->name) }}</button>
            </div>
        </form>
    @endforeach
@endsection
