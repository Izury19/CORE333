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
    min-height: calc(100vh - 100px); /* adjust height, minus navbar/footer */
    padding: 40px 20px;
    padding-right: 250px;
    }

    h2, h3 {
        font-weight: 600;
        margin-bottom: 20px;
    }

    /* Flex container for side-by-side inputs */
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

    /* Smaller inputs in table for Qty, Price, Total */
    #items-table input.form-control {
        padding: 6px 8px;
        font-size: 13px;
    }

    #items-table td:nth-child(1) input.form-control {
        width: 100%; /* Description full width */
    }

    #items-table td:nth-child(2) input.form-control,
    #items-table td:nth-child(3) input.form-control,
    #items-table td:nth-child(4) input.form-control {
        width: 80px; /* Qty, Price, Total */
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

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-outline-primary {
        border: 1px solid #007bff;
        background: transparent;
        color: #007bff;
    }

    .btn-outline-primary:hover {
        background-color: #007bff;
        color: #fff;
    }

    .btn-danger {
        background-color: #dc3545;
        color: #fff;
        border: none;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    input[readonly] {
        background-color: #f8f9fa;
    }

    .alert {
        padding: 15px;
        border-radius: 6px;
        margin-bottom: 20px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }

    .text-end {
        text-align: right;
    }

    @media (max-width: 768px) {
        .invoice-box {
            padding: 20px;
        }

        table th, table td {
            font-size: 13px;
        }

        .flex-row {
            flex-direction: column;
        }
    }
</style>
<!-- breadcrumb -->
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
                    <a href="#" class="ml-1 text-sm font-medium text-black text-white hover:text-blue-900 md:ml-2">Invoice Creation</a>
                </div>
                </li>   
            </ol>
        </div>
        <!-- breadcrumb -->

<div class="invoice-wrapper">
<div class="invoice-box" style="margin-top: -40px;">
    @if (session('success'))
        <div class="alert alert-success mt-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('invoice.store') }}" method="POST">
        @csrf

        <h2 class="text-4xl font-bold">Invoice Creation</h2>


        <div class="flex-row">
            <div>
                <label>Client Name</label>
                <input type="text" class="form-control" name="client_name" required>
            </div>
            <div>
                <label>Client Email</label>
                <input type="email" class="form-control" name="client_email" required placeholder="client@example.com">
            </div>
        </div>

        <div class="flex-row">
            <div>
                <label>Invoice Date</label>
                <input type="date" class="form-control" name="invoice_date" required>
            </div>
            <div>
                <label>Due Date</label>
                <input type="date" class="form-control" name="due_date" required>
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
                <tr>
                    <td><input type="text" name="items[0][description]" class="form-control" required></td>
                    <td><input type="number" name="items[0][qty]" class="form-control qty" required></td>
                    <td><input type="number" name="items[0][price]" class="form-control price" required></td>
                    <td><input type="number" name="items[0][total]" class="form-control total" readonly></td>
                    <td><button type="button" class="btn btn-danger btn-sm remove-row">X</button></td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-sm btn-outline-primary btn-add-row mt-3">+ Add Item</button>

        <div class="mt-4">
            <label>Subtotal:</label>
            <input type="text" id="subtotal" class="form-control" readonly>

            <label>Tax (15%):</label>
            <input type="text" id="tax" class="form-control" readonly>

            <label>Total:</label>
            <input type="text" name="total" id="grand-total" class="form-control" readonly>
        </div>

        <button class="btn btn-primary mt-4" type="submit">💾 Save Invoice</button>
    </form>
</div>
</div>
@if (session('invoice'))
<div class="invoice-box mt-5" style="margin-top: 60px; margin-right: 250px;">
    <h3 class="mb-3">🧾 Invoice Preview</h3>

    <p><strong>Client:</strong> {{ session('invoice')['client_name'] }}</p>
    <p><strong>Invoice Date:</strong> {{ session('invoice')['invoice_date'] }}</p>
    <p><strong>Due Date:</strong> {{ session('invoice')['due_date'] }}</p>

    <table class="mt-3">
        <thead>
            <tr>
                <th>#</th>
                <th>Description</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach (session('invoice')['items'] as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item['description'] }}</td>
                    <td>{{ $item['qty'] }}</td>
                    <td>₱{{ number_format($item['price'], 2) }}</td>
                    <td>₱{{ number_format($item['qty'] * $item['price'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="text-end"><strong>Subtotal</strong></td>
                <td>₱{{ number_format(session('invoice')['subtotal'], 2) }}</td>
            </tr>
            <tr>
                <td colspan="4" class="text-end"><strong>Tax (15%)</strong></td>
                <td>₱{{ number_format(session('invoice')['tax'], 2) }}</td>
            </tr>
            <tr>
                <td colspan="4" class="text-end"><strong>Total</strong></td>
                <td><strong>₱{{ number_format(session('invoice')['total'], 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <div class="mt-3 text-end">
        <button class="btn btn-outline-primary" onclick="window.print()">🖨 Print Invoice</button>
    </div>
</div>
@endif

<script>
    let rowCount = 1;

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
@endsection

