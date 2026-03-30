<x-layouts.app :title="__('Crear banda')">

    <div class="space-y-6">
        <div>
            <p class="az-eyebrow mb-2">Organizaciones internas</p>
            <h2 class="az-title text-3xl font-semibold">Crear banda</h2>
            <p class="mt-2 az-muted">
                Registrar una nueva banda y configurar su empresa asociada y liquidación.
            </p>
        </div>

        <div class="az-card p-6">
            @if ($errors->any())
                <div class="mb-6 rounded-2xl border border-red-500/30 bg-red-500/10 px-5 py-4 text-red-300">
                    <ul class="space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.gangs.store') }}" class="space-y-6">
                @csrf

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 text-sm az-gold">Nombre</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="az-input" required>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Empresa asociada</label>
                        <select name="company_id" class="az-input">
                            <option value="">Seleccionar empresa</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" @selected(old('company_id') == $company->id)>
                                    {{ $company->name }} ({{ $company->type }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Líder</label>
                        <input type="text" name="boss_name" value="{{ old('boss_name') }}" class="az-input">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Contacto Discord</label>
                        <input type="text" name="contact_discord" value="{{ old('contact_discord') }}" class="az-input">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Porcentaje que se le quita a la banda (%)</label>
                        <input type="number" step="0.01" name="commission_percent" value="{{ old('commission_percent', 20) }}" class="az-input" required>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Estado</label>
                        <select name="status" class="az-input" required>
                            <option value="active" @selected(old('status') === 'active')>Activa</option>
                            <option value="inactive" @selected(old('status') === 'inactive')>Inactiva</option>
                            <option value="suspended" @selected(old('status') === 'suspended')>Suspendida</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm az-gold">Descripción</label>
                    <textarea name="description" rows="5" class="az-input">{{ old('description') }}</textarea>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="az-btn az-btn-primary">
                        Guardar banda
                    </button>

                    <a href="{{ route('admin.gangs.index') }}" class="az-btn az-btn-secondary">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
