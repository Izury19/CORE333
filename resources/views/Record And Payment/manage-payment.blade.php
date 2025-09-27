@extends('layouts.maintenance')

@section('content')
<div class="container-fluid mt-4">

    <div class="card shadow-lg border-0 rounded-4 w-100">
        <div class="card-header bg-primary text-white rounded-top-4">
            <h4 class="mb-0"><i class="bi bi-credit-card me-2"></i> Manage Payments</h4>
        </div>

        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle text-center">
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
                            <td><span class="badge bg-secondary">#{{ $payment->invoice?->invoice_id ?? 'N/A' }}</span></td>
                            <td>{{ $payment->invoice?->client_name ?? 'N/A' }}</td>
                            <td>{{ $payment->invoice?->client_email ?? 'N/A' }}</td>
                            <td><strong class="text-success">₱{{ number_format($payment->amount, 2) }}</strong></td>
                            <td><span class="badge bg-info text-dark">{{ ucfirst($payment->payment_method ?? 'N/A') }}</span></td>
                            <td>
                                @if($payment->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($payment->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($payment->status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>{{ $payment->payment_date ?? '-' }}</td>
                            <td>
                                @if($payment->proof)
                                    <a href="{{ asset('storage/' . $payment->proof) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                @else
                                    <span class="text-muted">No proof</span>
                                @endif
                            </td>
                            <td>
                                @if($payment->status === 'pending')
                                    <button onclick="confirmAction('{{ route('payments.approve', ['id' => $payment->payments_id]) }}', 'approve')" class="btn btn-sm btn-success me-1 mb-1">
                                        <i class="bi bi-check-circle"></i> Approve
                                    </button>
                                    <button onclick="confirmAction('{{ route('payments.reject', ['id' => $payment->payments_id]) }}', 'reject')" class="btn btn-sm btn-danger me-1 mb-1">
                                        <i class="bi bi-x-circle"></i> Reject
                                    </button>
                                @elseif($payment->status === 'approved')
                                    <button onclick="confirmAction('{{ route('payments.cancel', ['id' => $payment->payments_id]) }}', 'cancel')" class="btn btn-sm btn-warning me-1 mb-1">
                                        <i class="bi bi-arrow-counterclockwise"></i> Cancel
                                    </button>
                                @elseif($payment->status === 'rejected')
                                    <button onclick="confirmAction('{{ route('payments.cancel', ['id' => $payment->payments_id]) }}', 'cancel')" class="btn btn-sm btn-secondary me-1 mb-1">
                                        <i class="bi bi-arrow-counterclockwise"></i> Undo
                                    </button>
                                @else
                                    <em>No actions</em>
                                @endif

                                <!-- DELETE BUTTON -->
                                <button onclick="confirmAction('{{ route('payments.destroy', ['id' => $payment->payments_id]) }}', 'delete')" class="btn btn-sm btn-outline-danger mb-1">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">No payments yet.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmAction(url, actionType) {
    let title = '';
    let confirmButtonText = '';
    let method = "PATCH";
    let icon = 'question';

    if (actionType === 'approve') {
        title = "Approve Payment?";
        confirmButtonText = "Yes, approve it!";
    } else if (actionType === 'reject') {
        title = "Reject Payment?";
        confirmButtonText = "Yes, reject it!";
    } else if (actionType === 'cancel') {
        title = "Revert Status to Pending?";
        confirmButtonText = "Yes, revert it!";
    } else if (actionType === 'delete') {
        title = "Delete Payment Record?";
        confirmButtonText = "Yes, delete it!";
        method = "DELETE";
        icon = 'warning';
    }

    Swal.fire({
        title: title,
        icon: icon,
        showCancelButton: true,
        confirmButtonText: confirmButtonText,
        cancelButtonText: "Cancel",
        customClass: {
            confirmButton: actionType === 'delete' ? 'btn btn-danger me-2' : 'btn btn-primary me-2',
            cancelButton: 'btn btn-secondary'
        },
        buttonsStyling: false
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

<style>
.table {
    font-size: 15px;
    white-space: nowrap;
}
.table th, .table td {
    padding: 14px 12px;
    vertical-align: middle;
}
</style>
@endsection
