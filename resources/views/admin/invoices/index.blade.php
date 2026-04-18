<x-layouts.app :title="__('Facturas')">
    <div class="az-dashboard-top">
        <div>
            <p class="az-eyebrow mb-2">Facturación</p>
            <h1 class="az-title text-3xl font-semibold">Facturas</h1>
            <p class="az-muted mt-2">Facturas emitidas con snapshot de banda y empresa.</p>
        </div>
        <div class="az-dashboard-actions">
            <a href="{{ route('admin.invoices.create') }}" class="az-dashboard-action">Crear factura</a>
        </div>
    </div>

    @if (session('success'))
        <div class="az-card p-4 mb-6"><span class="az-status">{{ session('success') }}</span></div>
    @endif

    <div class="az-card p-6">
        <div class="az-table-container">
            <table class="az-table">
                <thead>
                    <tr>
                        <th>Factura</th>
                        <th>Banda</th>
                        <th>Empresa</th>
                        <th>Importe</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                        <tr>
                            <td><div class="az-table-primary">{{ $invoice->invoice_number }}</div><div class="az-table-sub">{{ $invoice->concept }}</div></td>
                            <td>{{ $invoice->gang_name_snapshot }}</td>
                            <td>{{ $invoice->company_name_snapshot }}</td>
                            <td>{{ number_format((float) $invoice->amount, 2, ',', '.') }} $</td>
                            <td><span class="az-status">{{ ucfirst($invoice->status) }}</span></td>
                            <td>
                                <div class="az-table-actions">
                                    <a href="{{ route('admin.invoices.preview', $invoice) }}" class="az-btn az-btn-secondary az-btn-sm">Ver</a>
                                    <a href="{{ route('admin.invoices.pdf', $invoice) }}" class="az-btn az-btn-secondary az-btn-sm">PDF</a>
                                    @if($invoice->image_path)
                                        <a href="{{ route('admin.invoices.image.show', $invoice) }}" target="_blank" class="az-btn az-btn-secondary az-btn-sm">
                                            PNG
                                        </a>

                                        <button
                                            type="button"
                                            class="az-btn az-btn-secondary az-btn-sm"
                                            onclick="navigator.clipboard.writeText('{{ asset('storage/' . $invoice->image_path) }}')"
                                        >
                                            Copiar URL
                                        </button>
                                    @endif
                                    <a href="{{ route('admin.invoices.edit', $invoice) }}" class="az-btn az-btn-secondary az-btn-sm">Editar</a>
                                    <form action="{{ route('admin.invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('¿Eliminar esta factura?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="az-btn az-btn-secondary az-btn-sm">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="az-muted">No hay facturas registradas todavía.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-6">{{ $invoices->links() }}</div>
    </div>
</x-layouts.app>
