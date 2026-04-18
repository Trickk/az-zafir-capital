<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #111827;
            font-size: 12px;
            margin: 0;
            padding: 0;
            background: #ffffff;
        }

        .page {
            padding: 34px 36px 40px;
        }

        .header {
            background: #111111;
            color: #ffffff;
            padding: 26px 30px;
            margin-bottom: 24px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-left,
        .header-right {
            vertical-align: top;
        }

        .header-right {
            text-align: right;
            width: 260px;
        }

        .kicker {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .18em;
            color: #c9c9c9;
            margin-bottom: 8px;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .muted-light {
            color: #dddddd;
            font-size: 12px;
        }

        .invoice-number-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .16em;
            color: #d8d8d8;
            margin-top: 10px;
        }

        .invoice-number {
            font-size: 22px;
            font-weight: bold;
        }

        .box-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 16px 0;
            margin: 0 -16px 20px;
        }

        .box {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 16px;
            vertical-align: top;
        }

        .box-gold {
            border: 1px solid #d4af37;
            background: #fffaf0;
        }

        .label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .label-gold {
            color: #8a6b1f;
        }

        .value-lg {
            font-size: 17px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .value {
            font-size: 13px;
            color: #374151;
            margin-bottom: 4px;
        }

        .desc-box {
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 26px;
        }

        .desc-head {
            background: #f9fafb;
            padding: 12px 16px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: #6b7280;
        }

        .desc-body {
            padding: 18px 16px;
        }

        .concept {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .desc-text {
            font-size: 13px;
            line-height: 1.7;
            color: #374151;
        }

        .total-wrap {
            text-align: right;
        }

        .total-box {
            display: inline-block;
            min-width: 260px;
            background: #111827;
            color: #ffffff;
            border-radius: 12px;
            padding: 18px 20px;
        }

        .total-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .14em;
            color: #d4d4d8;
            margin-bottom: 8px;
        }

        .total-amount {
            font-size: 30px;
            font-weight: bold;
            color: #f4d27a;
        }

        .logo {
            max-width: 220px;
            max-height: 90px;
            object-fit: contain;
            margin-bottom: 12px;
        }

.meta {
    width: 100%;
    border-collapse: separate;
    border-spacing: 16px 0;
    margin: 0 0px 28px;
    font-size: 8px;
}

.meta-box {
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 18px;
    vertical-align: top;
}

.meta-label {
   text-transform: uppercase;
    letter-spacing: .12em;
    color: #6b7280;
    font-size: 11px;
    /* margin-top: 11px; */
    align-content: center;
    position: relative;
}

.meta-value {
    font-size: 12px;
    font-weight: 800;
    color: #000;
}
    </style>
</head>
<body>
    @php
        $invoiceImage = $invoice->company_invoice_image_path_snapshot
            ? public_path('storage/' . $invoice->company_invoice_image_path_snapshot)
            : null;

        $logoImage = $invoice->company_logo_path_snapshot
            ? public_path('storage/' . $invoice->company_logo_path_snapshot)
            : null;
    @endphp

    <div class="header">
        <table class="header-table">
            <tr>
                <td class="header-left">
                    <div class="kicker">Corporate Invoice</div>
                    <div class="company-name">{{ $invoice->company_name_snapshot }}</div>

                    @if($invoice->company_legal_name_snapshot)
                        <div class="muted-light">{{ $invoice->company_legal_name_snapshot }}</div>
                    @endif
                </td>
                <td class="header-right">
                    @if($invoiceImage && file_exists($invoiceImage))
                        <img src="{{ $invoiceImage }}" class="logo" alt="Invoice image">
                    @elseif($logoImage && file_exists($logoImage))
                        <img src="{{ $logoImage }}" class="logo" alt="Company logo">
                    @endif

                    <div class="invoice-number-label">Factura</div>
                    <div class="invoice-number">{{ $invoice->invoice_number }}</div>
                    <div style="font-size:22px;font-weight:700;">{{ old('issued_at', optional($invoice->issued_at)->format('d-m-Y H:i')) }}</div>
                </td>
            </tr>
        </table>
    </div>


    <div class="page">
        <table class="box-table">
            <tr>
                <td class="meta-box" width="50%">
                    <div class="meta-label">Fecha emisión: <span class="meta-value">{{ optional($invoice->issued_at)->format('d/m/Y') }}</span></div>

                </td>

                <td class="meta-box" width="50%">
                    <div class="meta-label">Referencia <span class="meta-value">{{ $invoice->invoice_number }}</span></div>

                </td>
            </tr>
        </table>

        <table class="box-table">
            <tr>
                <td class="box" width="50%">
                    <div class="label">Empresa emisora</div>
                    <div class="value-lg">{{ $invoice->company_name_snapshot }}</div>

                    @if($invoice->company_tax_id_snapshot)
                        <div class="value">Tax ID: {{ $invoice->company_tax_id_snapshot }}</div>
                    @endif

                    @if($invoice->company_responsible_name_snapshot)
                        <div class="value">Responsable: {{ $invoice->company_responsible_name_snapshot }}</div>
                    @endif
                </td>

                <td class="box box-gold" width="50%">
                    <div class="label label-gold">Destinatario</div>
                    <div class="value-lg">{{ $invoice->invoice_customer_name ?: 'No indicado' }}</div>
                    <div class="value">State ID: {{ $invoice->invoice_state_id ?: 'No indicado' }}</div>
                </td>
            </tr>
        </table>

        <div class="desc-box">
            <div class="desc-head">Descripción de la factura</div>
            <div class="desc-body">
                <div class="concept">{{ $invoice->concept }}</div>

                @if($invoice->description)
                    <div class="desc-text">{!! nl2br(e($invoice->description)) !!}</div>
                @else
                    <div class="desc-text">Sin descripción adicional.</div>
                @endif
            </div>
        </div>

        <div class="total-wrap">
            <div class="total-box">
                <div class="total-label">Importe total</div>
                <div class="total-amount">{{ number_format((float) $invoice->amount, 2, ',', '.') }} $</div>
            </div>
        </div>
    </div>
</body>
</html>
