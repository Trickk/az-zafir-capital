<x-layouts.app :title="__('Detalle Matrix Fund')">
    <div class="az-dashboard-top">
        <div>
            <p class="az-eyebrow mb-2">Reserva estratégica</p>
            <h1 class="az-title text-3xl font-semibold">Matrix Fund - {{ $matrixFund->gang?->name }}</h1>
            <p class="az-muted mt-2">Detalle del fondo y movimientos asociados.</p>
        </div>
        <div class="az-dashboard-actions">
            <a href="{{ route('admin.matrix-funds.withdraw.form', $matrixFund) }}" class="az-dashboard-action">Retirar saldo</a>
        </div>
    </div>

    @if (session('success'))
        <div class="az-card p-4 mb-6"><span class="az-status">{{ session('success') }}</span></div>
    @endif

    <div class="grid md:grid-cols-3 gap-6 mb-6">
        <div class="az-card p-5"><p class="az-eyebrow mb-2">Saldo actual</p><p class="az-title text-2xl">{{ number_format((float)$matrixFund->balance, 2, ',', '.') }} $</p></div>
        <div class="az-card p-5"><p class="az-eyebrow mb-2">Total entradas</p><p class="az-title text-2xl">{{ number_format((float)$matrixFund->total_in, 2, ',', '.') }} $</p></div>
        <div class="az-card p-5"><p class="az-eyebrow mb-2">Total salidas</p><p class="az-title text-2xl">{{ number_format((float)$matrixFund->total_out, 2, ',', '.') }} $</p></div>
    </div>

    <div class="az-card p-6">
        <p class="az-eyebrow mb-4">Movimientos</p>
        <div class="az-table-container">
            <table class="az-table">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Importe</th>
                        <th>Concepto</th>
                        <th>Notas</th>
                        <th>Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($matrixFund->movements as $movement)
                        <tr>
                            <td><span class="az-status">{{ $movement->type === 'in' ? 'Entrada' : ($movement->type === 'out' ? 'Salida' : 'Ajuste') }}</span></td>
                            <td>{{ number_format((float)$movement->amount, 2, ',', '.') }} $</td>
                            <td>{{ $movement->concept }}</td>
                            <td>{{ $movement->notes }}</td>
                            <td>{{ $movement->created_at?->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="az-muted">Todavía no hay movimientos registrados en este fondo.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
