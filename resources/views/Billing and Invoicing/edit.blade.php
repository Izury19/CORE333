@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f4f6f8;
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

    .flex-row > div { flex: 1; }

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

    #items-table input.form-control { padding: 6px 8px; font-size: 13px; }
    #items-table td:nth-child(2) input,
    #items-table td:nth-child(3) input,
    #items-table td:nth-child(4) input { width: 80px; }

    .btn { border-radius: 6px; padding: 8px 14px; font-size: 14px; transition: all 0.2s ease-in-out; }
    .btn-primary { background-color: #007bff; border: none; color: #fff; }
    .btn-primary:hover { background-color: #0056b3; }
    .btn-outline-primary { border: 1px solid #007bff; background: transparent; color: #007bff; }
    .btn-outline-primary:hover { background-color: #007bff; color: #fff; }
    .btn-outline-secondary { background-color: #007bff; border: none; color: #fff; }
    .btn-outline-secondary:hover { background-color: #0056b3; }
    .btn-danger { background-color: #dc3545; color: #fff; border: none; }
    .btn-danger:hover { background-color: #c82333; }

    input[readonly] { background-color: #f8f9fa; }

    .alert { padding: 15px; border-radius: 6px; margin-bottom: 20px; }
    .alert-success { background-color: #d4edda; color: #155724; }

    .text-end { text-align: right; }

    #payment-details {
        background-color: #eef4fb;
        border-left: 4px solid #007bff;
        padding: 10px;
        font-size: 13.5px;
        color: #333;
        margin-top: 5px;
    }

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

            <!-- Payment -->
            <div class="flex-row">
                <div>
                    <label>Terms of Payment</label>
                    <select name="terms_of_payment" class="form-control" id="terms-select" required>
                        <option value="">Select Payment Method</option>
                        <option value="Bank Transfer" {{ $invoice->terms_of_payment == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        <option value="GCash" {{ $invoice->terms_of_payment == 'GCash' ? 'selected' : '' }}>GCash</option>
                        <option value="Paypal" {{ $invoice->terms_of_payment == 'Paypal' ? 'selected' : '' }}>Paypal</option>
                        <option value="Cash" {{ $invoice->terms_of_payment == 'Cash' ? 'selected' : '' }}>Cash</option>
                    </select>
                </div>
                <div>
                    <label>Payment Details</label>
                    <div id="payment-details" class="form-control">{!! $invoice->payment_details ?? 'Select a payment method to see details.' !!}</div>
                </div>
            </div>

            <div>
                <label>Address</label>
                <textarea name="client_address" rows="3" class="form-control">{{ $invoice->client_address }}</textarea>
            </div>

            <div class="flex-row">
                <div style="flex:1;">
                    <label>Note</label>
                    <textarea class="form-control" name="note" rows="3">{{ $invoice->note }}</textarea>
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

    // Add row
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

    // Remove row
    document.querySelectorAll('.remove-row').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.target.closest('tr').remove();
            updateTotals();
        });
    });

    // Update totals when qty/price change
    document.querySelectorAll('.qty, .price').forEach(input => {
        input.addEventListener('input', updateTotals);
    });

    // Alert auto-hide
    document.addEventListener("DOMContentLoaded", function() {
        let alertBox = document.querySelector(".alert-success");
        if (alertBox) {
            setTimeout(() => {
                alertBox.style.transition = "opacity 0.5s ease";
                alertBox.style.opacity = "0";
                setTimeout(() => alertBox.remove(), 500);
            }, 3000);
        }

        // Payment details dynamic
        const paymentDetails = {
            "Bank Transfer": "Bank: BDO<br>Account Number: 1234-5678-9012<br>Account Name: Juan Dela Cruz",
            "GCash": "GCash Number: 0917-123-4567<br>Account Name: Maria Santos",
            "Paypal": "Paypal Email: yourname@paypal.com",
            "Cash": "Please prepare exact amount upon delivery or pickup."
        };

        const termsSelect = document.getElementById('terms-select');
        const paymentDetailsDiv = document.getElementById('payment-details');

        termsSelect.addEventListener('change', function () {
            const selected = this.value;
            paymentDetailsDiv.innerHTML = paymentDetails[selected] || 'Select a payment method to see details.';
        });
    });
</script>
@endsection
