<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $invoice->invoice_number }}</title>
    <style>
        body{
            margin:0;
            padding:30px;
            background:#111;
        }

        .invoice-wrap{
            max-width:1100px;
            margin:0 auto;
        }
    </style>
</head>
<body>
    <div class="invoice-wrap">
        @include('admin.invoices.partials.invoice-template', [
            'invoice' => $invoice,
            'isPdf' => false,
            'isPng' => true,
        ])
    </div>
</body>
</html>
