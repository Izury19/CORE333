<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Maintenance Schedule Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f8;
            padding: 20px;
            color: #333;
        }
        .container {
            max-width: 600px;
            background: #fff;
            margin: auto;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: #007bff;
            color: white;
            padding: 15px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }
        .reminder {
            background: #ffeeba;
            color: #856404;
            padding: 12px;
            font-size: 15px;
            font-weight: 600;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .content p {
            margin: 8px 0;
            font-size: 15px;
        }
        .badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            color: #fff;
            font-weight: bold;
            font-size: 0.9em;
        }
        .badge-pending { background: #f0ad4e; }
        .badge-completed { background: #87CEEB; }
        .badge-overdue { background: #e74c3c; }
        .footer {
            text-align: center;
            font-size: 13px;
            color: #666;
            background: #f9f9f9;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">📅 Maintenance Schedule Notification</div>

        <div class="reminder">
            ⚠️ Reminder: You have a maintenance schedule on 
            <strong>{{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('F d, Y') }}</strong>.
        </div>

        <div class="content">
            <p><strong>Equipment:</strong> {{ $schedule->equipment_name }}</p>
            <p><strong>Maintenance Type:</strong> {{ optional($schedule->maintenanceType)->name }}</p>
            <p><strong>Scheduled Date:</strong> {{ \Carbon\Carbon::parse($schedule->scheduled_date)->format('F d, Y') }}</p>
            <p><strong>Technician:</strong> {{ $schedule->technician_name }}</p>
            <p>
                <strong>Status:</strong>
                @php
                    $status = strtolower($schedule->status);
                    $badgeClass = $status === 'pending' ? 'badge-pending' :
                                  ($status === 'completed' ? 'badge-completed' : 'badge-overdue');
                @endphp
                <span class="badge {{ $badgeClass }}">
                    {{ ucfirst($schedule->status) }}
                </span>
            </p>
        </div>

        <div class="footer">
            This is an automated notification from <strong>Cali-CMS</strong>. <br>
            Please do not reply to this email.
        </div>
    </div>
</body>
</html>
