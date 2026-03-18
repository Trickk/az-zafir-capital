<x-layouts.app :title="__('Panel principal')">
    <div class="az-dashboard-top">

        <div class="az-dashboard-actions">

            <a href="{{ route('admin.invoices.create') }}" class="az-dashboard-action">
                Crear factura
            </a>

            <a href="{{ route('admin.cash-rolls.create') }}" class="az-dashboard-action">
                Nueva entrega
            </a>

            <a href="{{ route('admin.holdings.create') }}" class="az-dashboard-action">
                Nuevo holding
            </a>

        </div>

        <div class="az-dashboard-status">

            <span class="az-badge az-badge-gold">
                Liquidaciones pendientes: {{ $pendingSettlements }}
            </span>

            <span class="az-badge az-badge-gold">
                Procesadas: {{ $processedSettlements }}
            </span>

            <span class="az-badge az-badge-gold">
                Liberadas: {{ $releasedSettlements }}
            </span>

        </div>

    </div>
    <div class="space-y-6">

        <div class="grid md:grid-cols-2 xl:grid-cols-4 gap-6">
            <div class="az-kpi-card">
                <div class="az-kpi-label">Bandas activas</div>
                <div class="az-kpi-value">{{ $activeGangs }}</div>
                <div class="mt-3 az-muted text-sm">Total registradas: {{ $totalGangs }}</div>
            </div>

            <div class="az-kpi-card">
                <div class="az-kpi-label">Holdings activos</div>
                <div class="az-kpi-value">{{ $activeHoldings }}</div>
                <div class="mt-3 az-muted text-sm">Total registrados: {{ $totalHoldings }}</div>
            </div>

            <div class="az-kpi-card">
                <div class="az-kpi-label">Facturas</div>
                <div class="az-kpi-value">{{ $totalInvoices }}</div>
                <div class="mt-3 az-muted text-sm">
                    Pendientes: {{ $pendingInvoices }} · Aprobadas: {{ $approvedInvoices }}
                </div>
            </div>

            <div class="az-kpi-card">
                <div class="az-kpi-label">Saldo sucio total</div>
                <div class="az-kpi-value">{{ number_format($totalDirtyBalance, 2, ',', '.') }} €</div>
                <div class="mt-3 az-muted text-sm">Capital pendiente de compensación</div>
            </div>
        </div>

        <div class="grid xl:grid-cols-1 gap-6">

            <div class="az-card p-6">
                <div class="flex items-end justify-between gap-6">
                    <div>
                        <p class="az-eyebrow mb-2">Resumen financiero</p>
                    </div>
                </div>

                <div class="az-finance-row">

                    <div class="az-finance-item">
                        <div class="az-finance-label">Dinero sucio recibido</div>
                        <div class="az-finance-value">
                            {{ number_format($totalDirtyReceived,2,',','.') }} €
                        </div>
                    </div>

                    <div class="az-finance-item">
                        <div class="az-finance-label">Dinero limpiado</div>
                        <div class="az-finance-value">
                            {{ number_format($totalCleaned,2,',','.') }} €
                        </div>
                    </div>

                    <div class="az-finance-item">
                        <div class="az-finance-label">Comisión Al-Zafir</div>
                        <div class="az-finance-value">
                            {{ number_format($totalCommission,2,',','.') }} €
                        </div>
                    </div>

                    <div class="az-finance-item">
                        <div class="az-finance-label">Saldo pendiente</div>
                        <div class="az-finance-value">
                            {{ number_format($totalDirtyBalance,2,',','.') }} €
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="grid xl:grid-cols-2 gap-6">

            <div class="az-card p-6">
                <p class="az-eyebrow mb-2">Últimas facturas</p>
                <h2 class="az-title text-3xl font-semibold">Actividad reciente</h2>

                <div class="mt-6 az-table-container">
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
                            @forelse($latestInvoices as $invoice)
                            <tr>
                                <td>
                                    <div class="az-table-primary">{{ $invoice->invoice_number }}</div>
                                    <div class="az-table-sub">{{ $invoice->concept }}</div>
                                </td>
                                <td>{{ $invoice->gang?->name ?? '—' }}</td>
                                <td>{{ number_format((float) $invoice->gross_amount, 2, ',', '.') }} €</td>
                                <td>
                                    <span class="az-status">{{ ucfirst($invoice->status) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="az-muted">Todavía no hay facturas registradas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="az-card p-6">
                <p class="az-eyebrow mb-2">Últimos rulos</p>
                <h2 class="az-title text-3xl font-semibold">Entradas de dinero</h2>

                <div class="mt-6 az-table-container">
                    <table class="az-table">
                        <thead>
                            <tr>
                                <th>Referencia</th>
                                <th>Banda</th>
                                <th>Importe</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($latestCashRolls as $roll)
                            <tr>
                                <td>
                                    <div class="az-table-primary">{{ $roll->delivery_number }}</div>
                                    <div class="az-table-sub">{{ $roll->holding?->name ?? '—' }}</div>
                                </td>
                                <td>{{ $roll->gang?->name ?? '—' }}</td>
                                <td>{{ number_format((float) $roll->amount, 2, ',', '.') }} €</td>
                                <td>
                                    <span class="az-status">{{ ucfirst($roll->status) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="az-muted">Todavía no hay rulos registrados.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </div>

</x-layouts.app>
