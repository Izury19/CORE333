@extends('layouts.maintenance')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">💳 Manage Payments</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Payment ID</th>
                <th>Invoice</th>
                <th>Client Name</th>
                <th>Client Email</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Status</th>
                <th>Date Paid</th>
                <th>Proof</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @forelse($payments as $payment)
            <tr>
                <td>{{ $payment->payments_id }}</td>
                <td>#{{ $payment->invoice?->invoice_id ?? 'N/A' }}</td>
                <td>{{ $payment->invoice?->client_name ?? 'N/A' }}</td>
                <td>{{ $payment->invoice?->client_email ?? 'N/A' }}</td>
                <td>₱{{ number_format($payment->amount, 2) }}</td>
                <td>{{ ucfirst($payment->payment_method ?? 'N/A') }}</td>
                <td>
                    @if($payment->status == 'pending')
                        <span class="badge bg-warning text-dark">Pending</span>
                    @elseif($payment->status == 'approved')
                        <span class="badge bg-success">Approved</span>
                    @else
                        <span class="badge bg-danger">Rejected</span>
                    @endif
                </td>
                <td>{{ $payment->payment_date ?? '-' }}</td>
                <td>
                    @if($payment->proof)
                        <a href="{{ asset('storage/' . $payment->proof) }}" target="_blank" class="btn btn-sm btn-info">
                            View Proof
                        </a>
                    @else
                        <span class="text-muted">No proof</span>
                    @endif
                </td>
                <td>
                    @if($payment->status == 'pending')
                        <button onclick="confirmAction('{{ route('payments.approve', ['id' => $payment->payments_id]) }}', 'approve')" class="btn btn-sm btn-success">Approve</button>
                        <button onclick="confirmAction('{{ route('payments.reject', ['id' => $payment->payments_id]) }}', 'reject')" class="btn btn-sm btn-danger">Reject</button>
                    @elseif($payment->status == 'approved')
                        <button onclick="confirmAction('{{ route('payments.cancel', ['id' => $payment->payments_id]) }}', 'cancel')" class="btn btn-sm btn-warning">Cancel</button>
                    @else
                        <em>No actions</em>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-center">No payments yet.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmAction(url, actionType) {
    let title = '';
    let confirmButtonText = '';
    let method = "PATCH";

    if(actionType === 'approve') {
        title = "Approve Payment?";
        confirmButtonText = "Yes, approve it!";
    } else if(actionType === 'reject') {
        title = "Reject Payment?";
        confirmButtonText = "Yes, reject it!";
    } else if(actionType === 'cancel') {
        title = "Cancel Approval?";
        confirmButtonText = "Yes, cancel it!";
    }

    Swal.fire({
        title: title,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: confirmButtonText,
        cancelButtonText: "Cancel"
    }).then((result) => {
        if (result.isConfirmed) {
            let form = document.createElement('form');
            form.method = "POST";
            form.action = url;
            form.innerHTML = `
                @csrf
                @method('${method}')
            `;
            document.body.appendChild(form);
            form.submit();
        }
    });
}

@if(session('success'))
Swal.fire({
    icon: 'success',
    title: 'Success!',
    text: "{{ session('success') }}",
    timer: 2000,
    showConfirmButton: false
});
@endif

@if(session('error'))
Swal.fire({
    icon: 'error',
    title: 'Error!',
    text: "{{ session('error') }}",
    timer: 2000,
    showConfirmButton: false
});
@endif
</script>
@endsection
