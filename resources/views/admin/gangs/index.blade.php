<x-layouts.app :title="__('Gangs')">

    <div class="space-y-6">

        @if (session('success'))
            <div class="rounded-2xl border border-[rgba(212,175,55,0.20)] bg-[rgba(212,175,55,0.06)] px-5 py-4 text-[var(--az-gold-light)]">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="az-eyebrow mb-2">Organizaciones</p>
                <h2 class="az-title text-3xl font-semibold">Gestión de Bandas</h2>
                <p class="mt-2 az-muted">
                    Bandas registradas que operan dentro de la estructura de Al-Zafir.
                </p>
            </div>

            <a href="{{ route('admin.gangs.create') }}" class="az-btn az-btn-primary">
                Registrar Banda
            </a>
        </div>

        <div class="az-card p-6">
            <div class="az-table-wrap">
                <table class="az-table">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Líder</th>
                            <th>Dinero sucio</th>
                            <th>Estado</th>
                            <th class="text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($gangs as $gang)
                            <tr>
                                <td>
                                    <div class="font-medium text-[var(--az-text)]">{{ $gang->name }}</div>
                                    <div class="text-sm az-muted">{{ $gang->slug }}</div>
                                </td>

                                <td>
                                    <h2>{{ $gang->boss_name ?: '—' }}</h2>
                                    <em class="text-sm az-muted">{{ $gang->description ?: '—' }}</em>
                                </td>

                                <td>${{ number_format((float) $gang->dirty_balance, 2) }}</td>

                                <td>
                                    <span class="az-badge az-badge-gold">
                                        {{ $gang->status == 'active' ? 'Activa' : '' }}
                                        {{ $gang->status == 'inactive' ? 'Inactiva' : '' }}
                                        {{ $gang->status == 'suspended' ? 'Suspendida' : '' }}
                                    </span>
                                </td>

                                <td class="text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.gangs.edit', $gang) }}" class="az-btn az-btn-secondary !py-2 !px-4">
                                            Editar
                                        </a>

                                        <form method="POST" action="{{ route('admin.gangs.destroy', $gang) }}" onsubmit="return confirm('Delete this gang?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="az-btn az-btn-secondary !py-2 !px-4">
                                                Eliminar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="az-muted">
                                    No hay bandas registradas todavía.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $gangs->links() }}
            </div>
        </div>
    </div>

</x-layouts.app>
