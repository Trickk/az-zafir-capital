<x-layouts.app :title="__('Empresas')">
    <div class="az-dashboard-top">
        <div>
            <p class="az-eyebrow mb-2">Holding</p>
            <h1 class="az-title text-3xl font-semibold">Empresas</h1>
            <p class="az-muted mt-2">Empresas registradas dentro del holding principal.</p>
        </div>
        <div class="az-dashboard-actions">
            <a href="{{ route('admin.companies.create') }}" class="az-dashboard-action">Nueva empresa</a>
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
                        <th>Empresa</th>
                        <th>Code</th>
                        <th>Tipo</th>
                        <th>Responsable</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($companies as $company)
                        <tr>
                            <td>
                                <div class="az-table-primary">{{ $company->name }}</div>
                                <div class="az-table-sub">{{ $company->legal_name ?: ($company->city ?: 'Sin localización') }}</div>
                            </td>
                            <td>{{ $company->company_code }}</td>
                            <td>{{ ucfirst($company->type) }}</td>
                            <td>{{ $company->responsible_name ?: '—' }}</td>
                            <td><span class="az-status">{{ $company->status === 'active' ? 'Activa' : 'Inactiva' }}</span></td>
                            <td>
                                <div class="az-table-actions">
                                    <a href="{{ route('admin.companies.edit', $company) }}" class="az-btn az-btn-secondary az-btn-sm">Editar</a>
                                    <form action="{{ route('admin.companies.destroy', $company) }}" method="POST" onsubmit="return confirm('¿Eliminar esta empresa?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="az-btn az-btn-secondary az-btn-sm">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="az-muted">No hay empresas registradas todavía.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $companies->links() }}</div>
    </div>
</x-layouts.app>
