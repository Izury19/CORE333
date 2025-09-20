@extends('layouts.app')

@section('content')

<style>
    body {
        background-color: #fff;
        font-family: 'Segoe UI', sans-serif;
        padding: 0;
    }

    h2 {
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 20px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    /* ✅ Card style for table */
    .table-wrapper {
        padding: 20px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.12);
        margin-left: -250px;
    }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 14px;
        background-color: #fff;
        border-radius: 8px;
        overflow: hidden;
    }

    th {
        background: linear-gradient(135deg, #007bff, #0056b3);
        color: #fff;
        padding: 12px 10px;
        text-align: left;
        font-weight: 600;
    }

    td {
        padding: 10px 12px;
        border-top: 1px solid #eee;
        word-wrap: break-word;
        overflow: hidden;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    tr:hover {
        background-color: #eef5ff;
        transition: 0.2s ease-in-out;
    }

    td strong {
        color: #1e7e34;
    }

    /* Column widths */
    th.client, td.client { width: 150px; white-space: nowrap; text-overflow: ellipsis; }
    th.email, td.email { width: 160px; white-space: nowrap; text-overflow: ellipsis; }
    th.description, td.description { width: 140px; white-space: normal; word-wrap: break-word; }
    th.qty, td.qty { width: 80px; white-space: nowrap; }
    th.invoice-date, td.invoice-date,
    th.due-date, td.due-date { width: 90px; white-space: nowrap; }
    th.subtotal, td.subtotal,
    th.tax, td.tax,
    th.total, td.total {
        width: 80px;
        white-space: nowrap;
        text-align: right;
    }

    /* New status column */
    th.status, td.status {
        width: 90px;
        white-space: nowrap;
        text-align: center;
        font-weight: 600;
        text-transform: capitalize;
    }

    th.actions, td.actions {
        width: 160px;
        text-align: center;
        white-space: nowrap;
    }

    /* Status colors */
    .status-paid {
        color: #28a745; /* green */
        font-weight: 700;
    }
    .status-unpaid {
        color: #fd7e14; /* orange */
        font-weight: 700;
    }
    .status-overdue {
        color: #dc3545; /* red */
        font-weight: 700;
    }

    /* Buttons */
    .btn {
        padding: 6px 12px;
        font-size: 12px;
        border-radius: 4px;
        margin: 2px;
        border: none;
        cursor: pointer;
    }
    .btn-warning { background-color: #ffc107; color: #000; }
    .btn-danger { background-color: #dc3545; color: #fff; }

    /* Modal styles */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-overlay.active {
        display: flex;
    }

    .modal {
        background: #fff;
        padding: 20px 25px;
        border-radius: 8px;
        max-width: 400px;
        width: 100%;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        text-align: center;
    }

    .modal h3 {
        margin-bottom: 15px;
        font-size: 20px;
        color: #333;
    }

    .modal p {
        margin-bottom: 25px;
        font-size: 16px;
        color: #555;
    }

    .modal-buttons {
        display: flex;
        justify-content: center;
        gap: 15px;
    }

    .modal-buttons button {
        padding: 8px 16px;
        font-size: 14px;
        border-radius: 5px;
        border: none;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .modal-buttons .btn-cancel {
        background-color: #6c757d;
        color: white;
    }
    .modal-buttons .btn-cancel:hover {
        background-color: #5a6268;
    }

    .modal-buttons .btn-confirm {
        background-color: #dc3545;
        color: white;
    }
    .modal-buttons .btn-confirm:hover {
        background-color: #c82333;
    }
</style>

<!-- ✅ Breadcrumb section -->
<div class="flex mb-5" aria-label="Breadcrumb" style="justify-content: flex-start; padding-left: 17.5rem;">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="#" class="inline-flex items-center text-sm font-medium text-white hover:text-blue-600">
                <svg class="w-3 h-3 mr-2.5 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                </svg>
                Core 3
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <svg class="w-3 h-3 text-white mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                </svg>
                <a href="#" class="ml-1 text-sm font-medium text-white hover:text-blue-900 md:ml-2">Invoice Delivery</a>
            </div>
        </li>   
    </ol>
</div>

<!-- ✅ Table Card -->
<div class="table-wrapper">
    <h2>📦 Invoice Delivery Dashboard</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($invoices->isEmpty())
        <p>No invoices available.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th class="client">Client</th>
                    <th class="email">Email</th>
                    <th class="description">Description(s)</th>
                    <th class="qty">Total Qty</th>
                    <th class="invoice-date">Invoice Date</th>
                    <th class="due-date">Due Date</th>
                    <th class="subtotal">Subtotal</th>
                    <th class="tax">Tax</th>
                    <th class="total">Total</th>
                    <th class="status">Status</th> <!-- Added Status Column -->
                    <th class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                <tr>
                    <td class="client">{{ $invoice->client_name }}</td>
                    <td class="email">{{ $invoice->client_email }}</td>
                    @php
                        $groupedItems = $invoice->items
                            ->groupBy('description')
                            ->map(fn($group) => $group->sum('qty'));
                    @endphp
                    <td class="description">
                        <ul style="padding-left: 15px; margin: 0;">
                            @foreach($groupedItems as $desc => $qty)
                                <li>{{ $desc }} ({{ $qty }})</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="qty">{{ $groupedItems->sum() }}</td>
                    <td class="invoice-date">{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') }}</td>
                    <td class="due-date">{{ \Carbon\Carbon::parse($invoice->due_date)->format('Y-m-d') }}</td>
                    <td class="subtotal">₱{{ number_format($invoice->subtotal, 2) }}</td>
                    <td class="tax">₱{{ number_format($invoice->tax, 2) }}</td>
                    <td class="total"><strong>₱{{ number_format($invoice->total, 2) }}</strong></td>
                    
                    @php
                        $status = strtolower($invoice->status);
                        $statusClass = match($status) {
                            'paid' => 'status-paid',
                            'pending', 'unpaid' => 'status-unpaid',
                            'overdue' => 'status-overdue',
                            default => '',
                        };
                    @endphp
                    <td class="status {{ $statusClass }}">{{ ucfirst($status) }}</td>

                    <td class="actions">
                        <a href="{{ route('invoices.edit', $invoice->invoice_id) }}" class="btn btn-warning">Edit</a>

                        <!-- Delete button triggers modal -->
                        <button type="button" class="btn btn-danger btn-delete" 
                                data-invoice-id="{{ $invoice->invoice_id }}" 
                                data-client-name="{{ $invoice->client_name }}">
                            Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<!-- Modal Overlay -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal" role="dialog" aria-modal="true" aria-labelledby="modalTitle" aria-describedby="modalDesc">
        <h3 id="modalTitle">Confirm Delete</h3>
        <p id="modalDesc">Are you sure you want to delete the invoice for <strong id="modalClientName"></strong>?</p>
        <div class="modal-buttons">
            <button type="button" class="btn btn-cancel" id="cancelDelete">Cancel</button>
            <form id="deleteForm" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-confirm">Delete</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Auto fade alert success
        let alertBox = document.querySelector(".alert-success");
        if (alertBox) {
            setTimeout(() => {
                alertBox.style.transition = "opacity 0.5s ease";
                alertBox.style.opacity = "0";
                setTimeout(() => alertBox.remove(), 500);
            }, 3000);
        }

        // Modal elements
        const modal = document.getElementById('deleteModal');
        const cancelBtn = document.getElementById('cancelDelete');
        const modalClientName = document.getElementById('modalClientName');
        const deleteForm = document.getElementById('deleteForm');

        // Show modal when any delete button clicked
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                const invoiceId = this.getAttribute('data-invoice-id');
                const clientName = this.getAttribute('data-client-name');

                modalClientName.textContent = clientName;

                // Update form action dynamically
                deleteForm.action = `/invoices/${invoiceId}`;

                // Show modal
                modal.classList.add('active');
            });
        });

        // Cancel button closes modal
        cancelBtn.addEventListener('click', () => {
            modal.classList.remove('active');
        });

        // Clicking outside modal closes modal
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('active');
            }
        });
    });
</script>

@endsection
