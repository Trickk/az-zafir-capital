<x-layouts.app :title="__('Bandas')">

    <div class="space-y-6">

        @if (session('success'))
            <div class="rounded-2xl border border-[rgba(212,175,55,0.20)] bg-[rgba(212,175,55,0.06)] px-5 py-4 text-[var(--az-gold-light)]">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="az-eyebrow mb-2">Organizaciones internas</p>
                <h2 class="az-title text-3xl font-semibold">Bandas</h2>
                <p class="mt-2 az-muted">
                    Bandas registradas y vinculadas a empresas del sistema.
                </p>
            </div>

            <a href="{{ route('admin.gangs.create') }}" class="az-btn az-btn-primary">
                <i class="fa fa-plus" aria-hidden="true"></i>
            </a>
        </div>

        <div class="az-table-container">
            <table class="az-table">
                <thead>
                    <tr>
                        <th>Banda</th>
                        <th>Code</th>
                        <th>Empresa</th>
                        <th>% comision</th>
                        <th>Saldo</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($gangs as $gang)
                        <tr>
                            <td>
                                <div class="az-table-primary">{{ $gang->name }}</div>
                                <div class="az-table-sub">{{ $gang->boss_name ?: $gang->slug }}</div>
                            </td>
                            <td>{{ $gang->gang_code ?? 'Sin asignar' }}</td>
                            <td>{{ $gang->company?->name ?? 'Sin asignar' }}
                                <br><em>{{ ucfirst($gang->company?->type ?? '') }}</em>
                            </td>

                            <td>{{ number_format((float) $gang->commission_percent, 2, ',', '.') }}%</td>

                            <td>{{ number_format((float) $gang->dirty_balance, 2, ',', '.') }} $</td>

                            <td>
                                <span class="az-status">
                                    @if($gang->status === 'active')
                                        Activa
                                    @elseif($gang->status === 'inactive')
                                        Inactiva
                                    @else
                                        Suspendida
                                    @endif
                                </span>
                            </td>

                            <td>
                                <div class="az-table-actions">
                                    <a href="{{ route('admin.gangs.edit', $gang) }}" class="az-btn az-btn-secondary az-btn-sm">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>

                                    <form method="POST" action="{{ route('admin.gangs.destroy', $gang) }}" onsubmit="return confirm('¿Eliminar esta banda?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="az-btn az-btn-secondary az-btn-sm">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="az-muted">
                                No hay bandas registradas todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $gangs->links() }}
        </div>

    </div>

</x-layouts.app>
