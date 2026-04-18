<x-layouts.app :title="__('Retirar Matrix Fund')">
    <div class="az-dashboard-top">
        <div>
            <p class="az-eyebrow mb-2">Reserva estratégica</p>
            <h1 class="az-title text-3xl font-semibold">Retirar saldo del Matrix Fund</h1>
            <p class="az-muted mt-2">Banda: <strong>{{ $matrixFund->gang?->name }}</strong> · Saldo actual: <strong>{{ number_format((float)$matrixFund->balance, 2, ',', '.') }} $</strong></p>
        </div>
    </div>

    @if ($errors->any())
        <div class="az-card p-4 mb-6"><ul class="az-muted">@foreach ($errors->all() as $error)<li>• {{ $error }}</li>@endforeach</ul></div>
    @endif

    <div class="az-card p-6">
        <form action="{{ route('admin.matrix-funds.withdraw', $matrixFund) }}" method="POST">
            @csrf
            <div class="grid md:grid-cols-2 gap-6">
                <div><label class="block text-sm mb-2">Importe a retirar</label><input type="number" step="0.01" min="0.01" name="amount" value="{{ old('amount') }}" class="az-input" required></div>
                <div><label class="block text-sm mb-2">Concepto</label><input type="text" name="concept" value="{{ old('concept') }}" class="az-input" required></div>
            </div>
            <div class="mt-6"><label class="block text-sm mb-2">Notas</label><textarea name="notes" rows="5" class="az-input">{{ old('notes') }}</textarea></div>
            <div class="az-card p-4 mt-6"><p class="az-muted text-sm mb-0">Esta operación reducirá el saldo disponible del Matrix Fund y generará un movimiento de salida.</p></div>
            <div class="mt-6 az-table-actions">
                <button type="submit" class="az-btn az-btn-primary">Registrar retirada</button>
                <a href="{{ route('admin.matrix-funds.show', $matrixFund) }}" class="az-btn az-btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</x-layouts.app>
