@php
    $isPdf = $isPdf ?? false;

    $logoSrc = null;
    if (!empty($invoice->company_logo_path_snapshot)) {
        $logoSrc = $isPdf
            ? public_path('storage/' . $invoice->company_logo_path_snapshot)
            : asset('storage/' . $invoice->company_logo_path_snapshot);
    }

    $invoiceImageSrc = null;
    if (!empty($invoice->company_invoice_image_path_snapshot)) {
        $invoiceImageSrc = $isPdf
            ? public_path('storage/' . $invoice->company_invoice_image_path_snapshot)
            : asset('storage/' . $invoice->company_invoice_image_path_snapshot);
    }
@endphp

<div style="background:#fff; color:#111; border-radius:18px; overflow:hidden; font-family:Arial, Helvetica, sans-serif;">

    <div style="padding:40px; border-bottom:1px solid #e5e5e5;">
        <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:30px;">

            <div style="display:flex; gap:20px; align-items:flex-start;">
                @if($logoSrc)
                    <img
                        src="{{ $logoSrc }}"
                        alt="{{ $invoice->company_name_snapshot }}"
                        style="max-height:90px; max-width:120px; object-fit:contain;"
                    >
                @endif

                <div>
                    <div style="font-size:30px; font-weight:700; line-height:1.1;">
                        {{ $invoice->company_name_snapshot }}
                    </div>

                    @if($invoice->company_legal_name_snapshot)
                        <div style="margin-top:8px; font-size:14px; color:#555;">
                            {{ $invoice->company_legal_name_snapshot }}
                        </div>
                    @endif

                    @if($invoice->company_tax_id_snapshot)
                        <div style="margin-top:8px; font-size:14px; color:#555;">
                            Tax ID: {{ $invoice->company_tax_id_snapshot }}
                        </div>
                    @endif

                    @if($invoice->company_address_snapshot)
                        <div style="margin-top:8px; font-size:14px; color:#555;">
                            {{ $invoice->company_address_snapshot }}
                            @if($invoice->company_city_snapshot)
                                , {{ $invoice->company_city_snapshot }}
                            @endif
                            @if($invoice->company_country_snapshot)
                                , {{ $invoice->company_country_snapshot }}
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div style="text-align:right;">
                <div style="font-size:32px; font-weight:700; color:#111;">
                    INVOICE
                </div>

                <div style="margin-top:12px; font-size:14px; color:#555;">
                    <strong>Invoice No:</strong> {{ $invoice->invoice_number }}
                </div>

                <div style="margin-top:6px; font-size:14px; color:#555;">
                    <strong>Issue Date:</strong> {{ optional($invoice->issued_at)->format('d/m/Y') }}
                </div>

                @if($invoice->due_at)
                    <div style="margin-top:6px; font-size:14px; color:#555;">
                        <strong>Due Date:</strong> {{ optional($invoice->due_at)->format('d/m/Y') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div style="padding:40px;">
        <div style="margin-bottom:28px;">
            @if($invoice->invoice_customer_name)
                <div style="margin-top:8px; font-size:14px; color:#555;">
                    <strong>Customer:</strong> {{ $invoice->invoice_customer_name }}
                </div>
            @endif

            @if($invoice->invoice_state_id)
                <div style="margin-top:8px; font-size:14px; color:#555;">
                    <strong>State ID:</strong> {{ $invoice->invoice_state_id }}
                </div>
            @endif
        </div>
        <div style="margin-bottom:28px;">
            <div style="font-size:13px; text-transform:uppercase; letter-spacing:.18em; color:#777;">
                Concept
            </div>
            <div style="margin-top:10px; font-size:24px; font-weight:700;">
                {{ $invoice->concept }}
            </div>
        </div>

        @if($invoice->description)
            <div style="margin-bottom:32px;">
                <div style="font-size:13px; text-transform:uppercase; letter-spacing:.18em; color:#777;">
                    Description
                </div>
                <div style="margin-top:10px; font-size:15px; line-height:1.7; color:#333;">
                    {!! nl2br(e($invoice->description)) !!}
                </div>
            </div>
        @endif

        <div style="margin-top:30px; border:1px solid #e5e5e5; border-radius:14px; overflow:hidden;">
            <table style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background:#f8f8f8;">
                        <th style="text-align:left; padding:16px; font-size:12px; text-transform:uppercase; letter-spacing:.14em; color:#666;">
                            Item
                        </th>
                        <th style="text-align:right; padding:16px; font-size:12px; text-transform:uppercase; letter-spacing:.14em; color:#666;">
                            Total
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding:18px 16px; border-top:1px solid #eee;">
                            {{ $invoice->concept }}
                        </td>
                        <td style="padding:18px 16px; border-top:1px solid #eee; text-align:right; font-weight:700;">
                            {{ number_format((float) $invoice->gross_amount, 2, ',', '.') }} $
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="display:flex; justify-content:flex-end; margin-top:28px;">
            <div style="width:320px; border:1px solid #e5e5e5; border-radius:14px; overflow:hidden;">
                <div style="display:flex; justify-content:space-between; padding:16px; background:#fafafa; font-size:15px;">
                    <span>Total invoice amount</span>
                    <strong>{{ number_format((float) $invoice->gross_amount, 2, ',', '.') }} $</strong>
                </div>
            </div>
        </div>

        <div style="margin-top:40px; font-size:12px; color:#777; line-height:1.7;">
            This invoice reflects the total billed amount for the services described above.
        </div>
    </div>
</div>
