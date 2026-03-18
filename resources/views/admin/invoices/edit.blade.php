<x-layouts.app :title="__('Editar factura')">

    <div class="space-y-6">
        <div>
            <p class="az-eyebrow mb-2">Facturación</p>
            <h2 class="az-title text-3xl font-semibold">Editar factura</h2>
            <p class="mt-2 az-muted">
                Modificar una factura y recalcular automáticamente su liquidación.
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

            <form method="POST" action="{{ route('admin.invoices.update', $invoice) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 text-sm az-gold">Banda</label>
                        <select name="gang_id" class="az-input" required>
                            <option value="">Seleccionar banda</option>
                            @foreach($gangs as $gang)
                                <option value="{{ $gang->id }}" @selected(old('gang_id', $invoice->gang_id) == $gang->id)>
                                    {{ $gang->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Empresa</label>
                        <select name="company_id" class="az-input" required>
                            <option value="">Seleccionar empresa</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" @selected(old('company_id', $invoice->company_id) == $company->id)>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Concepto</label>
                        <input type="text" name="concept" value="{{ old('concept', $invoice->concept) }}" class="az-input" required>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Importe bruto</label>
                        <input type="number" step="0.01" name="gross_amount" value="{{ old('gross_amount', $invoice->gross_amount) }}" class="az-input" required>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Fecha de emisión</label>
                        <input type="date" name="issued_at" value="{{ old('issued_at', optional($invoice->issued_at)->format('Y-m-d')) }}" class="az-input" required>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Fecha de vencimiento</label>
                        <input type="date" name="due_at" value="{{ old('due_at', optional($invoice->due_at)->format('Y-m-d')) }}" class="az-input">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Estado</label>
                        <select name="status" class="az-input" required>
                            <option value="draft" @selected(old('status', $invoice->status) === 'draft')>Borrador</option>
                            <option value="pending" @selected(old('status', $invoice->status) === 'pending')>Pendiente</option>
                            <option value="reviewed" @selected(old('status', $invoice->status) === 'reviewed')>Revisada</option>
                            <option value="approved" @selected(old('status', $invoice->status) === 'approved')>Aprobada</option>
                            <option value="rejected" @selected(old('status', $invoice->status) === 'rejected')>Rechazada</option>
                            <option value="paid" @selected(old('status', $invoice->status) === 'paid')>Pagada</option>
                            <option value="cancelled" @selected(old('status', $invoice->status) === 'cancelled')>Cancelada</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm az-gold">Descripción</label>
                    <textarea name="description" rows="4" class="az-input">{{ old('description', $invoice->description) }}</textarea>
                </div>

                <div>
                    <label class="block mb-2 text-sm az-gold">Notas</label>
                    <textarea name="notes" rows="4" class="az-input">{{ old('notes', $invoice->notes) }}</textarea>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="az-btn az-btn-primary">
                        Actualizar factura
                    </button>

                    <a href="{{ route('admin.invoices.index') }}" class="az-btn az-btn-secondary">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
