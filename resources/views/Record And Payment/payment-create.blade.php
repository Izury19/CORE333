@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">📤 Upload Proof of Payments</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <p><strong>Invoice #{{ $invoice->invoice_id }}</strong></p>
            <p><strong>Client:</strong> {{ $invoice->client_name }}</p>
            <p><strong>Total Amount:</strong> ₱{{ number_format($invoice->total, 2) }}</p>

            <form action="{{ route('payments.upload.store', $invoice->id) }}" method="POST" enctype="multipart/form-data">

                @csrf

                <div class="mb-3">
                    <label for="amount" class="form-label">💵 Amount Paid</label>
                    <input type="number" name="amount" id="amount" step="0.01" class="form-control" required>
                    @error('amount') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label for="proof" class="form-label">📎 Upload Proof (jpg, png, pdf)</label>
                    <input type="file" name="proof" id="proof" class="form-control" required>
                    @error('proof') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <button type="submit" class="btn btn-primary">Submit Proof</button>
            </form>
        </div>
    </div>
</div>
@endsection
