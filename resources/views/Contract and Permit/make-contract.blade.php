@extends('layouts.contract')

@section('content')
<style>
    body {
        background-color: #2e2e2e;
        font-family: 'Segoe UI', sans-serif;
        padding: 20px;
    }

   
    .container {
        max-width: 900px;
        margin: 0 auto 60px auto; /* No top margin (0) */
        padding: 0 20px;
    }
    .form-wrapper {
        background: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }

    .form-title {
        font-size: 28px;
        font-weight: bold;
        color: #333;
        margin-bottom: 25px;
    }

    /* Grid layout */
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

    /* Labels and Inputs */
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

    /* Button */
    .form-actions {
        margin-top: 30px;
        text-align: right;
    }

    .btn-save {
        background-color: #007BFF;
        color: #fff;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 500;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: background 0.3s;
    }

    .btn-save:hover {
        background-color: #0056b3;
    }

    /* Newly Created Contract Styles */
    .new-contract {
        margin-top: 40px;
        background-color: #f0f4f8;
        border: 1px solid #cbd5e1;
        padding: 25px 30px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(96, 165, 250, 0.25);
        color: #1e293b;
        font-family: 'Segoe UI', sans-serif;
    }

    .new-contract h3 {
        color: #2563eb; /* blue-600 */
        font-weight: 700;
        margin-bottom: 20px;
        font-size: 24px;
    }

    .new-contract p {
        font-size: 16px;
        margin: 8px 0;
    }

    .new-contract strong {
        color: #1e40af; /* blue-800 */
    }

</style>


<div class="container">
    <div class="form-wrapper">

        {{-- 🔵 Flash Success Message --}}
        @if(session('success'))
            <div style="background-color: #d4edda; color: #155724; padding: 10px; margin-bottom: 20px; border-radius: 5px;">
                {{ session('success') }}
            </div>
        @endif

        {{-- 🔵 Display Validation Errors --}}
        @if($errors->any())
            <div style="background-color: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 20px; border-radius: 5px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h2 class="form-title">📄 Make a New Contract</h2>

        <!-- 🔵 Contract Form -->
        <form method="POST" action="{{ route('contracts.store') }}">
            @csrf

            <div class="form-grid">
                <!-- Contract Title -->
                <div class="form-group">
                    <label for="contract_title">Contract Title</label>
                    <input type="text" id="contract_title" name="contract_title" value="{{ old('contract_title') }}" placeholder="e.g., Monthly Crane Lease Contract">
                </div>

                <!-- Client Name -->
                <div class="form-group">
                    <label for="client_name">Client Name</label>
                    <input type="text" id="client_name" name="client_name" value="{{ old('client_name') }}" placeholder="e.g., ABC Construction Ltd.">
                </div>

                <!-- Start Date -->
                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}">
                </div>

                <!-- End Date -->
                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}">
                </div>

                <!-- Equipment Type -->
                <div class="form-group">
                    <label for="equipment_type">Equipment Type</label>
                    <select id="equipment_type" name="equipment_type">
                        <option value="">Select Equipment</option>
                        <option value="crane" {{ old('equipment_type') == 'crane' ? 'selected' : '' }}>Crane</option>
                        <option value="truck" {{ old('equipment_type') == 'truck' ? 'selected' : '' }}>Truck</option>
                        <option value="trailer" {{ old('equipment_type') == 'trailer' ? 'selected' : '' }}>Trailer</option>
                    </select>
                </div>

                <!-- Payment Type -->
                <div class="form-group">
                    <label for="payment_type">Payment Type</label>
                    <select id="payment_type" name="payment_type">
                        <option value="">Select Payment Type</option>
                        <option value="per_hour" {{ old('payment_type') == 'per_hour' ? 'selected' : '' }}>Per Hour</option>
                        <option value="daily" {{ old('payment_type') == 'daily' ? 'selected' : '' }}>Daily</option>
                        <option value="monthly" {{ old('payment_type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                    </select>
                </div>
            </div>

            <!-- Contract Details -->
            <div class="form-group full-width">
                <label for="contract_details">Contract Details / Notes</label>
                <textarea id="contract_details" name="contract_details" rows="5" placeholder="Include terms, conditions, scope of work, etc.">{{ old('contract_details') }}</textarea>
            </div>

            <!-- Submit Button -->
            <div class="form-actions">
                <button type="submit" class="btn-save">💾 Save Contract</button>
            </div>
        </form>
    </div>

    @if(session('new_contract'))
    @php $c = session('new_contract'); @endphp
    <div class="new-contract">
        <h3>📌 Newly Created Contract</h3>
        <p><strong>Title:</strong> {{ $c['contract_title'] }}</p>
        <p><strong>Client:</strong> {{ $c['client_name'] }}</p>
        <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($c['start_date'])->toFormattedDateString() }}</p>
        <p><strong>End Date:</strong> {{ \Carbon\Carbon::parse($c['end_date'])->toFormattedDateString() }}</p>
        <p><strong>Equipment Type:</strong> {{ ucfirst($c['equipment_type']) }}</p>
        <p><strong>Payment Type:</strong> {{ ucfirst(str_replace('_', ' ', $c['payment_type'])) }}</p>
        <p><strong>Details:</strong> {{ $c['contract_details'] ?: 'N/A' }}</p>
    </div>
    @endif

</div>

@endsection
