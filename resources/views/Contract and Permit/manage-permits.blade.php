@extends('layouts.contract')

@section('content')
<style>
    body {
        background-color: #f4f6f9;
        font-family: 'Segoe UI', sans-serif;
        padding: 20px;
    }

    .container {
        max-width: 1200px;
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
    
    .permit-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    
    .permit-table th, .permit-table td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    
    .permit-table th {
        background-color: #f8f9fa;
        font-weight: 600;
    }
    
    .permit-table tr:hover {
        background-color: #f1f1f1;
    }
    
    .status-badge {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .status-active {
        background-color: #d4edda;
        color: #155724;
    }
    
    .status-expiring {
        background-color: #fff3cd;
        color: #856404;
    }
    
    .status-expired {
        background-color: #f8d7da;
        color: #721c24;
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
        <h2 class="form-title"><i class="fas fa-certificate"></i> Permit Management</h2>
        
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
        <form method="POST" action="{{ url('/permits') }}">
            @csrf
            
            <div class="form-grid">
                <div class="form-group">
                    <label for="permit_number">Permit Number</label>
                    <input type="text" id="permit_number" name="permit_number" placeholder="e.g., PERM-2023-001">
                </div>
                
                <div class="form-group">
                    <label for="permit_type">Permit Type</label>
                    <select id="permit_type" name="permit_type">
                        <option value="">Select Permit Type</option>
                        <option value="operational">Operational</option>
                        <option value="safety">Safety</option>
                        <option value="environmental">Environmental</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="issue_date">Issue Date</label>
                    <input type="date" id="issue_date" name="issue_date">
                </div>
                
                <div class="form-group">
                    <label for="expiry_date">Expiry Date</label>
                    <input type="date" id="expiry_date" name="expiry_date">
                </div>
                
                <div class="form-group">
                    <label for="related_contract">Related Contract</label>
                    <select id="related_contract" name="related_contract">
                        <option value="">Select Contract</option>
                        <option value="1">Contract #1 - ABC Construction</option>
                        <option value="2">Contract #2 - XYZ Rentals</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="active">Active</option>
                        <option value="expiring">Expiring Soon</option>
                        <option value="expired">Expired</option>
                    </select>
                </div>
            </div>
            
            <div class="form-group full-width">
                <label for="permit_notes">Notes</label>
                <textarea id="permit_notes" name="permit_notes" rows="4" placeholder="Special conditions, requirements, etc."></textarea>
            </div>
            
            <div class="form-actions">
                <button type="reset" class="btn btn-secondary">🔄 Reset</button>
                <button type="submit" class="btn btn-primary">💾 Save Permit</button>
            </div>
        </form>
    </div>
    
    <div class="form-wrapper">
        <h2 class="form-title"><i class="fas fa-list"></i> Existing Permits</h2>
        
        <table class="permit-table">
            <thead>
                <tr>
                    <th>Permit Number</th>
                    <th>Type</th>
                    <th>Issue Date</th>
                    <th>Expiry Date</th>
                    <th>Related Contract</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>PERM-2023-001</td>
                    <td>Operational</td>
                    <td>Jan 15, 2023</td>
                    <td>Dec 31, 2023</td>
                    <td>Contract #1</td>
                    <td><span class="status-badge status-active">Active</span></td>
                    <td>
                        <button class="btn btn-sm btn-primary">Edit</button>
                        <button class="btn btn-sm btn-secondary">Download</button>
                    </td>
                </tr>
                <tr>
                    <td>PERM-2023-002</td>
                    <td>Safety</td>
                    <td>Mar 10, 2023</td>
                    <td>Jun 30, 2023</td>
                    <td>Contract #2</td>
                    <td><span class="status-badge status-expiring">Expiring Soon</span></td>
                    <td>
                        <button class="btn btn-sm btn-primary">Edit</button>
                        <button class="btn btn-sm btn-secondary">Download</button>
                    </td>
                </tr>
                <tr>
                    <td>PERM-2022-005</td>
                    <td>Environmental</td>
                    <td>Aug 20, 2022</td>
                    <td>Feb 28, 2023</td>
                    <td>Contract #3</td>
                    <td><span class="status-badge status-expired">Expired</span></td>
                    <td>
                        <button class="btn btn-sm btn-primary">Renew</button>
                        <button class="btn btn-sm btn-secondary">Archive</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection