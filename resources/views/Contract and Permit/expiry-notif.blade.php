@extends('layouts.contract')

@section('content')
<style>
    body {
        background-color: #f4f6f9;
        font-family: 'Segoe UI', sans-serif;
        padding: 20px;
    }

    .container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    .form-wrapper {
        background: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        margin-bottom: 30px;
    }

    .form-title {
        font-size: 24px;
        font-weight: bold;
        color: #333;
        margin-bottom: 25px;
        display: flex;
        align-items: center;
    }
    
    .form-title i {
        margin-right: 10px;
        color: #007BFF;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 20px;
    }

    label {
        font-weight: 500;
        margin-bottom: 6px;
        color: #444;
    }

    select {
        padding: 10px 14px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        transition: border 0.3s;
        max-width: 300px;
    }

    select:focus {
        outline: none;
        border-color: #007BFF;
    }

    .form-actions {
        margin-top: 30px;
        text-align: right;
    }

    .btn {
        padding: 10px 20px;
        font-size: 14px;
        font-weight: 500;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s;
        margin-left: 10px;
    }
    
    .btn-primary {
        background-color: #007BFF;
        color: #fff;
    }
    
    .btn-primary:hover {
        background-color: #0056b3;
    }
    
    .btn-warning {
        background-color: #ffc107;
        color: #212529;
    }
    
    .btn-warning:hover {
        background-color: #e0a800;
    }
    
    .notification-item {
        background: #f8f9fa;
        border-left: 4px solid #ffc107;
        padding: 15px;
        margin-bottom: 15px;
        border-radius: 0 8px 8px 0;
    }
    
    .notification-title {
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .notification-date {
        font-size: 14px;
        color: #666;
        margin-bottom: 10px;
    }
    
    .notification-actions {
        display: flex;
        gap: 10px;
    }
    
    .expiry-soon {
        border-left-color: #dc3545;
    }
    
    .expired {
        border-left-color: #6c757d;
        background-color: #f1f1f1;
    }
    
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 6px;
    }
    
    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }
    
    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }
</style>

<div class="container">
    <div class="form-wrapper">
        <h2 class="form-title"><i class="fas fa-bell"></i> Contract & Permit Expiry Notifications</h2>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <!-- Fixed form action to use URL instead of named route -->
        <form method="POST" action="{{ url('/notifications/settings') }}">
            @csrf
            <div class="form-group">
                <label for="notification_period">Notification Period</label>
                <select id="notification_period" name="notification_period">
                    <option value="7">7 days before expiry</option>
                    <option value="14" selected>14 days before expiry</option>
                    <option value="30">30 days before expiry</option>
                    <option value="60">60 days before expiry</option>
                </select>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">💾 Save Settings</button>
                <button type="button" class="btn btn-warning" onclick="sendNotifications()">📧 Send Notifications Now</button>
            </div>
        </form>
    </div>
    
    <div class="form-wrapper">
        <h2 class="form-title"><i class="fas fa-exclamation-triangle"></i> Upcoming Expirations</h2>
        
        <div class="notification-item expiry-soon">
            <div class="notification-title">Contract #2 - XYZ Rentals</div>
            <div class="notification-date">Expiry Date: Jun 30, 2023 (14 days remaining)</div>
            <div>Related Permits: PERM-2023-002 (Safety)</div>
            <div class="notification-actions">
                <button class="btn btn-sm btn-primary">Renew Contract</button>
                <button class="btn btn-sm btn-warning">Notify Client</button>
                <button class="btn btn-sm btn-secondary">View Details</button>
            </div>
        </div>
        
        <div class="notification-item expiry-soon">
            <div class="notification-title">Permit PERM-2023-003</div>
            <div class="notification-date">Expiry Date: Jul 15, 2023 (29 days remaining)</div>
            <div>Related Contract: Contract #4 - GHI Equipment</div>
            <div class="notification-actions">
                <button class="btn btn-sm btn-primary">Renew Permit</button>
                <button class="btn btn-sm btn-warning">Notify Responsible Party</button>
                <button class="btn btn-sm btn-secondary">View Details</button>
            </div>
        </div>
        
        <div class="notification-item expired">
            <div class="notification-title">Permit PERM-2022-005</div>
            <div class="notification-date">Expired: Feb 28, 2023</div>
            <div>Related Contract: Contract #3 - LMN Services</div>
            <div class="notification-actions">
                <button class="btn btn-sm btn-primary">Renew Now</button>
                <button class="btn btn-sm btn-secondary">Archive</button>
            </div>
        </div>
    </div>
</div>

<script>
    function sendNotifications() {
        if(confirm('Are you sure you want to send notifications now?')) {
            // Here you would typically make an AJAX request to send notifications
            alert('Notifications sent successfully!');
        }
    }
</script>
@endsection