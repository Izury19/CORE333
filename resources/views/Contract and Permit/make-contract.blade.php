@extends('layouts.contract')

@section('content')
<style>
    body {
        background-color: #f4f6f9;
        font-family: 'Segoe UI', 'Roboto', sans-serif;
        padding: 20px;
        color: #2d3748;
    }

    .container {
        max-width: 950px;
        margin: 0 auto 60px auto;
        padding: 0 20px;
    }

    /* Form Wrapper */
    .form-wrapper {
        background: #ffffff;
        padding: 40px 35px;
        border-radius: 12px;
        box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        border: 1px solid #e5e7eb;
    }

    .form-title {
        font-size: 30px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 28px;
        text-align: center;
        letter-spacing: 0.5px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 22px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .full-width {
        grid-column: span 2;
    }

    label {
        font-weight: 600;
        margin-bottom: 8px;
        color: #374151;
        font-size: 14px;
    }

    input, select, textarea {
        padding: 12px 15px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        font-size: 15px;
        background-color: #f9fafb;
        transition: all 0.3s ease;
    }

    input:focus, select:focus, textarea:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37,99,235,0.15);
        background: #fff;
    }

    .form-actions {
        margin-top: 35px;
        text-align: right;
    }

    .btn-save {
        background: linear-gradient(90deg, #2563eb, #1d4ed8);
        color: #fff;
        padding: 13px 28px;
        font-size: 15px;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(37,99,235,0.25);
    }

    .btn-save:hover {
        background: linear-gradient(90deg, #1d4ed8, #1e40af);
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(37,99,235,0.35);
    }

    /* Flash + Errors */
    .alert-success {
        background-color: #ecfdf5;
        color: #065f46;
        padding: 12px 15px;
        border: 1px solid #a7f3d0;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    .alert-error {
        background-color: #fef2f2;
        color: #991b1b;
        padding: 12px 15px;
        border: 1px solid #fecaca;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 14px;
    }

    /* Contract Style */
    .new-contract {
        margin-top: 45px;
        background-color: #fff;
        padding: 50px 40px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        font-family: 'Times New Roman', serif;
        color: #111827;
    }

    .new-contract h3 {
        text-align: center;
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 40px;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-bottom: 2px solid #d1d5db;
        padding-bottom: 10px;
    }

    .contract-section {
        margin-bottom: 30px;
    }

    .contract-section h4 {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 10px;
        border-bottom: 1px solid #d1d5db;
        padding-bottom: 5px;
    }

    .contract-section p {
        margin: 5px 0;
        line-height: 1.6;
    }

    .contract-section .label {
        font-weight: bold;
        width: 150px;
        display: inline-block;
    }

    .contract-section .value {
        display: inline-block;
    }

    .contract-details {
        margin-top: 15px;
        padding: 15px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background-color: #f9fafb;
        font-style: italic;
    }

    .signature {
        margin-top: 50px;
        text-align: left;
    }

    .signature-line {
        margin-top: 60px;
        border-top: 1px solid #111827;
        width: 250px;
    }

</style>

<div class="container">
    <div class="form-wrapper">

        {{-- Flash Success Message --}}
        @if(session('success'))
            <div class="alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Display Validation Errors --}}
        @if($errors->any())
            <div class="alert-error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <h2 class="form-title">📄 Make a New Contract</h2>

        <form method="POST" action="{{ route('contracts.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label for="company_name">Company Name</label>
                    <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" placeholder="e.g., ABC Construction Ltd.">
                </div>

                <div class="form-group">
                    <label for="client_name">Client Name</label>
                    <input type="text" id="client_name" name="client_name" value="{{ old('client_name') }}" placeholder="e.g., Juan Dela Cruz">
                </div>

                <div class="form-group">
                    <label for="client_email">Client Email</label>
                    <input type="email" id="client_email" name="client_email" value="{{ old('client_email') }}" placeholder="e.g., client@example.com">
                </div>

                <div class="form-group">
                    <label for="client_number">Client Number</label>
                    <input type="text" id="client_number" name="client_number" value="{{ old('client_number') }}" placeholder="e.g., 09171234567">
                </div>

                <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}">
                </div>

                <div class="form-group">
                    <label for="end_date">End Date</label>
                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}">
                </div>

                <div class="form-group">
                    <label for="equipment_type">Equipment Type</label>
                    <select id="equipment_type" name="equipment_type">
                        <option value="">Select Equipment</option>
                        <option value="crane" {{ old('equipment_type') == 'crane' ? 'selected' : '' }}>Crane</option>
                        <option value="truck" {{ old('equipment_type') == 'truck' ? 'selected' : '' }}>Truck</option>
                        <option value="trailer" {{ old('equipment_type') == 'trailer' ? 'selected' : '' }}>Trailer</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="payment_type">Payment Type</label>
                    <select id="payment_type" name="payment_type">
                        <option value="">Select Payment Type</option>
                        <option value="cash" {{ old('payment_type') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bank transfer" {{ old('payment_type') == 'bank transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="gcash" {{ old('payment_type') == 'gcash' ? 'selected' : '' }}>Gcash</option>
                    </select>
                </div>
            </div>

            <div class="form-group full-width">
                <label for="contract_details">Contract Details / Notes</label>
                <textarea id="contract_details" name="contract_details" rows="5" placeholder="Include terms, conditions, scope of work, etc.">{{ old('contract_details') }}</textarea>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">💾 Save Contract</button>
            </div>
        </form>

    </div>

    {{-- Newly Created Contract Display --}}
    @if(session('new_contract'))
        @php $c = session('new_contract'); @endphp
        <div class="new-contract">
            <h3>Contract Agreement</h3>

            <div class="contract-section">
                <h4>Parties</h4>
                <p><span class="label">Company Name:</span> <span class="value">{{ $c['company_name'] }}</span></p>
                <p><span class="label">Client Name:</span> <span class="value">{{ $c['client_name'] }}</span></p>
                <p><span class="label">Client Email:</span> <span class="value">{{ $c['client_email'] ?? 'N/A' }}</span></p>
                <p><span class="label">Client Number:</span> <span class="value">{{ $c['client_number'] ?? 'N/A' }}</span></p>
            </div>

            <div class="contract-section">
                <h4>Contract Duration</h4>
                <p><span class="label">Start Date:</span> <span class="value">{{ \Carbon\Carbon::parse($c['start_date'])->toFormattedDateString() }}</span></p>
                <p><span class="label">End Date:</span> <span class="value">{{ \Carbon\Carbon::parse($c['end_date'])->toFormattedDateString() }}</span></p>
            </div>

            <div class="contract-section">
                <h4>Equipment & Payment</h4>
                <p><span class="label">Equipment Type:</span> <span class="value">{{ ucfirst($c['equipment_type']) }}</span></p>
                <p><span class="label">Payment Type:</span> <span class="value">{{ ucfirst(str_replace('_', ' ', $c['payment_type'])) }}</span></p>
            </div>

            <div class="contract-section">
                <h4>Contract Details</h4>
                <p class="contract-details">{{ $c['contract_details'] ?: 'N/A' }}</p>
            </div>

            <div class="signature">
                <p>Authorized Signature</p>
                <div class="signature-line"></div>
            </div>
        </div>
    @endif
</div>
@endsection
