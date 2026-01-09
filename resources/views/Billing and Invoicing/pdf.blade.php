<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_uid }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th, td { border: 1px solid #000; padding: 8px; text-align: left; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>CaliCrane Billing</h2>
    <p><strong>Invoice:</strong> {{ $invoice->invoice_uid }}</p>
    <p><strong>Client:</strong> {{ $invoice->client_name }}</p>
    <p><strong>Period:</strong> 
        {{ \Carbon\Carbon::parse($invoice->billing_period_start)->format('M d, Y') }} - 
        {{ \Carbon\Carbon::parse($invoice->billing_period_end)->format('M d, Y') }}
    </p>

    <table>
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ ucfirst($invoice->equipment_type) }} Rental ({{ $invoice->hours_used }} hrs)</td>
                <td class="text-right">₱{{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td><strong>TOTAL</strong></td>
                <td class="text-right"><strong>₱{{ number_format($invoice->total_amount, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <p><strong>Status:</strong> {{ ucfirst($invoice->status) }}</p>
</body>
</html>