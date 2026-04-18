<x-layouts.app :title="__('Bandas')">
    <div class="az-dashboard-top">
        <div>
            <p class="az-eyebrow mb-2">Organizaciones internas</p>
            <h1 class="az-title text-3xl font-semibold">Bandas</h1>
            <p class="az-muted mt-2">Bandas registradas y vinculadas a empresas del sistema.</p>
        </div>
        <div class="az-dashboard-actions">
            <a href="{{ route('admin.gangs.create') }}" class="az-dashboard-action">Nueva banda</a>
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
                        <th>Code</th>
                        <th>Empresa</th>
                        <th>% gestor</th>
                        <th>% matrix</th>
                        <th>% operativo</th>
                        <th>Saldo</th>
                        <th>Matrix Fund</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($gangs as $gang)
                        @php $operatingPercent = 100 - (float) $gang->commission_percent - (float) ($gang->matrix_percent ?? 10); @endphp
                        <tr>
                            <td>
                                <div class="az-table-primary">{{ $gang->name }}</div>
                                <div class="az-table-sub">{{ $gang->boss_name ?: 'Sin líder' }}</div>
                            </td>
                            <td>{{ $gang->gang_code ?? 'Sin asignar' }}</td>
                            <td>
                                <div>{{ $gang->company?->name ?? 'Sin asignar' }}</div>
                                <div class="az-table-sub">{{ ucfirst($gang->company?->type ?? '') }}</div>
                            </td>
                            <td>{{ number_format((float) $gang->commission_percent, 2, ',', '.') }}%</td>
                            <td>{{ number_format((float) ($gang->matrix_percent ?? 10), 2, ',', '.') }}%</td>
                            <td>{{ number_format((float) $operatingPercent, 2, ',', '.') }}%</td>
                            <td>{{ number_format((float) $gang->operating_balance, 2, ',', '.') }} $</td>
                            <td>{{ number_format((float) ($gang->matrixFund?->balance ?? 0), 2, ',', '.') }} $</td>
                            <td><span class="az-status">{{ $gang->status === 'active' ? 'Activa' : ($gang->status === 'inactive' ? 'Inactiva' : 'Suspendida') }}</span></td>
                            <td>
                                <div class="az-table-actions">
                                    <a href="{{ route('admin.gangs.edit', $gang) }}" class="az-btn az-btn-secondary az-btn-sm">Editar</a>
                                    <form action="{{ route('admin.gangs.destroy', $gang) }}" method="POST" onsubmit="return confirm('¿Eliminar esta banda?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="az-btn az-btn-secondary az-btn-sm">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="az-muted">No hay bandas registradas todavía.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $gangs->links() }}</div>
    </div>
</x-layouts.app>
