<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $invoice->invoice_number }}</title>
</head>
<body style="margin:0; padding:20px; background:#ffffff;">
    @include('admin.invoices.partials.invoice-template', ['invoice' => $invoice])
</body>
</html>
