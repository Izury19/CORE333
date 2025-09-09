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
    .new-contract {
    margin-top: 40px;
    background-color: #f9fafb;
    border: 1px solid #e2e8f0;
    padding: 30px 35px;
    border-radius: 14px;
    box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
    color: #1f2937;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    transition: box-shadow 0.3s ease;
}

.new-contract:hover {
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3); /* Blue glow on hover */
}

.new-contract h3 {
    color: #2563eb; /* Tailwind Blue-600 */
    font-weight: 700;
    margin-bottom: 25px;
    font-size: 26px;
    letter-spacing: 0.03em;
    text-shadow: 1px 1px 2px rgba(37, 99, 235, 0.3);
}

.contract-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 18px 40px;
    font-size: 16px;
    line-height: 1.5;
}

.contract-details-grid strong {
    display: block;
    color: #1e40af; /* Tailwind Blue-800 */
    margin-bottom: 6px;
    font-weight: 600;
}

.contract-details-grid span {
    color: #374151;
}

.contract-details-grid .full-row {
    grid-column: 1 / -1;
    margin-top: 10px;
    font-style: italic;
    color: #4b5563;
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
                <!-- Company Name -->
                <div class="form-group">
                    <label for="company_name">Company Name</label>
                    <input type="text" id="company_name" name="company_name" value="{{ old('company_name') }}" placeholder="e.g., ABC Construction Ltd.">
                </div>

                <!-- Client Name -->
                <div class="form-group">
                    <label for="client_name">Client Name</label>
                    <input type="text" id="client_name" name="client_name" value="{{ old('client_name') }}" placeholder="e.g., Juan Dela Cruz">
                </div>

                <!-- Client Email -->
                <div class="form-group">
                    <label for="client_email">Client Email</label>
                    <input type="email" id="client_email" name="client_email" value="{{ old('client_email') }}" placeholder="e.g., client@example.com">
                </div>

                <!-- Client Number -->
                <div class="form-group">
                    <label for="client_number">Client Number</label>
                    <input type="text" id="client_number" name="client_number" value="{{ old('client_number') }}" placeholder="e.g., 09171234567">
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
                        <option value="cash" {{ old('payment_type') == 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="bank transfer" {{ old('payment_type') == 'bank transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="gcash" {{ old('payment_type') == 'gcash' ? 'selected' : '' }}>Gcash</option>
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
            <div class="contract-details-grid">
                <div><strong>Company Name:</strong> <span>{{ $c['company_name'] }}</span></div>
                <div><strong>Client Name:</strong> <span>{{ $c['client_name'] }}</span></div>
                <div><strong>Client Email:</strong> <span>{{ $c['client_email'] ?? 'N/A' }}</span></div>
                <div><strong>Client Number:</strong> <span>{{ $c['client_number'] ?? 'N/A' }}</span></div>
                <div><strong>Start Date:</strong> <span>{{ \Carbon\Carbon::parse($c['start_date'])->toFormattedDateString() }}</span></div>
                <div><strong>End Date:</strong> <span>{{ \Carbon\Carbon::parse($c['end_date'])->toFormattedDateString() }}</span></div>
                <div><strong>Equipment Type:</strong> <span>{{ ucfirst($c['equipment_type']) }}</span></div>
                <div><strong>Payment Type:</strong> <span>{{ ucfirst(str_replace('_', ' ', $c['payment_type'])) }}</span></div>
                <div class="full-row"><strong>Details:</strong> <span>{{ $c['contract_details'] ?: 'N/A' }}</span></div>
            </div>
        </div>
        @endif



</div>

@endsection
