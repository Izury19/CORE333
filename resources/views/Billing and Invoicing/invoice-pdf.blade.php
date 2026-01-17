<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_uid }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .header { text-align: center; margin-bottom: 30px; }
        .company-name { font-size: 24px; font-weight: bold; color: #333; }
        .invoice-title { font-size: 20px; margin: 20px 0; }
        .invoice-details { margin: 20px 0; }
        .invoice-table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        .invoice-table th, .invoice-table td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        .total-row { font-weight: bold; background-color: #f5f5f5; }
        .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">CALI HEAVY EQUIPMENT INC.</div>
        <div>Equipment Rental Services</div>
        <div>Manila, Philippines</div>
    </div>
    
    <div class="invoice-title">OFFICIAL INVOICE</div>
    
    <div class="invoice-details">
        <strong>Invoice Number:</strong> {{ $invoice->invoice_uid }}<br>
        <strong>Date Issued:</strong> {{ \Carbon\Carbon::now()->format('F d, Y') }}<br>
        <strong>Client:</strong> {{ $invoice->client_name }}<br>
        @if($invoice->job_order_id)
            <strong>Job Order:</strong> JO-{{ str_pad($invoice->job_order_id, 4, '0', STR_PAD_LEFT) }}
        @endif
    </div>
    
    <table class="invoice-table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    {{ ucfirst(str_replace('_', ' ', $invoice->equipment_type)) }} Rental
                    @if($invoice->billing_period_start && $invoice->billing_period_end)
                        ({{ \Carbon\Carbon::parse($invoice->billing_period_start)->format('M d') }} - {{ \Carbon\Carbon::parse($invoice->billing_period_end)->format('M d, Y') }})
                    @endif
                </td>
                <td>{{ $invoice->hours_used ?? 1 }} hours</td>
                <td>₱{{ number_format($invoice->hourly_rate, 2) }}</td>
                <td>₱{{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="3" style="text-align: right;"><strong>TOTAL AMOUNT DUE:</strong></td>
                <td><strong>₱{{ number_format($invoice->total_amount, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>
    
    <div class="payment-instructions">
        <strong>Payment Instructions:</strong><br>
        Please pay via Bank Transfer, GCash, or Cash.<br>
        Send proof of payment to: billing@caliheavy.com
    </div>
    
    <div class="footer">
        This is a computer-generated invoice. No signature required.<br>
        Thank you for your business!
    </div>
</body>
</html>