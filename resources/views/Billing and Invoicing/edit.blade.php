@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #2e2e2e;
        font-family: 'Segoe UI', sans-serif;
        padding: 0;
    }
    .invoice-box {
        width: 100%;
        max-width: 800px;
        margin-left: auto;
        margin-right: auto;
        padding: 40px;
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }
    .invoice-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: calc(100vh - 100px);
        padding: 40px 20px;
        padding-right: 250px;
    }

    h2, h3 {
        font-weight: 600;
        margin-bottom: 20px;
    }

    .flex-row {
        display: flex;
        gap: 20px;
        margin-bottom: 15px;
    }

    .flex-row > div {
        flex: 1;
    }

    .form-control {
        border-radius: 6px;
        padding: 10px;
        border: 1px solid #ccc;
        width: 100%;
        box-sizing: border-box;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        font-size: 15px;
    }

    table th {
        background-color: #007bff;
        color: #fff;
        text-align: left;
        padding: 12px;
        border: none;
    }

    table td {
        padding: 8px 10px;
        border: 1px solid #ddd;
        vertical-align: middle;
    }

    #items-table input.form-control {
        padding: 6px 8px;
        font-size: 13px;
    }

    #items-table td:nth-child(2) input,
    #items-table td:nth-child(3) input,
    #items-table td:nth-child(4) input {
        width: 80px;
    }

    .btn {
        border-radius: 6px;
        padding: 8px 14px;
        font-size: 14px;
        transition: all 0.2s ease-in-out;
    }

    .btn-primary {
        background-color: #007bff;
        border: none;
        color: #fff;
    }
    .btn-primary:hover { background-color: #0056b3; }

    .btn-outline-primary {
        border: 1px solid #007bff;
        background: transparent;
        color: #007bff;
    }
    .btn-outline-primary:hover { background-color: #007bff; color: #fff; }

    .btn-outline-secondary {
        border: 1px solid #6c757d;
        background: transparent;
        color: #6c757d;
    }
    .btn-outline-secondary:hover { background-color: #6c757d; color: #fff; }

    .btn-danger {
        background-color: #dc3545;
        color: #fff;
        border: none;
    }
    .btn-danger:hover { background-color: #c82333; }

    input[readonly] { background-color: #f8f9fa; }

    .alert {
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }

    .text-end { text-align: right; }

    @media (max-width: 768px) {
        .invoice-box { padding: 20px; }
        table th, table td { font-size: 13px; }
        .flex-row { flex-direction: column; }
    }
</style>

<div class="invoice-wrapper">
    <div class="invoice-box">
        @if (session('success'))
            <div class="alert alert-success mt-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('invoices.update', $invoice->invoice_id) }}" method="POST">
            @csrf
            @method('PUT')

            <h2 class="text-4xl font-bold">Edit Invoice</h2>

            <div class="flex-row">
                <div>
                    <label>Client Name</label>
                    <input type="text" class="form-control" name="client_name" value="{{ $invoice->client_name }}" required>
                </div>
                <div>
                    <label>Client Email</label>
                    <input type="email" class="form-control" name="client_email" value="{{ $invoice->client_email }}" required>
                </div>
            </div>

            <div class="flex-row">
                <div>
                    <label>Invoice Date</label>
                    <input type="date" class="form-control" name="invoice_date" value="{{ $invoice->invoice_date }}" required>
                </div>
                <div>
                    <label>Due Date</label>
                    <input type="date" class="form-control" name="due_date" value="{{ $invoice->due_date }}" required>
                </div>
            </div>

            <table id="items-table">
                <thead>
                    <tr>
                        <th>Description</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>🛠</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $index => $item)
                        <tr>
                            <td><input type="text" name="items[{{ $index }}][description]" value="{{ $item->description }}" class="form-control" required></td>
                            <td><input type="number" name="items[{{ $index }}][qty]" value="{{ $item->qty }}" class="form-control qty" required></td>
                            <td><input type="number" name="items[{{ $index }}][price]" value="{{ $item->price }}" class="form-control price" required></td>
                            <td><input type="number" name="items[{{ $index }}][total]" value="{{ $item->total }}" class="form-control total" readonly></td>
                            <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <button type="button" class="btn btn-sm btn-outline-primary btn-add-row mt-3">+ Add Item</button>

            <div class="mt-4">
                <label>Subtotal:</label>
                <input type="text" id="subtotal" value="{{ $invoice->subtotal }}" class="form-control" readonly>

                <label>Tax (15%):</label>
                <input type="text" id="tax" value="{{ $invoice->tax }}" class="form-control" readonly>

                <label>Total:</label>
                <input type="text" name="total" id="grand-total" value="{{ $invoice->total }}" class="form-control" readonly>
            </div>

            <div class="mt-4 flex gap-2">
                <button class="btn btn-primary" type="submit">💾 Update Invoice</button>
                <a href="{{ route('invoices.index') }}" class="btn btn-outline-secondary">⬅ Back</a>
            </div>
        </form>
    </div>
</div>

<script>
    let rowCount = {{ count($invoice->items) }};

    function updateTotals() {
        let subtotal = 0;
        document.querySelectorAll("#items-table tbody tr").forEach(row => {
            const qty = parseFloat(row.querySelector('.qty').value) || 0;
            const price = parseFloat(row.querySelector('.price').value) || 0;
            const total = qty * price;
            row.querySelector('.total').value = total.toFixed(2);
            subtotal += total;
        });

        const tax = subtotal * 0.15;
        const grandTotal = subtotal + tax;

        document.getElementById('subtotal').value = subtotal.toFixed(2);
        document.getElementById('tax').value = tax.toFixed(2);
        document.getElementById('grand-total').value = grandTotal.toFixed(2);
    }

    document.querySelectorAll('.qty, .price').forEach(input => {
        input.addEventListener('input', updateTotals);
    });

    document.querySelector('.btn-add-row').addEventListener('click', () => {
        const table = document.querySelector('#items-table tbody');
        const newRow = document.createElement('tr');

        newRow.innerHTML = `
            <td><input type="text" name="items[${rowCount}][description]" class="form-control" required></td>
            <td><input type="number" name="items[${rowCount}][qty]" class="form-control qty" required></td>
            <td><input type="number" name="items[${rowCount}][price]" class="form-control price" required></td>
            <td><input type="number" name="items[${rowCount}][total]" class="form-control total" readonly></td>
            <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
        `;

        table.appendChild(newRow);
        rowCount++;

        newRow.querySelectorAll('.qty, .price').forEach(input => {
            input.addEventListener('input', updateTotals);
        });

        newRow.querySelector('.remove-row').addEventListener('click', () => {
            newRow.remove();
            updateTotals();
        });
    });

    document.querySelectorAll('.remove-row').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.target.closest('tr').remove();
            updateTotals();
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let alertBox = document.querySelector(".alert-success");
        if (alertBox) {
            setTimeout(() => {
                alertBox.style.transition = "opacity 0.5s ease";
                alertBox.style.opacity = "0";
                setTimeout(() => alertBox.remove(), 500);
            }, 3000); // 3 seconds
        }
    });
</script>
@endsection
