<x-layouts.app :title="__('Registrar entrega de dinero')">
    <div class="az-dashboard-top">
        <div>
            <p class="az-eyebrow mb-2">Operativa interna</p>
            <h1 class="az-title text-3xl font-semibold">Registrar entrega de dinero</h1>
            <p class="az-muted mt-2">Registrar una entrega asociada a una banda y aplicar el reparto configurado.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="az-card p-4 mb-6"><ul class="az-muted">@foreach ($errors->all() as $error)<li>• { $error }</li>@endforeach</ul></div>
    @endif

    <div class="az-card p-6">
        <form action="{{ route('admin.cash-deliveries.store') }}" method="POST">

            @csrf

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm mb-2">Banda</label>
                    <select name="gang_id" class="az-input" required>
                        <option value="">Seleccionar banda</option>
                        @foreach($gangs as $gang)
                            <option value="{{ $gang->id }}" @selected(old('gang_id') == $gang->id)>{{ $gang->name }} @if($gang->company) — {{$gang->company->name }} @endif</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-sm mb-2">Importe</label><input type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount') }}" class="az-input" required></div>
                <div>
                    <label class="block text-sm mb-2">Estado</label>
                    <select name="status" class="az-input">
                        <option value="pending" @selected(old('status','received') === 'pending')>Pendiente</option>
                        <option value="received" @selected(old('status','received') === 'received')>Recibida</option>
                        <option value="verified" @selected(old('status','received') === 'verified')>Verificada</option>
                        <option value="cancelled" @selected(old('status','received') === 'cancelled')>Cancelada</option>
                    </select>
                </div>
            </div>
            <div class="az-card p-4 mt-6"><p class="az-muted text-sm mb-0">El sistema calculará automáticamente el importe destinado a Matrix Fund, gastos de gestión y saldo operativo.</p></div>
            <div class="mt-6"><label class="block text-sm mb-2">Notas</label><textarea name="notes" rows="5" class="az-input">{{ old('notes') }}</textarea></div>
            <div class="mt-6 az-table-actions">
                <button type="submit" class="az-btn az-btn-primary">Guardar entrega</button>
                <a href="{ route('admin.cash-deliveries.index') }" class="az-btn az-btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</x-layouts.app>
