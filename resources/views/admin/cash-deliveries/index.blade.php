<x-layouts.app :title="__('Entregas de dinero')">

    <div class="space-y-6">

        @if (session('success'))
            <div class="rounded-2xl border border-[rgba(212,175,55,0.20)] bg-[rgba(212,175,55,0.06)] px-5 py-4 text-[var(--az-gold-light)]">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="az-eyebrow mb-2">Operativa interna</p>
                <h2 class="az-title text-3xl font-semibold">Entregas de dinero</h2>
                <p class="mt-2 az-muted">
                    Registro de entregas de dinero sucio asociadas a bandas y empresas.
                </p>
            </div>

            <a href="{{ route('admin.cash-deliveries.create') }}" class="az-btn az-btn-primary">
                Registrar entrega
            </a>
        </div>

        <div class="az-table-container">
            <table class="az-table">
                <thead>
                    <tr>
                        <th>Referencia</th>
                        <th>Banda</th>
                        <th>Empresa</th>
                        <th>Importe</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($cashDeliveries as $delivery)
                        <tr>
                            <td>
                                <div class="az-table-primary">{{ $delivery->delivery_number }}</div>
                                <div class="az-table-sub">
                                    {{ optional($delivery->delivered_at)->format('d/m/Y H:i') ?: '—' }}
                                </div>
                            </td>

                            <td>{{ $delivery->gang?->name ?? '—' }}</td>
                            <td>{{ $delivery->company?->name ?? '—' }}</td>
                            <td>{{ number_format((float) $delivery->amount, 2, ',', '.') }} $</td>

                            <td>
                                <span class="az-status">
                                    @if($delivery->status === 'pending')
                                        Pendiente
                                    @elseif($delivery->status === 'received')
                                        Recibida
                                    @elseif($delivery->status === 'verified')
                                        Verificada
                                    @else
                                        Cancelada
                                    @endif
                                </span>
                            </td>

                            <td>
                                <div class="az-table-actions">
                                    <a href="{{ route('admin.cash-deliveries.edit', $delivery) }}" class="az-btn az-btn-secondary az-btn-sm">
                                        Editar
                                    </a>

                                    <form method="POST" action="{{ route('admin.cash-deliveries.destroy', $delivery) }}" onsubmit="return confirm('¿Eliminar esta entrega?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="az-btn az-btn-secondary az-btn-sm">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="az-muted">
                                No hay entregas registradas todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $cashDeliveries->links() }}
        </div>
    </div>

</x-layouts.app>
