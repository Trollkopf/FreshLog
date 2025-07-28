<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/solar/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <title>@yield('title', 'FreshLog')</title>
    <style>
        @media (max-width: 576px) {
            .navbar-brand span {
                display: block;
                font-size: 0.9rem; /* Ajusta el tamaño de texto si es necesario */
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container d-flex justify-content-between align-items-center">
            <!-- Logo alineado a la izquierda -->
            <a class="navbar-brand" href="/">
                <img src="{{ asset('images/logonav.png') }}" alt="logo" height="50px" class="rounded mb-2">
                <span class="ms-2">{{ config('app.place')}} - Diario de limpiezas</span>
                <span class="text-primary ms-2">Cleaning Schedule</span>
            </a>

            <!-- Menú hamburguesa para usuario registrado -->
            @if (Auth::check())
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#userMenu"
                    aria-controls="userMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="userMenu">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link text-light" title="Dashboard">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('configurations.index') }}" class="nav-link text-light" title="Configuración">
                                <i class="fas fa-cog"></i> Configuración
                            </a>
                        </li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-link nav-link text-light" title="Cerrar Sesión">
                                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endif
        </div>
    </nav>

    <div class="container mt-4">
        @yield('content')
    </div>

    <!-- Contenedor del Toast -->
    <div aria-live="polite" aria-atomic="true" style="position: relative; min-height: 200px;">
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div id="toast-message" class="toast align-items-center text-white border-0" role="alert"
                aria-live="assertive" aria-atomic="true" data-bs-delay="3000">
                <div class="d-flex">
                    <div id="toast-body" class="toast-body">
                        <!-- Mensaje dinámico -->
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>

    @yield('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Determina el mensaje y el tipo de toast a mostrar
            let message = "";
            let toastClass = "";
            @if (session('success'))
                message = "{{ session('success') }}";
                toastClass = "bg-success";
            @elseif (session('error'))
                message = "{{ session('error') }}";
                toastClass = "bg-danger";
            @endif

            // Si hay un mensaje, muestra el toast
            if (message) {
                var toastEl = document.getElementById('toast-message');
                var toastBody = document.getElementById('toast-body');

                // Asigna el mensaje y la clase de fondo correspondiente
                toastBody.textContent = message;
                toastEl.classList.add(toastClass);

                // Muestra el toast
                var toast = new bootstrap.Toast(toastEl);
                toast.show();
            }
        });
    </script>
</body>

</html>
