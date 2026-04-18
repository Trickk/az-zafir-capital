<x-layouts.app :title="__('Matrix Fund')">
    <div class="az-dashboard-top">
        <div>
            <p class="az-eyebrow mb-2">Reserva estratégica</p>
            <h1 class="az-title text-3xl font-semibold">Matrix Fund</h1>
            <p class="az-muted mt-2">Fondos de reserva acumulados por cada banda.</p>
        </div>
    </div>

    @if (session('success'))
        <div class="az-card p-4 mb-6"><span class="az-status">{{ session('success') }}</span></div>
    @endif

    <div class="az-card p-6">
        <div class="az-table-container">
            <table class="az-table">
                <thead>
                    <tr>
                        <th>Banda</th>
                        <th>Saldo</th>
                        <th>Total entradas</th>
                        <th>Total salidas</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($funds as $fund)
                        <tr>
                            <td>{{ $fund->gang?->name }}</td>
                            <td>{{ number_format((float) $fund->balance, 2, ',', '.') }} $</td>
                            <td>{{ number_format((float) $fund->total_in, 2, ',', '.') }} $</td>
                            <td>{{ number_format((float) $fund->total_out, 2, ',', '.') }} $</td>
                            <td>
                                <div class="az-table-actions">
                                    <a href="{{ route('admin.matrix-funds.show', $fund) }}" class="az-btn az-btn-secondary az-btn-sm">Detalle</a>
                                    <a href="{{ route('admin.matrix-funds.withdraw.form', $fund) }}" class="az-btn az-btn-primary az-btn-sm">Retirar</a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="az-muted">No hay fondos Matrix todavía.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $funds->links() }}</div>
    </div>
</x-layouts.app>
