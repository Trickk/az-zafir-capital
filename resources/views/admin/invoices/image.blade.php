<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ $invoice->invoice_number }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #e5e7eb;
            font-family: Arial, Helvetica, sans-serif;
        }

        .page {
            width: 1200px;
            min-height: 1700px;
            margin: 0 auto;
            background: #ffffff;
            color: #111827;
        }

        .header {
            background: linear-gradient(135deg, #0b0b0f 0%, #16161d 55%, #1f1a12 100%);
            color: #ffffff;
            padding: 36px 42px;
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
            width: 280px;
            text-align: right;
        }

        .kicker {
            font-size: 12px;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: rgba(255,255,255,.65);
            margin-bottom: 10px;
        }

        .company-name {
            font-size: 34px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 8px;
        }

        .muted-light {
            font-size: 15px;
            color: rgba(255,255,255,.78);
        }

        .invoice-label {
            font-size: 14px;
            color: rgba(255,255,255,.72);
            margin-top: 10px;
        }

        .invoice-number {
            font-size: 28px;
            font-weight: 700;
        }

        .brand-image {
            max-width: 240px;
            max-height: 100px;
            object-fit: contain;
            margin-bottom: 14px;
        }

        .content {
            padding: 40px 42px 46px;
        }

        .boxes {
            width: 100%;
            border-collapse: separate;
            border-spacing: 18px 0;
            margin: 0 -18px 28px;
        }

        .box {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 20px;
            vertical-align: top;
            background: #f8fafc;
        }

        .box-gold {
            background: #fffaf0;
            border-color: rgba(212,175,55,.35);
        }

        .box-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: #6b7280;
            margin-bottom: 10px;
        }

        .box-label-gold {
            color: #8a6b1f;
        }

        .box-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .box-line {
            font-size: 15px;
            color: #4b5563;
            margin-bottom: 4px;
        }

        .meta {
            width: 100%;
            border-collapse: separate;
            border-spacing: 16px 0;
            margin: 0 -16px 28px;
        }

        .meta-box {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 18px;
            vertical-align: top;
        }

        .meta-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .meta-value {
            font-size: 17px;
            font-weight: 700;
        }

        .desc {
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            overflow: hidden;
            margin-bottom: 28px;
        }

        .desc-head {
            background: #f9fafb;
            padding: 14px 18px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .12em;
            color: #6b7280;
        }

        .desc-body {
            padding: 22px 18px;
        }

        .concept {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 12px;
        }

        .desc-text {
            font-size: 16px;
            line-height: 1.7;
            color: #374151;
            white-space: pre-line;
        }

        .total-wrap {
            text-align: right;
        }

        .total-box {
            display: inline-block;
            min-width: 340px;
            background: linear-gradient(135deg, #111827 0%, #1f2937 100%);
            color: #ffffff;
            border-radius: 16px;
            padding: 24px 26px;
        }

        .total-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .14em;
            color: rgba(255,255,255,.68);
            margin-bottom: 10px;
        }

        .total-amount {
            font-size: 40px;
            font-weight: 800;
            line-height: 1;
            color: #f4d27a;
        }
    </style>
</head>
<body>
    <div class="page">
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
                        @if($invoice->company_invoice_image_path_snapshot)
                            <img
                                src="{{ asset('storage/' . $invoice->company_invoice_image_path_snapshot) }}"
                                alt="Invoice image"
                                class="brand-image"
                            >
                        @elseif($invoice->company_logo_path_snapshot)
                            <img
                                src="{{ asset('storage/' . $invoice->company_logo_path_snapshot) }}"
                                alt="Company logo"
                                class="brand-image"
                            >
                        @endif

                        <div class="invoice-label">Factura</div>
                        <div class="invoice-number">{{ $invoice->invoice_number }}</div>
                    </td>
                </tr>
            </table>
        </div>

        <div class="content">
            <table class="meta">
                <tr>
                    <td class="meta-box" width="50%">
                        <div class="meta-label">Fecha emisión: <span class="meta-value">{{ optional($invoice->issued_at)->format('d/m/Y') }}</span></div>

                    </td>

                    <td class="meta-box" width="50%">
                        <div class="meta-label">Referencia: <span class="meta-value">{{ $invoice->invoice_number }}</span></div>

                    </td>
                </tr>
            </table>

            <table class="boxes">
                <tr>
                    <td class="box" width="50%">
                        <div class="box-label">Empresa emisora</div>
                        <div class="box-title">{{ $invoice->company_name_snapshot }}</div>

                        @if($invoice->company_tax_id_snapshot)
                            <div class="box-line">Tax ID: {{ $invoice->company_tax_id_snapshot }}</div>
                        @endif

                        @if($invoice->company_responsible_name_snapshot)
                            <div class="box-line">Responsable: {{ $invoice->company_responsible_name_snapshot }}</div>
                        @endif
                    </td>

                    <td class="box box-gold" width="50%">
                        <div class="box-label box-label-gold">Destinatario</div>
                        <div class="box-title">{{ $invoice->invoice_customer_name ?: 'No indicado' }}</div>
                        <div class="box-line">State ID: {{ $invoice->invoice_state_id ?: 'No indicado' }}</div>
                    </td>
                </tr>
            </table>

            <div class="desc">
                <div class="desc-head">Descripción de la factura</div>
                <div class="desc-body">
                    <div class="concept">{{ $invoice->concept }}</div>

                    @if($invoice->description)
                        <div class="desc-text">{{ $invoice->description }}</div>
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
    </div>
</body>
</html>
