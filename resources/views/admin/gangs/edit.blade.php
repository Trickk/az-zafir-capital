<x-layouts.app :title="__('Edit Gang')">

    <div class="space-y-6">
        <div>
            <p class="az-eyebrow mb-2">Network</p>
            <h2 class="az-title text-3xl font-semibold">Edit Gang</h2>
            <p class="mt-2 az-muted">
                Update the organization details and operational status.
            </p>
        </div>

        <div class="az-card p-6">
            <form method="POST" action="{{ route('admin.gangs.update', $gang) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 text-sm az-gold">Nombre</label>
                        <input type="text" name="name" value="{{ old('name', $gang->name) }}" class="az-input" required>
                        @error('name') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Lider</label>
                        <input type="text" name="boss_name" value="{{ old('boss_name', $gang->boss_name) }}" class="az-input">
                        @error('boss_name') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Contacto Discord</label>
                        <input type="text" name="contact_discord" value="{{ old('contact_discord', $gang->contact_discord) }}" class="az-input">
                        @error('contact_discord') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Status</label>
                        <select name="status" class="az-input" required>
                            <option value="active" @selected(old('status', $gang->status) === 'active')>Activo</option>
                            <option value="inactive" @selected(old('status', $gang->status) === 'inactive')>Inactivo</option>
                            <option value="suspended" @selected(old('status', $gang->status) === 'suspended')>Suspendido</option>
                        </select>
                        @error('status') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm az-gold">Descripcion</label>
                    <textarea name="description" rows="5" class="az-input">{{ old('description', $gang->description) }}</textarea>
                    @error('description') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="az-btn az-btn-primary">
                        Actualizar
                    </button>

                    <a href="{{ route('admin.gangs.index') }}" class="az-btn az-btn-secondary">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
