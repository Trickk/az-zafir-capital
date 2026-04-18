<x-layouts.app :title="__('Dashboard')">
    <div class="az-dashboard-top">
        <div class="az-dashboard-actions">
             <flux:tooltip content="Nueva factura" position="top">
                <a href="{{ route('admin.invoices.create') }}" class="az-dashboard-action">
                    <i class="fa-solid fa-file-invoice-dollar fa-2xl"></i>
                </a>
            </flux:tooltip>

            <flux:tooltip content="Nueva entrega" position="top">
                <a href="{{ route('admin.cash-deliveries.create') }}" class="az-dashboard-action">
                    <i class="fa-solid fa-handshake fa-2xl"></i>
                </a>
            </flux:tooltip>

            <flux:tooltip content="Nueva empresa" position="top">
                <a href="{{ route('admin.companies.create') }}" class="az-dashboard-action">
                    <i class="fa-solid fa-building fa-2xl"></i>
                </a>
            </flux:tooltip>

            <flux:tooltip content="Nueva banda" position="top">
                <a href="{{ route('admin.gangs.create') }}" class="az-dashboard-action">
                    <i class="fa-solid fa-users fa-2xl"></i>
                </a>
            </flux:tooltip>
        </div>

        <div>
            <p class="az-eyebrow mb-2">Centro de control</p>
            <h1 class="az-title text-3xl font-semibold">Dashboard</h1>
            <p class="az-muted mt-2">Resumen financiero y operativo del holding.</p>
        </div>
    </div>

    <div class="az-card p-6 mb-6">
        <div class="az-finance-row">
            <div class="az-finance-item">
                <div class="az-finance-label">Bruto entregado</div>
                <div class="az-finance-value">{{ number_format($grossDelivered, 2, ',', '.') }} $</div>
            </div>
            <div class="az-finance-item">
                <div class="az-finance-label">Asignado a Matrix</div>
                <div class="az-finance-value">{{ number_format($totalMatrixAssigned, 2, ',', '.') }} $</div>
            </div>
            <div class="az-finance-item">
                <div class="az-finance-label">Asignado a gestor</div>
                <div class="az-finance-value">{{ number_format($totalManagementAssigned, 2, ',', '.') }} $</div>
            </div>
            <div class="az-finance-item">
                <div class="az-finance-label">Saldo operativo asignado</div>
                <div class="az-finance-value">{{ number_format($totalOperatingAssigned, 2, ',', '.') }} $</div>
            </div>
        </div>
    </div>

    <div class="grid xl:grid-cols-2 gap-6">
        <div class="az-card p-6">
            <p class="az-eyebrow mb-4">Últimas entregas</p>
            <div class="az-table-container">
                <table class="az-table">
                    <thead>
                        <tr>
                            <th>Ref.</th>
                            <th>Banda</th>
                            <th>Importe</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentDeliveries as $delivery)
                            <tr>
                                <td>
                                    <div class="az-table-primary">{{ $delivery->delivery_number }}</div>
                                    <div class="az-table-sub">{{ $delivery->created_at?->format('d/m/Y H:i') }}</div>
                                </td>
                                <td>{{ $delivery->gang?->name ?? '—' }}</td>
                                <td>{{ number_format((float) $delivery->amount, 2, ',', '.') }} $</td>
                                <td><span class="az-status">{{ ucfirst($delivery->status) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="az-muted">Sin movimientos recientes.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="az-card p-6">
            <p class="az-eyebrow mb-4">Últimas facturas</p>
            <div class="az-table-container">
                <table class="az-table">
                    <thead>
                        <tr>
                            <th>Factura</th>
                            <th>Banda</th>
                            <th>Importe</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentInvoices as $invoice)
                            <tr>
                                <td>
                                    <div class="az-table-primary">{{ $invoice->invoice_number }}</div>
                                    <div class="az-table-sub">{{ $invoice->concept }}</div>
                                </td>
                                <td>{{ $invoice->gang_name_snapshot }}</td>
                                <td>{{ number_format((float) $invoice->amount, 2, ',', '.') }} $</td>
                                <td><span class="az-status">{{ ucfirst($invoice->status) }}</span></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="az-muted">Sin facturas recientes.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layouts.app>
