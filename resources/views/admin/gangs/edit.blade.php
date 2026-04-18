<x-layouts.app :title="__('Editar banda')">
    <div class="az-dashboard-top">
        <div>
            <p class="az-eyebrow mb-2">Organizaciones internas</p>
            <h1 class="az-title text-3xl font-semibold">Editar banda</h1>
            <p class="az-muted mt-2">Modificar empresa asociada, porcentajes de reparto y datos internos de la banda.</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="az-card p-4 mb-6"><ul class="az-muted">@foreach ($errors->all() as $error)<li>• { $error }</li>@endforeach</ul></div>
    @endif

    <div class="az-card p-6">
        <form action="{{ route('admin.gangs.update', $gang) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid md:grid-cols-2 gap-6">
                <div><label class="block text-sm mb-2">Nombre</label><input type="text" name="name" value="{{ old('name', $gang->name) }}" class="az-input" required></div>
                <div>
                    <label class="block text-sm mb-2">Empresa asociada</label>
                    <select name="company_id" class="az-input">
                        <option value="">Seleccionar empresa</option>
                        @foreach($companies as $company)
                            <option value="{{ $company->id }}" @selected(old('company_id') == $company->id)>{{ $company->name }} ({{ $company->type }})</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-sm mb-2">Líder</label><input type="text" name="boss_name" value="{{ old('boss_name', $gang->boss_name) }}" class="az-input"></div>
                <div><label class="block text-sm mb-2">Contacto Discord</label><input type="text" name="contact_discord" value="{{ old('contact_discord', $gang->contact_discord) }}" class="az-input"></div>
                <div><label class="block text-sm mb-2">Porcentaje gestor (%)</label><input type="number" step="0.01" min="0" max="100" name="commission_percent" value="{{ old('commission_percent', $gang->commission_percent) }}" class="az-input"></div>
                <div><label class="block text-sm mb-2">Porcentaje Matrix Fund (%)</label><input type="number" step="0.01" min="0" max="100" name="matrix_percent" value="{{ old('matrix_percent', $gang->matrix_percent ?? 10) }}" class="az-input"></div>
                <div>
                    <label class="block text-sm mb-2">Estado</label>
                    <select name="status" class="az-input">
                        <option value="active" @selected(old('status', $gang->status) === 'active')>Activa</option>
                        <option value="inactive" @selected(old('status', $gang->status) === 'inactive')>Inactiva</option>
                        <option value="suspended" @selected(old('status', $gang->status) === 'suspended')>Suspendida</option>
                    </select>
                </div>
            </div>
            <div class="grid md:grid-cols-2 gap-6 mt-6">
                <div class="az-card p-4"><p class="az-eyebrow mb-2">Saldo operativo</p><p class="az-title text-xl">{{ number_format((float) $gang->operating_balance, 2, ',', '.') }} $</p></div>
                <div class="az-card p-4"><p class="az-eyebrow mb-2">Matrix Fund</p><p class="az-title text-xl">{{ number_format((float) ($gang->matrixFund?->balance ?? 0), 2, ',', '.') }} $</p></div>
            </div>
            <div class="az-card p-4 mt-6"><p class="az-muted text-sm mb-0">El porcentaje restante quedará como saldo operativo para facturación.</p></div>
            <div class="mt-6"><label class="block text-sm mb-2">Descripción</label><textarea name="description" rows="5" class="az-input">{{ old('description', $gang->description) }}</textarea></div>
            <div class="mt-6 az-table-actions">
                <button type="submit" class="az-btn az-btn-primary">Guardar cambios</button>
                <a href="{ route('admin.gangs.index') }" class="az-btn az-btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</x-layouts.app>
