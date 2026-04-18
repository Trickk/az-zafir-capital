<x-layouts.app :title="__('Crear banda')">
    <div class="az-dashboard-top">
        <div>
            <p class="az-eyebrow mb-2">Organizaciones internas</p>
            <h1 class="az-title text-3xl font-semibold">Crear banda</h1>
            <p class="az-muted mt-2">Registrar una nueva banda y configurar su empresa asociada y porcentajes de reparto.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="az-card p-4 mb-6"><ul class="az-muted">@foreach ($errors->all() as $error)<li>• { $error }</li>@endforeach</ul></div>
    @endif

    <div class="az-card p-6">
        <form action="{{ route('admin.gangs.store') }}" method="POST">
            @csrf

            <div class="grid md:grid-cols-2 gap-6">
                <div><label class="block text-sm mb-2">Nombre</label><input type="text" name="name" value="{{ old('name') }}" class="az-input" required></div>
                <div>
                    <label class="block text-sm mb-2">Empresa asociada</label>
                    <select name="company_id" class="az-input">
                        <option value="">Seleccionar empresa</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" @selected(old('company_id') == $company->id)>{{ $company->name }} ({{ $company->type }})</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-sm mb-2">Líder</label><input type="text" name="boss_name" value="{{ old('boss_name') }}" class="az-input"></div>
                <div><label class="block text-sm mb-2">Contacto Discord</label><input type="text" name="contact_discord" value="{{ old('contact_discord') }}" class="az-input"></div>
                <div><label class="block text-sm mb-2">Porcentaje gestor (%)</label><input type="number" step="0.01" min="0" max="100" name="commission_percent" value="{{ old('commission_percent', 10) }}" class="az-input"></div>
                <div><label class="block text-sm mb-2">Porcentaje Matrix Fund (%)</label><input type="number" step="0.01" min="0" max="100" name="matrix_percent" value="{{ old('matrix_percent', 10) }}" class="az-input"></div>
                <div>
                    <label class="block text-sm mb-2">Estado</label>
                    <select name="status" class="az-input">
                        <option value="active" @selected(old('status','active') === 'active')>Activa</option>
                        <option value="inactive" @selected(old('status','active') === 'inactive')>Inactiva</option>
                        <option value="suspended" @selected(old('status','active') === 'suspended')>Suspendida</option>
                    </select>
                </div>
            </div>

            <div class="az-card p-4 mt-6"><p class="az-muted text-sm mb-0">El porcentaje restante quedará como saldo operativo para facturación.</p></div>
            <div class="mt-6"><label class="block text-sm mb-2">Descripción</label><textarea name="description" rows="5" class="az-input">{{ old('description') }}</textarea></div>
            <div class="mt-6 az-table-actions">
                <button type="submit" class="az-btn az-btn-primary">Guardar banda</button>
                <a href="{ route('admin.gangs.index') }" class="az-btn az-btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</x-layouts.app>
