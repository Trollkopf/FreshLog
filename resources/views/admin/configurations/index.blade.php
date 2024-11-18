@extends('layouts.app')

@section('title', 'Configuración')

@section('content')
    <h1><i class="fas fa-cog" style="font-size: 1.5rem;"></i> Configuración</h1>

    <div class="accordion mt-5" id="configAccordion">

        <!-- Emails -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="emailsHeading">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#emailsCollapse"
                    aria-expanded="true" aria-controls="emailsCollapse">
                    Emails Configurados
                </button>
            </h2>
            <div id="emailsCollapse" class="accordion-collapse collapse show" aria-labelledby="emailsHeading"
                data-bs-parent="#configAccordion">
                <div class="accordion-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0">Emails</h3>
                        <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addEmailModal">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($emails as $email)
                                <tr>
                                    <td>{{ $email->email }}</td>
                                    <td>
                                        <form action="{{ route('emails.delete', $email->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"><i
                                                    class="fas fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center">
                                        No hay emails configurados.
                                        <button class="btn btn-success btn-sm mt-2" data-bs-toggle="modal"
                                            data-bs-target="#addEmailModal">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Configuración General -->
        <div class="accordion-item">
            <h2 class="accordion-header" id="generalConfigHeading">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#generalConfigCollapse" aria-expanded="false" aria-controls="generalConfigCollapse">
                    Configuración General
                </button>
            </h2>
            <div id="generalConfigCollapse" class="accordion-collapse collapse" aria-labelledby="generalConfigHeading"
                data-bs-parent="#configAccordion">
                <div class="accordion-body">
                    <h3>Horario y Configuración General</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Configuración</th>
                                <th>Valor</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $keys = [
                                    'report_time' => 'Hora de Envío de Informes',
                                    'opening_time' => 'Hora de Apertura',
                                    'closing_time' => 'Hora de Cierre',
                                ];
                            @endphp

                            @foreach ($keys as $key => $label)
                                @php
                                    $config = $configurations->firstWhere('key', $key);
                                @endphp

                                @if ($config)
                                    <tr>
                                        <td>{{ $label }}</td>
                                        <td>{{ $config->value }}</td>
                                        <td>
                                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editConfigModal{{ $key }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @else
                                    <tr>
                                        <td>{{ $label }}</td>
                                        <td colspan="2" class="text-center">
                                            No configurado.
                                            <button class="btn btn-success btn-sm mt-2" data-bs-toggle="modal"
                                                data-bs-target="#addConfigModal{{ $key }}">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('partials.confModals')
    <!-- Modales -->

@endsection
