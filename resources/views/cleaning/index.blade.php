@extends('layouts.app')

@section('title', 'Registro de Limpieza')

@section('content')
    <h1>Registro de Limpieza</h1>

    <p>Selecciona el operario que ha limpiado el baño:</p>

    @foreach ($users as $user)
        <div class="d-grid gap-2">
            <button data-user-id="{{ $user->id }}" class="btn btn-lg btn-info mb-2 clean-btn">
                {{ ucfirst($user->name) }}
                <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            </button>
        </div>
    @endforeach

    <!-- Modal de Alerta -->
    <div class="modal fade" id="alertModal" tabindex="-1" aria-labelledby="alertModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alertModalLabel">Registro reciente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>El último registro fue realizado por <strong id="lastUserName"></strong> a las <strong
                            id="lastTime"></strong>.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast de Éxito -->
    <div aria-live="polite" aria-atomic="true" class="position-relative">
        <div class="toast-container position-fixed bottom-0 end-0 p-3">
            <div id="toastMessage" class="toast align-items-center text-bg-success border-0" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body" id="toastBody">
                        <!-- Mensaje dinámico -->
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Cerrar"></button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.clean-btn');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            buttons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();

                    const userId = button.getAttribute('data-user-id');
                    const spinner = button.querySelector('.spinner-border');

                    // Activar el spinner y deshabilitar el botón
                    spinner.classList.remove('d-none');
                    button.disabled = true;

                    // Enviar datos con AJAX
                    fetch('/clean', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ user_id: userId })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Mostrar el toast con el mensaje de éxito
                                const toast = new bootstrap.Toast(document.getElementById('toastMessage'));
                                document.getElementById('toastBody').textContent = 'Registro de limpieza guardado con éxito.';
                                toast.show();
                            } else if (data.error) {
                                // Mostrar el modal con la información del último registro
                                const modal = new bootstrap.Modal(document.getElementById('alertModal'));
                                document.getElementById('lastUserName').textContent = data.lastUserName;
                                document.getElementById('lastTime').textContent = data.lastRecordTime;
                                modal.show();
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                        })
                        .finally(() => {
                            spinner.classList.add('d-none');
                            button.disabled = false;
                        });
                });
            });
        });
    </script>
@endsection
