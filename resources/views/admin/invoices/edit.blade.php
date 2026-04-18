<x-layouts.app :title="__('Editar factura')">
    <div class="az-dashboard-top">
        <div>
            <p class="az-eyebrow mb-2">Facturación</p>
            <h1 class="az-title text-3xl font-semibold">Editar factura</h1>
            <p class="az-muted mt-2">Si cambian datos de empresa o banda, la recomendación es cancelar la factura y
                generar una nueva.</p>
        </div>
    </div>

    @if ($errors->any())
    <div class="az-card p-4 mb-6">
        <ul class="az-muted">@foreach ($errors->all() as $error)<li>• { $error }</li>@endforeach</ul>
    </div>
    @endif

    <div class="az-card p-6">
        <form action="{{ route('admin.invoices.update', $invoice) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm mb-2">Banda</label>
                    <select name="gang_id" class="az-input" required>
                        <option value="">Seleccionar banda</option>
                        @foreach($gangs as $gang)
                        <option value="{{ $gang->id }}" @selected(old('gang_id', $invoice->gang_id) == $gang->id)>{{ $gang->name }}
                            @if($gang->company) — {{ $gang->company->name }} - {{  number_format($gang->operating_balance, 0, ',', '.') }} @endif</option>
                        @endforeach
                    </select>
                </div>
                <div><label class="block text-sm mb-2">Importe</label><input type="number" step="0.01" min="0.01"
                        name="amount" value="{{ old('amount', $invoice->amount) }}" class="az-input" required></div>
                <div class="col-span-2"><label class="block text-sm mb-2">Concepto</label><input type="text" name="concept"
                        value="{{ old('concept', $invoice->concept) }}" class="az-input" required></div>
                <div>
                    <label class="block text-sm mb-2">Persona a la que va</label>
                    <input type="text" name="invoice_customer_name"
                        value="{{ old('invoice_customer_name', $invoice->invoice_customer_name) }}" class="az-input">
                </div>

                <div>
                    <label class="block text-sm mb-2">State ID</label>
                    <input type="text" name="invoice_state_id"
                        value="{{ old('invoice_state_id', $invoice->invoice_state_id) }}" class="az-input">
                </div>
                <div>
                    <label class="block text-sm mb-2">Estado</label>
                    <select name="status" class="az-input">
                        <option value="draft" @selected(old('status', $invoice->status) === 'draft')>Borrador</option>
                        <option value="issued" @selected(old('status', $invoice->status) === 'issued')>Emitida</option>
                        <option value="paid" @selected(old('status', $invoice->status) === 'paid')>Pagada</option>
                        <option value="cancelled" @selected(old('status', $invoice->status) === 'cancelled')>Cancelada
                        </option>
                    </select>
                </div>
                <div><label class="block text-sm mb-2">Fecha de emisión</label><input type="datetime-local"
                        name="issued_at"
                        value="{{ old('issued_at', optional($invoice->issued_at)->format('Y-m-d\TH:i')) }}"
                        class="az-input"></div>
            </div>
            <div class="mt-6"><label class="block text-sm mb-2">Descripción</label><textarea name="description" rows="5"
                    class="az-input">{{ old('description', $invoice->description) }}</textarea></div>
            <div class="az-card p-4 mt-6">
                <p class="az-muted text-sm mb-0">Si la factura se guarda como pagada, se descontará del saldo operativo
                    de la banda.</p>
            </div>
            <div class="mt-6 az-table-actions">
                <button type="submit" class="az-btn az-btn-primary">Guardar cambios</button>
                <a href="{ route('admin.invoices.index') }" class="az-btn az-btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</x-layouts.app>
