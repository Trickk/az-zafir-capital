<x-layouts.app :title="__('Facturas')">

    <div class="space-y-6">

        @if (session('success'))
            <div class="rounded-2xl border border-[rgba(212,175,55,0.20)] bg-[rgba(212,175,55,0.06)] px-5 py-4 text-[var(--az-gold-light)]">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <p class="az-eyebrow mb-2">Facturación</p>
                <h2 class="az-title text-3xl font-semibold">Facturas</h2>
                <p class="mt-2 az-muted">
                    Facturas congeladas con snapshot completo de banda y empresa.
                </p>
            </div>

            <a href="{{ route('admin.invoices.create') }}" class="az-btn az-btn-primary">
                Crear factura
            </a>
        </div>

        <div class="az-table-container">
            <table class="az-table">
                <thead>
                    <tr>
                        <th>Factura</th>
                        <th>Banda</th>
                        <th>Empresa</th>
                        <th>Importe</th>
                        <th>Comision</th>
                        <th>Banda</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($invoices as $invoice)
                        <tr>
                            <td>
                                <div class="az-table-primary">{{ $invoice->invoice_number }}</div>
                                <div class="az-table-sub">{{ $invoice->concept }}</div>
                            </td>

                            <td>{{ $invoice->gang_name_snapshot }}</td>
                            <td>{{ $invoice->company_name_snapshot }}</td>
                            <td>{{ number_format((float) $invoice->gross_amount, 2, ',', '.') }} $</td>
                            <td>{{ number_format((float) $invoice->net_amount, 2, ',', '.') }} $</td>
                            <td>{{ number_format((float) $invoice->commission_amount, 2, ',', '.') }} $</td>

                            <td>
                                <span class="az-status">
                                    @if($invoice->status === 'draft')
                                        Borrador
                                    @elseif($invoice->status === 'pending')
                                        Pendiente
                                    @elseif($invoice->status === 'approved')
                                        Aprobada
                                    @elseif($invoice->status === 'rejected')
                                        Rechazada
                                    @elseif($invoice->status === 'paid')
                                        Pagada
                                    @else
                                        Cancelada
                                    @endif
                                </span>
                            </td>

                            <td>
                                <div class="az-table-actions">
                                    <a href="{{ route('admin.invoices.preview', $invoice) }}" class="az-btn az-btn-secondary az-btn-sm">
                                        Vista previa
                                    </a>
                                    @if($invoice->pdf_path)
                                        <a href="{{ asset('storage/' . $invoice->pdf_path) }}" target="_blank" class="az-btn az-btn-secondary az-btn-sm">
                                            Ver PDF
                                        </a>
                                    @endif
                                    <a href="{{ route('admin.invoices.edit', $invoice) }}" class="az-btn az-btn-secondary az-btn-sm">
                                        Editar
                                    </a>

                                    <form method="POST" action="{{ route('admin.invoices.destroy', $invoice) }}" onsubmit="return confirm('¿Eliminar esta factura?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="az-btn az-btn-secondary az-btn-sm">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="az-muted">
                                No hay facturas registradas todavía.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div>
            {{ $invoices->links() }}
        </div>
    </div>

</x-layouts.app>
