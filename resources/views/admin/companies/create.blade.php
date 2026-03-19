<x-layouts.app :title="__('Crear empresa')">

    <div class="space-y-6">
        <div>
            <p class="az-eyebrow mb-2">Entidades públicas</p>
            <h2 class="az-title text-3xl font-semibold">Crear empresa</h2>
            <p class="mt-2 az-muted">
                Registrar una nueva empresa para web pública y facturación.
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

            <form method="POST" action="{{ route('admin.companies.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 text-sm az-gold">Nombre</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="az-input" required>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Nombre legal</label>
                        <input type="text" name="legal_name" value="{{ old('legal_name') }}" class="az-input">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm az-gold">Responsable</label>
                        <input
                            type="text"
                            name="responsible_name"
                            value="{{ old('responsible_name') }}"
                            class="az-input"
                            placeholder="Nombre del responsable"
                        >
                    </div>
                    <div>
                        <label class="block mb-2 text-sm az-gold">Tipo</label>
                        <select name="type" class="az-input" required>
                            <option value="cultural">Cultural</option>
                            <option value="logistics">Logistics</option>
                            <option value="hospitality">Hospitality</option>
                            <option value="investment">Investment</option>
                            <option value="entertainment">Entertainment</option>
                            <option value="security">Security</option>
                            <option value="technology">Technology</option>
                            <option value="trading">Trading</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">País</label>
                        <input type="text" name="country" value="{{ old('country', config('app.default_company_country')) }}" class="az-input">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Ciudad</label>
                        <input type="text" name="city" value="{{ old('city', config('app.default_company_city')) }}" class="az-input">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block mb-2 text-sm az-gold">Dirección</label>
                        <input type="text" name="address" value="{{ old('address', config('app.default_company_address')) }}" class="az-input">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Logo</label>
                        <input type="file" name="logo" class="az-input" accept=".jpg,.jpeg,.png,.webp">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Imagen para factura</label>
                        <input type="file" name="invoice_image" class="az-input" accept=".jpg,.jpeg,.png,.webp">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Estado</label>
                        <select name="status" class="az-input" required>
                            <option value="active">Activa</option>
                            <option value="inactive">Inactiva</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm az-gold">Descripción</label>
                    <textarea name="description" rows="5" class="az-input">{{ old('description') }}</textarea>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="az-btn az-btn-primary">
                        Guardar empresa
                    </button>

                    <a href="{{ route('admin.companies.index') }}" class="az-btn az-btn-secondary">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
