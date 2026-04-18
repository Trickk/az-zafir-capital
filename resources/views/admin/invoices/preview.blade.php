<x-layouts.app :title="__('Vista previa factura')">
    <div class="az-dashboard-top">
        <div>
            <p class="az-eyebrow mb-2">Facturación</p>
            <h1 class="az-title text-3xl font-semibold">Vista previa de factura</h1>
            <p class="az-muted mt-2">{{ $invoice->invoice_number }}</p>
        </div>

        <div class="az-dashboard-actions">
            <a href="{{ route('admin.invoices.pdf', $invoice) }}" class="az-dashboard-action">
                Descargar PDF
            </a>
        </div>
    </div>

    <div class="az-card p-8">
        <div style="
            max-width: 980px;
            margin: 0 auto;
            background: #ffffff;
            color: #111827;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0,0,0,.20);
            border: 1px solid rgba(212,175,55,.25);
        ">
            <div style="
                background: linear-gradient(135deg, #0b0b0f 0%, #16161d 55%, #1f1a12 100%);
                color: #fff;
                padding: 32px 36px;
            ">
                <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:24px;">
                    <div style="flex:1;">
                        <div style="font-size:12px;letter-spacing:.18em;text-transform:uppercase;color:rgba(255,255,255,.65);margin-bottom:10px;">
                            Corporate Invoice
                        </div>

                        <div style="font-size:30px;font-weight:800;line-height:1.1;margin-bottom:8px;">
                            {{ $invoice->company_name_snapshot }}
                        </div>

                        @if($invoice->company_legal_name_snapshot)
                            <div style="font-size:14px;color:rgba(255,255,255,.78);">
                                {{ $invoice->company_legal_name_snapshot }}
                            </div>
                        @endif
                    </div>

                    <div style="text-align:right; min-width:260px;">
                        @if($invoice->company_invoice_image_path_snapshot)
                            <img
                                src="{{ asset('storage/' . $invoice->company_invoice_image_path_snapshot) }}"
                                alt="Invoice image"
                                style="width:100%;max-width:240px;max-height:100px;object-fit:contain;display:block;margin-left:auto;margin-bottom:14px;"
                            >
                        @elseif($invoice->company_logo_path_snapshot)
                            <img
                                src="{{ asset('storage/' . $invoice->company_logo_path_snapshot) }}"
                                alt="Company logo"
                                style="max-width:180px;max-height:80px;object-fit:contain;display:block;margin-left:auto;margin-bottom:14px;"
                            >
                        @endif
                    </div>
                </div>
            </div>

            <div style="padding: 34px 36px 40px;">
                <div class="meta" style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:24px;margin-bottom:28px;">
                        <div class="meta-box" width="50%">
                            <div class="meta-label">
                                Fecha emisión: <span class="meta-value">{{ optional($invoice->issued_at)->format('d/m/Y') }}</span>
                            </div>
                        </div>

                        <div class="meta-box" width="50%">
                            <div class="meta-label">
                                Referencia <span class="meta-value">{{ $invoice->invoice_number }}</span>
                            </div>
                        </div>
                </div>

                <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:24px;margin-bottom:28px;">
                    <div style="background:#f8fafc;border:1px solid #e5e7eb;border-radius:14px;padding:20px;">
                        <div style="font-size:11px;text-transform:uppercase;letter-spacing:.12em;color:#6b7280;margin-bottom:10px;">
                            Empresa emisora
                        </div>

                        <div style="font-size:18px;font-weight:700;margin-bottom:8px;">
                            {{ $invoice->company_name_snapshot }}
                        </div>

                        @if($invoice->company_tax_id_snapshot)
                            <div style="font-size:14px;color:#4b5563;margin-bottom:4px;">
                                Tax ID: {{ $invoice->company_tax_id_snapshot }}
                            </div>
                        @endif

                        @if($invoice->company_responsible_name_snapshot)
                            <div style="font-size:14px;color:#4b5563;margin-bottom:4px;">
                                Responsable: {{ $invoice->company_responsible_name_snapshot }}
                            </div>
                        @endif
                    </div>

                    <div style="background:#fffaf0;border:1px solid rgba(212,175,55,.35);border-radius:14px;padding:20px;">
                        <div style="font-size:11px;text-transform:uppercase;letter-spacing:.12em;color:#8a6b1f;margin-bottom:10px;">
                            Destinatario
                        </div>

                        <div style="font-size:18px;font-weight:700;margin-bottom:8px;">
                            {{ $invoice->invoice_customer_name ?: 'No indicado' }}
                        </div>

                        <div style="font-size:14px;color:#4b5563;margin-bottom:4px;">
                            State ID: {{ $invoice->invoice_state_id ?: 'No indicado' }}
                        </div>
                    </div>
                </div>

                <div style="border:1px solid #e5e7eb;border-radius:14px;overflow:hidden;margin-bottom:28px;">
                    <div style="background:#f9fafb;padding:14px 18px;border-bottom:1px solid #e5e7eb;font-size:11px;text-transform:uppercase;letter-spacing:.12em;color:#6b7280;">
                        Descripción de la factura
                    </div>

                    <div style="padding:22px 18px;">
                        <div style="font-size:22px;font-weight:700;margin-bottom:12px;">
                            {{ $invoice->concept }}
                        </div>

                        @if($invoice->description)
                            <div style="font-size:15px;line-height:1.7;color:#374151;">
                                {!! nl2br(e($invoice->description)) !!}
                            </div>
                        @else
                            <div style="font-size:15px;line-height:1.7;color:#6b7280;">
                                Sin descripción adicional.
                            </div>
                        @endif
                    </div>
                </div>

                <div style="display:flex;justify-content:flex-end;">
                    <div style="min-width:320px;background:linear-gradient(135deg, #111827 0%, #1f2937 100%);color:#fff;border-radius:16px;padding:22px 24px;">
                        <div style="font-size:11px;text-transform:uppercase;letter-spacing:.14em;color:rgba(255,255,255,.68);margin-bottom:10px;">
                            Importe total
                        </div>

                        <div style="font-size:34px;font-weight:800;line-height:1;color:#f4d27a;">
                            {{ number_format((float) $invoice->amount, 2, ',', '.') }} $
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
