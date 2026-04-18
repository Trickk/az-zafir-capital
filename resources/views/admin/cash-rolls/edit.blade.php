<x-layouts.app :title="__('Editar rulo')">

    <div class="space-y-6">
        <div>
            <p class="az-eyebrow mb-2">Entrada de capital</p>
            <h2 class="az-title text-3xl font-semibold">Editar rulo de dinero</h2>
            <p class="mt-2 az-muted">
                Modificar una entrega registrada y recalcular balances si es necesario.
            </p>
        </div>

        <div class="az-card p-6">
            <form method="POST" action="{{ route('admin.cash-rolls.update', $cashRoll) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid md:grid-cols-2 gap-6">
                    <div>
                        <label class="block mb-2 text-sm az-gold">Banda</label>
                        <select name="gang_id" class="az-input">
                            <option value="">Seleccionar banda</option>
                            @foreach($gangs as $gang)
                                <option value="{{ $gang->id }}" @selected(old('gang_id', $cashRoll->gang_id) == $gang->id)>
                                    {{ $gang->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('gang_id') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Holding</label>
                        <select name="holding_id" class="az-input" required>
                            <option value="">Seleccionar holding</option>
                            @foreach($holdings as $holding)
                                <option value="{{ $holding->id }}" @selected(old('holding_id', $cashRoll->holding_id) == $holding->id)>
                                    {{ $holding->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('holding_id') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Importe</label>
                        <input type="number" step="0.01" name="amount" value="{{ old('amount', $cashRoll->amount) }}" class="az-input" required>
                        @error('amount') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Número de rulos</label>
                        <input type="number" name="roll_count" value="{{ old('roll_count', $cashRoll->roll_count) }}" class="az-input" required>
                        @error('roll_count') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Estado</label>
                        <select name="status" class="az-input" required>
                            <option value="pending" @selected(old('status', $cashRoll->status) === 'pending')>Pendiente</option>
                            <option value="received" @selected(old('status', $cashRoll->status) === 'received')>Recibido</option>
                            <option value="verified" @selected(old('status', $cashRoll->status) === 'verified')>Verificado</option>
                            <option value="cancelled" @selected(old('status', $cashRoll->status) === 'cancelled')>Cancelado</option>
                        </select>
                        @error('status') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Entregado por</label>
                        <input type="text" name="delivered_by" value="{{ old('delivered_by', $cashRoll->delivered_by) }}" class="az-input">
                        @error('delivered_by') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Recibido por</label>
                        <input type="text" name="received_by" value="{{ old('received_by', $cashRoll->received_by) }}" class="az-input">
                        @error('received_by') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Fecha de entrega</label>
                        <input type="datetime-local" name="delivered_at" value="{{ old('delivered_at', optional($cashRoll->delivered_at)->format('Y-m-d\TH:i')) }}" class="az-input">
                        @error('delivered_at') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm az-gold">Fecha de recepción</label>
                        <input type="datetime-local" name="received_at" value="{{ old('received_at', optional($cashRoll->received_at)->format('Y-m-d\TH:i')) }}" class="az-input">
                        @error('received_at') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm az-gold">Notas</label>
                    <textarea name="notes" rows="5" class="az-input">{{ old('notes', $cashRoll->notes) }}</textarea>
                    @error('notes') <div class="mt-2 text-sm text-red-400">{{ $message }}</div> @enderror
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="az-btn az-btn-primary">
                        Actualizar
                    </button>

                    <a href="{{ route('admin.cash-rolls.index') }}" class="az-btn az-btn-secondary">
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
