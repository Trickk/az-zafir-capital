<x-layouts.app :title="__('Crear empresa')">
    <div class="az-dashboard-top">
        <div>
            <p class="az-eyebrow mb-2">Holding</p>
            <h1 class="az-title text-3xl font-semibold">Crear empresa</h1>
            <p class="az-muted mt-2">Registrar una nueva empresa dentro del holding.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="az-card p-4 mb-6"><ul class="az-muted">@foreach ($errors->all() as $error)<li>• { $error }</li>@endforeach</ul></div>
    @endif

    <div class="az-card p-6">
        <form action="{{ route('admin.companies.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="grid md:grid-cols-2 gap-6">
                <div><label class="block text-sm mb-2">Nombre</label><input type="text" name="name" value="{{ old('name') }}" class="az-input" required></div>
                <div><label class="block text-sm mb-2">Razón social</label><input type="text" name="legal_name" value="{{ old('legal_name') }}" class="az-input"></div>
                <div>
                    <label class="block text-sm mb-2">Tipo</label>
                    <select name="type" class="az-input" required>
                        @foreach(['cultural'=>'Cultural','logistics'=>'Logistics','hospitality'=>'Hospitality','investment'=>'Investment','entertainment'=>'Entertainment','security'=>'Security','technology'=>'Technology','trading'=>'Trading'] as $key => $label)
                            <option value="{ $key }" @selected(old('type') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-sm mb-2">Responsable</label><input type="text" name="responsible_name" value="{{ old('responsible_name') }}" class="az-input"></div>
                <div><label class="block text-sm mb-2">País</label><input type="text" name="country" value="{{ old('country') }}" class="az-input"></div>
                <div><label class="block text-sm mb-2">Ciudad</label><input type="text" name="city" value="{{ old('city') }}" class="az-input"></div>
                <div class="md:col-span-2"><label class="block text-sm mb-2">Dirección</label><input type="text" name="address" value="{{ old('address') }}" class="az-input"></div>
                <div><label class="block text-sm mb-2">Logo</label><input type="file" name="logo" class="az-input"></div>
                <div><label class="block text-sm mb-2">Imagen factura</label><input type="file" name="invoice_image" class="az-input"></div>
                <div>
                    <label class="block text-sm mb-2">Estado</label>
                    <select name="status" class="az-input" required>
                        <option value="active" @selected(old('status','active') === 'active')>Activa</option>
                        <option value="inactive" @selected(old('status','active') === 'inactive')>Inactiva</option>
                    </select>
                </div>
            </div>
            <div class="mt-6"><label class="block text-sm mb-2">Descripción</label><textarea name="description" rows="5" class="az-input">{{ old('description') }}</textarea></div>
            <div class="mt-6 az-table-actions">
                <button type="submit" class="az-btn az-btn-primary">Guardar empresa</button>
                <a href="{ route('admin.companies.index') }" class="az-btn az-btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</x-layouts.app>
