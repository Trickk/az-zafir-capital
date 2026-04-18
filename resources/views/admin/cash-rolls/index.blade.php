<x-layouts.app :title="__('Rulos de dinero')">

    <div class="space-y-6">

        @if (session('success'))
            <div class="rounded-2xl border border-[rgba(212,175,55,0.20)] bg-[rgba(212,175,55,0.06)] px-5 py-4 text-[var(--az-gold-light)]">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="az-eyebrow mb-2">Entrada de capital</p>
                <h2 class="az-title text-3xl font-semibold">Dinero sucio</h2>
                <p class="mt-2 az-muted">
                    Registro de entregas de dinero sucio y actualización de balances.
                </p>
            </div>

            <a href="{{ route('admin.cash-rolls.create') }}" class="az-btn az-btn-primary">
                Nueva entrega
            </a>
        </div>

        <div class="az-table-container">
            <table class="az-table">
                <thead>
                    <tr>
                        <th>Referencia</th>
                        <th>Banda</th>
                        <th>Holding</th>
                        <th>Importe</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($cashRolls as $roll)
                        <tr>
                            <td>
                                <div class="az-table-primary">{{ $roll->delivery_number }}</div>
                                <div class="az-table-sub">{{ optional($roll->delivered_at)?->format('d/m/Y H:i') }}</div>
                            </td>

                            <td>{{ $roll->gang?->name ?? '-' }}</td>

                            <td>{{ $roll->gang->holding->name ?? '-' }}</td>

                            <td>{{ number_format((float) $roll->amount, 2, ',', '.') }} €</td>

                            <td>
                                <span class="az-status">
                                    @if($roll->status === 'pending')
                                        Pendiente
                                    @elseif($roll->status === 'received')
                                        Recibido
                                    @elseif($roll->status === 'verified')
                                        Verificado
                                    @else
                                        Cancelado
                                    @endif
                                </span>
                            </td>

                            <td>
                                <div class="az-table-actions">
                                    <form method="POST" action="{{ route('admin.cash-rolls.destroy', $roll) }}" onsubmit="return confirm('¿Eliminar este rulo?')">
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
                            <td colspan="7" class="az-muted">
                                No hay rulos registrados todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $cashRolls->links() }}
        </div>

    </div>

</x-layouts.app>
