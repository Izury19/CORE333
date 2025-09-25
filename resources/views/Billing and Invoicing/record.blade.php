@extends('layouts.maintenance')

@section('content')
<style>
    body {
        background-color: #f4f6f8;
        font-family: 'Segoe UI', sans-serif;
        padding: 0;
    }
    .dashboard-container {
        background: #f4f6f9;
        min-height: 100vh;
        padding: 20px;
        padding-top: 5px;
    }
    .dashboard-card {
        background: #fff;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }
    .dashboard-header h2 {
        margin: 0;
        font-weight: bold;
    }
    .back-btn {
        background: #6c757d;
        color: #fff;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
    }
    .back-btn:hover {
        background: #5a6268;
        color: #fff;
    }

    /* receipt styles */
    .receipt-box {
        max-width: 350px;
        margin: 20px auto;
        background: #fff;
        padding: 20px;
        border: 1px dashed #aaa;
        font-size: 13px;
        line-height: 1.5;
        font-family: monospace;
    }
    .receipt-box h3 {
        text-align: center;
        font-size: 16px;
        margin-bottom: 8px;
        text-transform: uppercase;
        border-bottom: 1px dashed #aaa;
        padding-bottom: 4px;
    }
    .receipt-box table {
        width: 100%;
        border-collapse: collapse;
        margin: 8px 0;
        font-size: 12px;
    }
    .receipt-box table th,
    .receipt-box table td {
        padding: 3px 0;
        border-bottom: 1px dashed #ddd;
    }
    .receipt-box tfoot td {
        font-weight: bold;
    }
    .receipt-actions {
        margin-top: 10px;
        text-align: center;
    }
    .receipt-actions button {
        margin: 2px;
    }

    /* Print Styles */
    @media print {
        body * {
            visibility: hidden;
        }
        .printable, .printable * {
            visibility: visible;
        }
        .printable {
            position: absolute;
            left: 0;
            top: 0;
            width: 80mm;
            font-size: 12px;
            border: none !important;
            box-shadow: none !important;
            margin: 0;
            padding: 10px;
        }
        .printable h3 {
            font-size: 14px;
        }
        .printable table th,
        .printable table td {
            font-size: 12px;
        }

        /* 🚫 wag isama ang buttons sa print */
        .printable .receipt-actions {
            display: none !important;
        }
    }
</style>

<div class="dashboard-container">
    <div class="dashboard-card">
        <div class="dashboard-header">
            <h2>🧾 Receipts Records</h2>
            <a href="{{ route('delivery') }}" class="back-btn">⬅ Back to Delivery</a>
        </div>

        @if($records->isEmpty())
            <p>No receipts available.</p>
        @else
            <div class="row">
                @foreach($records as $record)
                    <div class="col-md-4">
                        <div class="receipt-box" id="receipt-{{ $record->record_id }}">
                            {{-- Company Header --}}
                            <div style="text-align:center; margin-bottom:10px;">
                                <h3 style="margin:0; font-size:14px;">Cali-CMS</h3>
                                <p style="margin:0; font-size:11px;">
                                    134 Magsaysay Ext, Quezon City<br>
                                    📞 (02) 123-4567 | ✉ support@calicms.com
                                </p>
                            </div>

                            <h3>Invoice Receipt</h3>

                            <p><strong>Receipt #:</strong> {{ $record->record_id }}</p>
                            <p><strong>Invoice #:</strong> {{ $record->invoice_id }}</p>
                            <p><strong>Client:</strong> {{ $record->client_name }}</p>
                            <p><strong>Email:</strong> {{ $record->client_email ?? 'N/A' }}</p>
                            <p><strong>Address:</strong> {{ $record->client_address ?? 'N/A' }}</p>
                            <p><strong>Payment Method:</strong> {{ $record->payment_method ?? 'N/A' }}</p>
                            <p><strong>Date Issued:</strong> {{ $record->created_at->format('Y-m-d') }}</p>
                            <p><strong>Status:</strong> {{ ucfirst($record->status) }}</p>

                            <table>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Item</th>
                                        <th>Qty</th>
                                        <th>₱</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($record->items as $index => $item)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td>{{ $item->qty }}</td>
                                            <td>{{ number_format($item->qty * $item->price, 2) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3">Total</td>
                                        <td><strong>₱{{ number_format($record->total, 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>

                            @if (!empty($record->note))
                                <p><strong>Note:</strong> {{ $record->note }}</p>
                            @endif

                            {{-- Print & Delete --}}
                            <div class="receipt-actions">
                                <button class="btn btn-sm btn-primary" onclick="printReceipt({{ $record->record_id }})">🖨 Print</button>
                                
                                <form id="delete-form-{{ $record->record_id }}" 
                                      action="{{ route('receipts.destroy', $record->record_id) }}" 
                                      method="POST" 
                                      style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $record->record_id }})">🗑 Delete</button>
                                </form>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $records->links() }}
            </div>
        @endif
    </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<script>
    function printReceipt(id) {
        var receipt = document.getElementById('receipt-' + id);
        receipt.classList.add("printable");
        window.print();
        receipt.classList.remove("printable");
    }

    function confirmDelete(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This receipt will be deleted permanently!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-form-' + id).submit();
            }
        });
    }

    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        position: 'center',
        showClass: {
            popup: 'animate__animated animate__bounceIn'
        },
        hideClass: {
            popup: 'animate__animated animate__fadeOutUp'
        }
    });
    @endif

    @if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '{{ session('error') }}',
        confirmButtonColor: '#d33',
        confirmButtonText: 'OK',
        showClass: {
            popup: 'animate__animated animate__shakeX'
        }
    });
    @endif
</script>
@endsection
