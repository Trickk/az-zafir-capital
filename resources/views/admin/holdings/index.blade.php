<x-layouts.app :title="__('Holdings')">

    <div class="space-y-6">

        @if (session('success'))
            <div class="rounded-2xl border border-[rgba(212,175,55,0.20)] bg-[rgba(212,175,55,0.06)] px-5 py-4 text-[var(--az-gold-light)]">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="az-eyebrow mb-2">Estructura corporativa</p>
                <h2 class="az-title text-3xl font-semibold">Gestión de Holdings</h2>
                <p class="mt-2 az-muted">
                    Holdings operativos asignados a cada banda dentro de la red de Al-Zafir.
                </p>
            </div>

            <a href="{{ route('admin.holdings.create') }}" class="az-btn az-btn-primary">
                Crear holding
            </a>
        </div>

        <div class="az-table-container">
            <table class="az-table">
                <thead>
                    <tr>
                        <th>Holding</th>
                        <th>Banda</th>
                        <th>Sector</th>
                        <th>Saldo sucio</th>
                        <th>Comisión</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($holdings as $holding)
                        <tr>
                            <td>
                                <div class="az-table-primary">{{ $holding->name }}</div>
                                <div class="az-table-sub">{{ $holding->legal_name ?: $holding->slug }}</div>
                            </td>

                            <td>{{ $holding->gang?->name ?? '—' }}</td>

                            <td>{{ $holding->sector ?: '—' }}</td>

                            <td>{{ number_format((float) $holding->dirty_balance, 2, ',', '.') }} €</td>

                            <td>{{ number_format((float) $holding->default_commission_percent, 2, ',', '.') }}%</td>

                            <td>
                                <span class="az-status">
                                    @if($holding->status === 'active')
                                        Activo
                                    @elseif($holding->status === 'inactive')
                                        Inactivo
                                    @else
                                        Bloqueado
                                    @endif
                                </span>
                            </td>

                            <td>
                                <div class="az-table-actions">
                                    <a href="{{ route('admin.holdings.edit', $holding) }}" class="az-btn az-btn-secondary az-btn-sm">
                                        Editar
                                    </a>

                                    <form method="POST" action="{{ route('admin.holdings.destroy', $holding) }}" onsubmit="return confirm('¿Eliminar este holding?')">
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
                                No hay holdings registrados todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $holdings->links() }}
        </div>

    </div>

</x-layouts.app>
