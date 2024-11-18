<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.0/dist/solar/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>


    <title>@yield('title', 'FreshLog')</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container d-flex justify-content-between align-items-center">
            <!-- Logo alineado a la izquierda -->
            <a class="navbar-brand" href="/">
                <img src="{{ asset('images/logonav.png') }}" alt="logo" height="50px" class="rounded">
            </a>

            <!-- Botones alineados a la derecha -->
            @if (Auth::check())
                <div class="d-flex align-items-center">
                    <!-- Botón de Configuración -->
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-link text-light p-0 me-3"
                        title="Configuración">
                        <i class="fas fa-home" style="font-size: 1.5rem;"></i>
                    </a>
                    <div class="d-flex align-items-center">
                        <!-- Botón de Configuración -->
                        <a href="{{ route('configurations.index') }}" class="btn btn-link text-light p-0 me-3"
                            title="Configuración">
                            <i class="fas fa-cog" style="font-size: 1.5rem;"></i>
                        </a>

                        <!-- Botón de Cerrar Sesión -->
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-link text-light p-0" title="Cerrar Sesión">
                                <i class="fas fa-sign-out-alt" style="font-size: 1.5rem;"></i>
                            </button>
                        </form>
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
