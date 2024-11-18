@extends('layouts.app')

@section('title', 'Bienvenida')

@section('content')

    <div class="d-flex justify-content-between align-items-center">
        <h1>Bienvenid@!</h1>
        <!-- Botón de Acceso para Operario -->
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#operarioModal">
            Acceder
        </button>
    </div>

    <!-- Modal para Ingresar Contraseña -->
    <div class="modal fade" id="operarioModal" tabindex="-1" aria-labelledby="operarioModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="operarioModalLabel">Acceso de Operario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <form id="operarioForm" action="/clean" method="GET">
                        @csrf
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Acceder</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <h2>Limpiezas de Hoy</h2>

    @if ($todayCleanings->isEmpty())
        <p>No hay registros de limpieza para hoy.</p>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre del Usuario</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($todayCleanings as $cleaning)
                    <tr>
                        <td>{{ $cleaning->user->name }}</td>
                        <td>{{ $cleaning->cleaned_at->format('Y-m-d') }}</td>
                        <td>{{ $cleaning->cleaned_at->format('H:i:s') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
@endsection
