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

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .full-width {
        grid-column: span 2;
    }

    label {
        font-weight: 500;
        margin-bottom: 6px;
        color: #444;
    }

    input, select, textarea {
        padding: 10px 14px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        transition: border 0.3s;
    }

    input:focus, select:focus, textarea:focus {
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
    
    .btn-secondary {
        background-color: #6c757d;
        color: #fff;
    }
    
    .btn-secondary:hover {
        background-color: #545b62;
    }
    
    .btn-success {
        background-color: #28a745;
        color: #fff;
    }
    
    .btn-success:hover {
        background-color: #218838;
    }
    
    .contract-list {
        margin-top: 20px;
    }
    
    .contract-item {
        background: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .contract-info {
        flex: 1;
    }
    
    .contract-title {
        font-weight: 600;
        font-size: 16px;
        margin-bottom: 5px;
    }
    
    .contract-meta {
        font-size: 14px;
        color: #666;
    }
    
    .contract-actions {
        display: flex;
        gap: 10px;
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
        <h2 class="form-title"><i class="fas fa-redo"></i> Contract Renewal Request</h2>
        
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
        <form method="POST" action="{{ url('/renewals') }}">
            @csrf
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="contract_select">Select Contract</label>
                    <select id="contract_select" name="contract_select">
                        <option value="">Choose a contract</option>
                        <option value="1">Contract #1 - ABC Construction (Expires: Dec 31, 2023)</option>
                        <option value="2">Contract #2 - XYZ Rentals (Expires: Jun 30, 2023)</option>
                        <option value="3">Contract #3 - LMN Services (Expires: Sep 15, 2023)</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="renewal_type">Renewal Type</label>
                    <select id="renewal_type" name="renewal_type">
                        <option value="full">Full Renewal</option>
                        <option value="partial">Partial Renewal</option>
                        <option value="extension">Extension Only</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="new_start_date">New Start Date</label>
                    <input type="date" id="new_start_date" name="new_start_date">
                </div>
                
                <div class="form-group">
                    <label for="new_end_date">New End Date</label>
                    <input type="date" id="new_end_date" name="new_end_date">
                </div>
            </div>
            
            <div class="form-group full-width">
                <label for="renewal_reason">Reason for Renewal</label>
                <textarea id="renewal_reason" name="renewal_reason" rows="4" placeholder="Explain why this contract needs renewal"></textarea>
            </div>
            
            <div class="form-group full-width">
                <label for="special_conditions">Special Conditions (if any)</label>
                <textarea id="special_conditions" name="special_conditions" rows="3" placeholder="Any changes to terms, conditions, or scope of work"></textarea>
            </div>
            
            <div class="form-actions">
                <button type="reset" class="btn btn-secondary">🔄 Reset</button>
                <button type="submit" class="btn btn-primary">📤 Submit Renewal Request</button>
            </div>
        </form>
    </div>
    
    <div class="form-wrapper">
        <h2 class="form-title"><i class="fas fa-history"></i> Recent Renewal Requests</h2>
        
        <div class="contract-list">
            <div class="contract-item">
                <div class="contract-info">
                    <div class="contract-title">Contract #1 - ABC Construction</div>
                    <div class="contract-meta">Submitted: May 15, 2023 | Status: Pending Approval</div>
                </div>
                <div class="contract-actions">
                    <button class="btn btn-sm btn-primary">View Details</button>
                    <button class="btn btn-sm btn-secondary">Cancel</button>
                </div>
            </div>
            
            <div class="contract-item">
                <div class="contract-info">
                    <div class="contract-title">Contract #3 - LMN Services</div>
                    <div class="contract-meta">Submitted: Apr 28, 2023 | Status: Approved</div>
                </div>
                <div class="contract-actions">
                    <button class="btn btn-sm btn-primary">View Details</button>
                    <button class="btn btn-sm btn-success">Download Renewed Contract</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection