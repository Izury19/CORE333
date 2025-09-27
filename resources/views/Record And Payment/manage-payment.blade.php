@extends('layouts.maintenance')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">💳 Manage Payments</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Payment ID</th>
                <th>Invoice</th>
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
                    {{-- Payment ID (primary key) --}}
                    <td>{{ $payment->payment_id }}</td>

                    {{-- Invoice ID (foreign key) --}}
                    <td>#{{ $payment->invoice?->invoice_id ?? 'N/A' }}</td>

                    {{-- Amount --}}
                    <td>₱{{ number_format($payment->amount, 2) }}</td>

                    {{-- Payment Method --}}
                    <td>{{ ucfirst($payment->payment_method ?? 'N/A') }}</td>

                    {{-- Status --}}
                    <td>
                        @if($payment->status == 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($payment->status == 'approved')
                            <span class="badge bg-success">Approved</span>
                        @else
                            <span class="badge bg-danger">Rejected</span>
                        @endif
                    </td>

                    {{-- Date Paid --}}
                    <td>{{ $payment->payment_date ?? '-' }}</td>

                    {{-- Proof link --}}
                    <td>
                        @if($payment->proof)
                            <a href="{{ asset('storage/' . $payment->proof) }}" target="_blank" class="btn btn-sm btn-info">
                                View Proof
                            </a>
                        @else
                            <span class="text-muted">No proof</span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td>
                        @if($payment->status == 'pending')
                            <form action="{{ route('payments.approve', $payment->payment_id) }}" method="POST" style="display:inline-block">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                            </form>
                            <form action="{{ route('payments.reject', $payment->payment_id) }}" method="POST" style="display:inline-block">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                            </form>
                        @else
                            <em>No actions</em>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No payments yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
