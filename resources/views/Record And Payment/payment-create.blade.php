@extends('layouts.maintenance')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">📤 Upload Proof of Payments</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <p><strong>Invoice #{{ $invoice->invoice_id }}</strong></p>
            <p><strong>Client:</strong> {{ $invoice->client_name }}</p>
            <p><strong>Total Amount:</strong> ₱{{ number_format($invoice->total, 2) }}</p>

            <form action="{{ route('payments.upload.store', ['invoiceId' => $invoice->invoice_id]) }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Amount --}}
                <div class="mb-3">
                    <label for="amount" class="form-label">💵 Amount Paid</label>
                    <input type="number" name="amount" id="amount" step="0.01" class="form-control" required>
                    @error('amount') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Payment Method --}}
                <div class="mb-3">
                    <label for="payment_method" class="form-label">🏦 Payment Method</label>
                    <select name="payment_method" id="payment_method" class="form-select" required>
                        <option value="">-- Select Payment Method --</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="gcash">GCash</option>
                        <option value="paypal">PayPal</option>
                        <option value="cash">Cash</option>
                    </select>
                    @error('payment_method') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                {{-- Proof --}}
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

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('success') }}",
            confirmButtonText: 'OK'
        });
    @endif

    @if($errors->any())
        Swal.fire({
            icon: 'error',
            title: 'Error!',
            html: `
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            `,
            confirmButtonText: 'OK'
        });
    @endif
</script>
@endsection
