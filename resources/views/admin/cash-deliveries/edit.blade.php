<x-layouts.app :title="__('Editar entrega de dinero')">
    <div class="az-dashboard-top">
        <div>
            <p class="az-eyebrow mb-2">Operativa interna</p>
            <h1 class="az-title text-3xl font-semibold">Editar entrega de dinero</h1>
            <p class="az-muted mt-2">Modificar una entrega registrada y recalcular balances si es necesario.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="az-card p-4 mb-6"><ul class="az-muted">@foreach ($errors->all() as $error)<li>• { $error }</li>@endforeach</ul></div>
    @endif

    <div class="az-card p-6">
        <form action="{{ route('admin.cash-deliveries.update', $cashDelivery) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm mb-2">Banda</label>
                    <select name="gang_id" class="az-input" required>
                        <option value="">Seleccionar banda</option>
                        @foreach($gangs as $gang)
                            <option value="{{ $gang->id }}" @selected(old('gang_id', $cashDelivery->gang_id) == $gang->id)>{{ $gang->name }} @if($gang->company) — {{ $gang->company->name }} @endif</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-sm mb-2">Importe</label><input type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount', $cashDelivery->amount) }}" class="az-input" required></div>
                <div>
                    <label class="block text-sm mb-2">Estado</label>
                    <select name="status" class="az-input">
                        <option value="pending" @selected(old('status', $cashDelivery->status) === 'pending')>Pendiente</option>
                        <option value="received" @selected(old('status', $cashDelivery->status) === 'received')>Recibida</option>
                        <option value="verified" @selected(old('status', $cashDelivery->status) === 'verified')>Verificada</option>
                        <option value="cancelled" @selected(old('status', $cashDelivery->status) === 'cancelled')>Cancelada</option>
                    </select>
                </div>
            </div>
            <div class="az-card p-4 mt-6"><p class="az-muted text-sm mb-0">El sistema calculará automáticamente el importe destinado a Matrix Fund, gastos de gestión y saldo operativo.</p></div>
            <div class="mt-6"><label class="block text-sm mb-2">Notas</label><textarea name="notes" rows="5" class="az-input">{{ old('notes', $cashDelivery->notes) }}</textarea></div>
            <div class="mt-6 az-table-actions">
                <button type="submit" class="az-btn az-btn-primary">Guardar cambios</button>
                <a href="{ route('admin.cash-deliveries.index') }" class="az-btn az-btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</x-layouts.app>
