@extends('layouts.app')

@section('title', 'Panel de Administrador')

@section('content')
    <h1>Panel de Administrador</h1>
    <!--Modales para nuevo usuario y nueva contraseña -->
    <div class="modal" tabindex="-1" id="new-user-modal">
        <div class="modal-dialog">
            <div class="modal-content border-success">
                <div class="modal-header">
                    <h5 class="modal-title">Crear Nuevo Usuario</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('admin.storeUser') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre del Usuario</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Crear Usuario</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="new-password-modal">
        <div class="modal-dialog">
            <div class="modal-content border-danger">
                <div class="modal-header">
                    <h5 class="modal-title ">Crear Nueva Contraseña</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.storePassword') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="password" class="form-label">Nueva Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Actualizar Contraseña</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Botón para crear un nuevo usuario -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#new-user-modal">
        <i class="fas fa-user-plus"></i> Nuevo Usuario
    </button>
    <button class="btn btn-warning mb-3" data-bs-toggle="modal" data-bs-target="#new-password-modal">
        <i class="fas fa-key"></i> Nueva Contraseña
    </button>

    <h2>Usuarios</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
                <th>Rol</th>
            </tr>
        </thead>
        <tbody>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>
                        @if ($user->role !== 'admin')
                            <!-- Botón para eliminar usuario -->
                            @if ($user->role !== 'manager')
                                <form action="{{ route('admin.deleteUser', $user->id) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Eliminar">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            @endif

                            <!-- Botón para cambiar el rol -->
                            <form action="{{ route('admin.changeRole', $user->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-primary btn-sm" title="Cambiar Rol">
                                    <i class="fas fa-user-shield"></i>
                                </button>
                            </form>
                        @else
                            <span class="badge bg-success">Admin</span>
                        @endif
                    </td>
                    <td>
                        @if ($user->role === 'admin')
                            <span class="badge bg-success">Administrador</span>
                        @elseif($user->role === 'manager')
                            <span class="badge bg-warning">Gerente</span>
                        @else
                            <span class="badge bg-secondary">Trabajador</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
