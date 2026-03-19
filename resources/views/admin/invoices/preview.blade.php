<x-layouts.app :title="'Vista previa factura'">

    <div class="space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <p class="az-eyebrow mb-2">Facturación</p>
                <h2 class="az-title text-3xl font-semibold">Vista previa de factura</h2>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('admin.invoices.pdf', $invoice) }}" class="az-btn az-btn-secondary">
                    Descargar PDF
                </a>

                <form method="POST" action="{{ route('admin.invoices.generate-pdf', $invoice) }}">
                    @csrf
                    <button type="submit" class="az-btn az-btn-primary">
                        Guardar PDF
                    </button>
                </form>
            </div>
        </div>

        <div class="az-card p-6">
            @include('admin.invoices.partials.invoice-template', ['invoice' => $invoice])
        </div>
    </div>

</x-layouts.app>
