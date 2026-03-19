<x-layouts.app :title="__('Crear factura')">

    <div class="space-y-6">
        <div>
            <p class="az-eyebrow mb-2">Facturación</p>
            <h2 class="az-title text-3xl font-semibold">Crear factura</h2>
            <p class="mt-2 az-muted">
                Crear una factura congelando los datos actuales de banda y empresa.
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

            <form method="POST" action="{{ route('admin.invoices.store') }}" class="space-y-6">
                @csrf

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 text-sm az-gold">Banda</label>
                        <select name="gang_id" class="az-input" required>
                            <option value="">Seleccionar banda</option>
                            @foreach($gangs as $gang)
                                <option value="{{ $gang->id }}" @selected(old('gang_id') == $gang->id)>
                                    {{ $gang->name }} @if($gang->company) — {{ $gang->company->name }} @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm az-gold">Cliente</label>
                        <input
                            type="text"
                            name="invoice_customer_name"
                            value="{{ old('invoice_customer_name') }}"
                            class="az-input"
                            placeholder="Nombre del cliente"
                        >
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">State ID</label>
                        <input
                            type="text"
                            name="invoice_state_id"
                            value="{{ old('invoice_state_id') }}"
                            class="az-input"
                        >
                    </div>
                    <div>
                        <label class="block mb-2 text-sm az-gold">Importe bruto total</label>
                        <input type="number" step="0.01" name="gross_amount" value="{{ old('gross_amount') }}" class="az-input" required>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block mb-2 text-sm az-gold">Concepto</label>
                        <input type="text" name="concept" value="{{ old('concept') }}" class="az-input" required>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Fecha de emisión</label>
                        <input type="date" name="issued_at" value="{{ old('issued_at', now()->format('Y-m-d')) }}" class="az-input" required>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Fecha de vencimiento</label>
                        <input type="date" name="due_at" value="{{ old('due_at') }}" class="az-input">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Estado</label>
                        <select name="status" class="az-input" required>
                            <option value="draft" @selected(old('status') === 'draft')>Borrador</option>
                            <option value="pending" @selected(old('status', 'pending') === 'pending')>Pendiente</option>
                            <option value="approved" @selected(old('status') === 'approved')>Aprobada</option>
                            <option value="rejected" @selected(old('status') === 'rejected')>Rechazada</option>
                            <option value="paid" @selected(old('status') === 'paid')>Pagada</option>
                            <option value="cancelled" @selected(old('status') === 'cancelled')>Cancelada</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm az-gold">Descripción</label>
                    <textarea name="description" rows="5" class="az-input">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label class="block mb-2 text-sm az-gold">Notas internas</label>
                    <textarea name="notes" rows="4" class="az-input">{{ old('notes') }}</textarea>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="az-btn az-btn-primary">
                        Guardar factura
                    </button>

                    <a href="{{ route('admin.invoices.index') }}" class="az-btn az-btn-secondary">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
