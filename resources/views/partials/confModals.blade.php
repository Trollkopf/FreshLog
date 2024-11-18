<!-- Modal para Añadir Email -->
<div class="modal fade" id="addEmailModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('emails.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Añadir Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" name="email" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Añadir</button>
                </div>
            </form>
        </div>
    </div>
</div>

@foreach ($keys as $key => $label)
<div class="modal fade" id="editConfigModal{{ $key }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form
                action="{{ route('configurations.update', ['id' => $configurations->firstWhere('key', $key)->id ?? 0]) }}"
                method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Editar {{ $label }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label for="value" class="form-label">Nuevo Valor</label>
                    <input type="text" class="form-control" name="value"
                        value="{{ $configurations->firstWhere('key', $key)->value ?? '' }}" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

@foreach ($keys as $key => $label)
<div class="modal fade" id="addConfigModal{{ $key }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form
                action="{{ route('configurations.store') }}"
                method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Añadir {{ $label }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label for="value" class="form-label">Nuevo Valor</label>
                    <input type="text" class="form-control" name="key"
                    value="{{$key}}" hidden>
                    <input type="text" class="form-control" name="value"
                        value="{{ $configurations->firstWhere('key', $key)->value ?? '' }}" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach