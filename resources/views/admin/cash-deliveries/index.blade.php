<x-layouts.app :title="__('Entregas de dinero')">
    <div class="az-dashboard-top">
        <div>
            <p class="az-eyebrow mb-2">Operativa interna</p>
            <h1 class="az-title text-3xl font-semibold">Entregas de dinero</h1>
            <p class="az-muted mt-2">Registro de entregas asociadas a bandas y reparto automático.</p>
        </div>
        <div class="az-dashboard-actions">
            <a href="{{ route('admin.cash-deliveries.create') }}" class="az-dashboard-action">Registrar entrega</a>
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
                        <th>Referencia</th>
                        <th>Banda</th>
                        <th>Empresa</th>
                        <th>Importe</th>
                        <th>Matrix</th>
                        <th>Gestor</th>
                        <th>Operativo</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cashDeliveries as $delivery)
                        <tr>
                            <td><div class="az-table-primary">{{ $delivery->delivery_number }}</div><div class="az-table-sub">{{ $delivery->created_at?->format('d/m/Y H:i') ?: '—' }}</div></td>
                            <td>{{ $delivery->gang?->name ?? '—' }}</td>
                            <td>{{ $delivery->company?->name ?? '—' }}</td>
                            <td>{{ number_format((float) $delivery->amount, 2, ',', '.') }} $</td>
                            <td>{{ number_format((float) $delivery->matrix_amount, 2, ',', '.') }} $</td>
                            <td>{{ number_format((float) $delivery->commission_amount, 2, ',', '.') }} $</td>
                            <td>{{ number_format((float) $delivery->operating_amount, 2, ',', '.') }} $</td>
                            <td><span class="az-status">{{ ucfirst($delivery->status) }}</span></td>
                            <td>
                                <div class="az-table-actions">
                                    <a href="{{ route('admin.cash-deliveries.edit', $delivery) }}" class="az-btn az-btn-secondary az-btn-sm">Editar</a>
                                    <form action="{{ route('admin.cash-deliveries.destroy', $delivery) }}" method="POST" onsubmit="return confirm('¿Eliminar esta entrega?')">
                                        @csrf
                                         @method('DELETE')
                                        <button type="submit" class="az-btn az-btn-secondary az-btn-sm">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="az-muted">No hay entregas registradas todavía.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $cashDeliveries->links() }}</div>
    </div>
</x-layouts.app>
