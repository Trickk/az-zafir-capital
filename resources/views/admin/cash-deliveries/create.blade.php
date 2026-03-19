<x-layouts.app :title="__('Registrar entrega')">

    <div class="space-y-6">
        <div>
            <p class="az-eyebrow mb-2">Operativa interna</p>
            <h2 class="az-title text-3xl font-semibold">Registrar entrega de dinero</h2>
            <p class="mt-2 az-muted">
                Registrar una entrega de dinero asociada a una banda y su empresa vinculada.
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

            <form method="POST" action="{{ route('admin.cash-deliveries.store') }}" class="space-y-6">
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
                        <label class="block mb-2 text-sm az-gold">Importe</label>
                        <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" class="az-input" required>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Estado</label>
                        <select name="status" class="az-input" required>
                            <option value="pending" @selected(old('status') === 'pending')>Pendiente</option>
                            <option value="received" @selected(old('status', 'received') === 'received')>Recibida</option>
                            <option value="verified" @selected(old('status') === 'verified')>Verificada</option>
                            <option value="cancelled" @selected(old('status') === 'cancelled')>Cancelada</option>
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Entregado por</label>
                        <input type="text" name="delivered_by" value="{{ old('delivered_by') }}" class="az-input">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Recibido por</label>
                        <input type="text" name="received_by" value="{{ old('received_by') }}" class="az-input">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Fecha de entrega</label>
                        <input type="datetime-local" name="delivered_at" value="{{ old('delivered_at') }}" class="az-input">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Fecha de recepción</label>
                        <input type="datetime-local" name="received_at" value="{{ old('received_at') }}" class="az-input">
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm az-gold">Notas</label>
                    <textarea name="notes" rows="5" class="az-input">{{ old('notes') }}</textarea>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="az-btn az-btn-primary">
                        Guardar entrega
                    </button>

                    <a href="{{ route('admin.cash-deliveries.index') }}" class="az-btn az-btn-secondary">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
