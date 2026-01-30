<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Financial Report</title>
    <style>
        
        body { 
            font-family: Arial, sans-serif; 
            margin: 20px; 
            font-size: 12px;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 { 
            margin: 0; 
            color: #333;
        }
        .summary { 
            margin-bottom: 20px; 
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 20px;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left;
        }
        th { 
            background-color: #f2f2f2; 
            font-weight: bold;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Financial Intelligence Report</h1>
        <p>Generated on: {{ date('F d, Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <p><strong>Total Revenue:</strong>{{ number_format($totalRevenue, 2) }}php</p>
        <p><strong>Collection Rate:</strong> {{ $collectionRatePercent }}%</p>
        <p><strong>Active Clients:</strong> {{ $invoiceDetails->count() }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Invoice ID</th>
                <th>Client Name</th>
                <th>Equipment Type</th>
                <th>Equipment ID</th>
                <th>Hours Used</th>
                <th>Hourly Rate</th>
                <th>Total Amount</th>
                <th>Date Created</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoiceDetails as $invoice)
            <tr>
                <td>INV-{{ str_pad($invoice->id, 3, '0', STR_PAD_LEFT) }}</td>
                <td>{{ $invoice->client_name }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $invoice->equipment_type)) }}</td>
                <td>{{ $invoice->equipment_id }}</td>
                <td>{{ $invoice->hours_used }}</td>
                <td>₱{{ number_format($invoice->hourly_rate, 2) }}</td>
                <td>₱{{ number_format($invoice->total_amount, 2) }}</td>
                <td>{{ \Carbon\Carbon::parse($invoice->created_at)->format('M d, Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>This is a system-generated report. Do not distribute without authorization.</p>
    </div>
</body>
</html>