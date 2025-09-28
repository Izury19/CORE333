@extends('layouts.maintenance')

@section('content')
<div class="container mt-4" style="max-width:600px;">
    <h2 class="mb-4">📤 Upload Proof of Payment</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <p><strong>Invoice #{{ $invoice->invoice_id }}</strong></p>
            <p><strong>Client:</strong> {{ $invoice->client_name }}</p>
            <p><strong>Total Amount:</strong> ₱{{ number_format($invoice->total,2) }}</p>

            <form id="uploadForm" action="{{ route('payments.upload.store', ['invoiceId'=>$invoice->invoice_id]) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label>💵 Amount Paid</label>
                    <input type="number" name="amount" class="form-control" step="0.01" required>
                    @error('amount') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label>🏦 Payment Method</label>
                    <select name="payment_method" class="form-select" required>
                        <option value="">-- Select --</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="gcash">GCash</option>
                        <option value="paypal">PayPal</option>
                        <option value="cash">Cash</option>
                    </select>
                    @error('payment_method') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="mb-3">
                    <label>📎 Upload Proof (JPG, PNG, PDF)</label>
                    <input type="file" name="proof" id="proof" class="form-control" accept=".jpg,.jpeg,.png,.pdf" required>
                    <img id="preview" style="display:none;margin-top:10px;max-width:100%;border:1px solid #ccc;border-radius:8px;">
                    @error('proof') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">📤 Submit Proof</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function(){

    // Success alert
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: '✅ Payment Submitted!',
        text: "{{ addslashes(session('success')) }}",
        confirmButtonColor: '#3085d6',
        confirmButtonText: 'OK'
    });
    @endif

    // Error alert
    @if($errors->any())
    Swal.fire({
        icon: 'error',
        title: '⚠️ Submission Failed',
        html: `{!! implode('<br>', $errors->all()) !!}`,
        confirmButtonText: 'OK'
    });
    @endif

    // Preview image
    const proofInput = document.getElementById('proof');
    const preview = document.getElementById('preview');

    proofInput.addEventListener('change', function(e){
        const file = e.target.files[0];
        if(!file){ preview.style.display='none'; return; }

        if(file.type.startsWith('image/')){
            if(file.size > 5*1024*1024){
                Swal.fire({icon:'error', title:'File too large', text:'Max 5MB allowed.'});
                e.target.value = '';
                preview.style.display='none';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(evt){
                preview.src = evt.target.result;
                preview.style.display='block';
            }
            reader.readAsDataURL(file);
        } else {
            preview.style.display='none';
        }
    });

    // Confirm before submit
    document.getElementById('uploadForm').addEventListener('submit', function(e){
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "Double-check your payment details before submitting.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'Yes, submit it!',
            cancelButtonText: 'Cancel'
        }).then((result)=>{
            if(result.isConfirmed){
                e.target.submit();
            }
        });
    });

});
</script>
@endsection
