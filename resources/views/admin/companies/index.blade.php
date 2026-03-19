<x-layouts.app :title="__('Empresas')">

    <div class="space-y-6">

        @if (session('success'))
            <div class="rounded-2xl border border-[rgba(212,175,55,0.20)] bg-[rgba(212,175,55,0.06)] px-5 py-4 text-[var(--az-gold-light)]">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="az-eyebrow mb-2">Entidades públicas</p>
                <h2 class="az-title text-3xl font-semibold">Empresas</h2>
                <p class="mt-2 az-muted">
                    Empresas visibles del sistema y emisoras de facturas.
                </p>
            </div>

            <a href="{{ route('admin.companies.create') }}" class="az-btn az-btn-primary">
                Crear empresa
            </a>
        </div>

        <div class="az-table-container">
            <table class="az-table">
                <thead>
                    <tr>
                        <th>Empresa</th>
                        <th>Responsable</th>
                        <th>Tipo</th>
                        <th>Ubicación</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($companies as $company)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    @if($company->logo_path)
                                        <img
                                            src="{{ asset('storage/' . $company->logo_path) }}"
                                            alt="{{ $company->name }}"
                                            class="h-20 w-20 rounded-lg object-cover border border-[var(--az-line)]"
                                        >
                                    @endif

                                    <div>
                                        <div class="az-table-primary">{{ $company->name }}</div>
                                        <div class="az-table-sub">{{ $company->legal_name ?: $company->slug }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $company->responsible_name ?? '—' }}</td>
                            <td>{{ ucfirst($company->type) }}</td>

                            <td>
                                {{ $company->city ?: '—' }}
                                @if($company->country)
                                    , {{ $company->country }}
                                @endif
                            </td>

                            <td>
                                <span class="az-status">
                                    {{ $company->status === 'active' ? 'Activa' : 'Inactiva' }}
                                </span>
                            </td>

                            <td>
                                <div class="az-table-actions">
                                    <a href="{{ route('admin.companies.edit', $company) }}" class="az-btn az-btn-secondary az-btn-sm">
                                        Editar
                                    </a>

                                    <form method="POST" action="{{ route('admin.companies.destroy', $company) }}" onsubmit="return confirm('¿Eliminar esta empresa?')">
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
                            <td colspan="5" class="az-muted">
                                No hay empresas registradas todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $companies->links() }}
        </div>

    </div>

</x-layouts.app>
