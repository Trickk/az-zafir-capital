<x-layouts.app :title="__('Crear holding')">

    <div class="space-y-6">
        <div>
            <p class="az-eyebrow mb-2">Estructura corporativa</p>
            <h2 class="az-title text-3xl font-semibold">Crear Holding</h2>
            <p class="mt-2 az-muted">
                Registrar un nuevo holding dentro de la estructura financiera.
            </p>
        </div>

        <div class="az-card p-6">
            <form method="POST" action="{{ route('admin.holdings.store') }}" class="space-y-6">
                @csrf

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 text-sm az-gold">Banda</label>
                        <select name="gang_id" class="az-input">
                            <option value="">Sin asignar</option>
                            @foreach($gangs as $gang)
                                <option value="{{ $gang->id }}" @selected(old('gang_id') == $gang->id)>
                                    {{ $gang->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('gang_id') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Nombre</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="az-input" required>
                        @error('name') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Nombre legal</label>
                        <input type="text" name="legal_name" value="{{ old('legal_name') }}" class="az-input">
                        @error('legal_name') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Sector</label>
                        <input type="text" name="sector" value="{{ old('sector') }}" class="az-input">
                        @error('sector') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Contacto</label>
                        <input type="text" name="contact_name" value="{{ old('contact_name') }}" class="az-input">
                        @error('contact_name') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Teléfono</label>
                        <input type="text" name="contact_phone" value="{{ old('contact_phone') }}" class="az-input">
                        @error('contact_phone') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Email</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email') }}" class="az-input">
                        @error('contact_email') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Nivel de confianza</label>
                        <select name="trust_level" class="az-input" required>
                            @for($i = 1; $i <= 5; $i++)
                                <option value="{{ $i }}" @selected(old('trust_level', 1) == $i)>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                        @error('trust_level') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Comisión por defecto (%)</label>
                        <input type="number" step="0.01" name="default_commission_percent" value="{{ old('default_commission_percent', 20) }}" class="az-input" required>
                        @error('default_commission_percent') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Estado</label>
                        <select name="status" class="az-input" required>
                            <option value="active" @selected(old('status') === 'active')>Activo</option>
                            <option value="inactive" @selected(old('status') === 'inactive')>Inactivo</option>
                            <option value="blocked" @selected(old('status') === 'blocked')>Bloqueado</option>
                        </select>
                        @error('status') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm az-gold">Notas</label>
                    <textarea name="notes" rows="5" class="az-input">{{ old('notes') }}</textarea>
                    @error('notes') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="az-btn az-btn-primary">
                        Guardar holding
                    </button>

                    <a href="{{ route('admin.holdings.index') }}" class="az-btn az-btn-secondary">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
