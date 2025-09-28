@extends('layouts.app')

@section('content')
<style>
    body {
        background-color: #f4f6f8;
        font-family: 'Segoe UI', sans-serif;
        margin: 0;
        padding: 0;
    }

    .invoice-wrapper {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: calc(100vh - 80px);
        padding: 40px 20px;
        margin-left: -200px;
    }

    .invoice-box {
        width: 100%;
        max-width: 850px;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        padding: 40px;
        transition: transform 0.2s;
    }

    .invoice-box:hover {
        transform: translateY(-2px);
    }

    h2 {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 25px;
    }

    h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 20px;
        color: #34495e;
    }

    /* Flex rows */
    .flex-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .flex-row > div {
        flex: 1;
    }

    label {
        display: block;
        font-weight: 500;
        margin-bottom: 5px;
        color: #34495e;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border-radius: 8px;
        border: 1px solid #d1d8e0;
        font-size: 14px;
        transition: all 0.2s;
    }

    .form-control:focus {
        outline: none;
        border-color: #007bff;
        box-shadow: 0 0 6px rgba(0, 123, 255, 0.2);
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 25px;
        font-size: 14px;
    }

    table th {
        background: linear-gradient(90deg, #007bff, #0056b3);
        color: #fff;
        text-align: left;
        padding: 12px;
        border: none;
        border-radius: 6px 6px 0 0;
    }

    table td {
        padding: 10px;
        border-bottom: 1px solid #e0e0e0;
        vertical-align: middle;
    }

    #items-table input.form-control {
        padding: 6px 8px;
        font-size: 13px;
    }

    #items-table td:nth-child(2),
    #items-table td:nth-child(3),
    #items-table td:nth-child(4) {
        width: 90px;
    }

    .btn {
        border-radius: 8px;
        padding: 10px 18px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        border: none;
    }

    .btn-primary {
        background-color: #007bff;
        color: #fff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
    }

    .btn-outline-primary {
        border: 1px solid #007bff;
        background: transparent;
        color: #007bff;
    }

    .btn-outline-primary:hover {
        background-color: #007bff;
        color: #fff;
        box-shadow: 0 5px 15px rgba(0, 123, 255, 0.2);
    }

    .btn-danger {
        background-color: #dc3545;
        color: #fff;
    }

    .btn-danger:hover {
        background-color: #c82333;
        box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3);
    }

    input[readonly] {
        background-color: #f1f3f6;
    }

    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-weight: 500;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border-left: 6px solid #28a745;
    }

    .text-end {
        text-align: right;
    }

    /* Receipt / Invoice Preview Styling */
    .receipt-box {
        max-width: 400px;
        margin: auto;
        background: #fff;
        padding: 25px 20px;
        border: 1px dashed #aaa;
        font-size: 14px;
        line-height: 1.5;
        font-family: monospace;
    }
    .receipt-box h3 {
        text-align: center;
        font-size: 18px;
        margin-bottom: 10px;
        text-transform: uppercase;
        border-bottom: 1px dashed #aaa;
        padding-bottom: 5px;
    }
    .receipt-box .info {
        margin-bottom: 10px;
    }
    .receipt-box table {
        width: 100%;
        border-collapse: collapse;
        margin: 10px 0;
        font-size: 13px;
    }
    .receipt-box table th,
    .receipt-box table td {
        padding: 4px 0;
        border-bottom: 1px dashed #ddd;
    }
    .receipt-box table th {
        text-align: left;
    }
    .receipt-box tfoot td {
        font-weight: bold;
    }
    .receipt-box .text-center {
        text-align: center;
        margin-top: 10px;
        font-size: 12px;
        color: #555;
    }
</style>


<div class="invoice-wrapper">
<div class="invoice-box" style="margin-top: -40px;">
    @if (session('success'))
        <div id="success-alert" class="alert alert-success mt-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('invoices.store') }}" method="POST">
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
        <!-- New fields: Address, Terms of Payment, Note -->
        <div class="flex-row">
            <div>
                <label>Terms of Payment</label>
                <select name="terms_of_payment" class="form-control" id="terms-select" required>
                    <option value="">Select Payment Method</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="GCash">GCash</option>
                    <option value="Paypal">Paypal</option>
                    <option value="Cash">Cash</option>
                </select>
            </div>
            <div>
                <label>Payment Details</label>
                <div id="payment-details" class="form-control" style="background-color: #eef4fb; border-left: 4px solid #007bff; color: #333; font-size: 13.5px;">
                    Select a payment method to see details.
                </div>
            </div>
        </div>

        <div>
            <label>Address</label>
            <textarea name="client_address" rows="4" placeholder="Enter address here..." class="form-control">Address: 134 Magsaysay Ext, Dona Faustina, San Bartolome, Novaliches, Quezon City</textarea>
        </div>

        <div class="flex-row">
            <div style="flex: 1;">
                <label>Note</label>
                <textarea class="form-control" name="note" rows="3" placeholder="Additional notes or instructions..."></textarea>
            </div>
        </div>

        <table id="items-table">
            <thead>
                <tr>
                    <th>Service / Equipment Details</th>
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
    <div style="background: #ffffff; padding: 30px; border-radius: 12px; box-shadow: 0 0 10px rgba(0,0,0,0.05); font-family: 'Segoe UI', sans-serif;">
        <h2 style="margin-bottom: 10px;">🧾 Hello {{ session('invoice')['client_name'] }},</h2>
        <p style="margin-top: 0;">Thank you for your business! Here's a summary of your invoice:</p>

        <hr style="margin: 20px 0;">

        <p><strong>📅 Invoice Date:</strong> {{ \Carbon\Carbon::parse(session('invoice')['invoice_date'])->format('F d, Y') }}</p>

        <p><strong>📆 Due Date:</strong>
            @if(\Carbon\Carbon::parse(session('invoice')['due_date'])->isPast())
                <span style="color:red;">⚠️ {{ \Carbon\Carbon::parse(session('invoice')['due_date'])->format('F d, Y') }} (Overdue)</span>
            @else
                {{ \Carbon\Carbon::parse(session('invoice')['due_date'])->format('F d, Y') }}
            @endif
        </p>

        <p><strong>🏠 Address:</strong> {{ session('invoice')['client_address'] ?? 'Not provided' }}</p>

        <p><strong>💳 Payment Method:</strong> {{ session('invoice')['terms_of_payment'] }}</p>

        <p><strong>📄 Payment Details:</strong><br>
            @switch(session('invoice')['terms_of_payment'])
                @case('Bank Transfer')
                    🏦 BPI - Account Name: XYZ Corporation, Account No: 1234-5678-9012
                    @break
                @case('GCash')
                    📱 GCash - Account Name: XYZ Corp, GCash Number: 0917-123-4567
                    @break
                @case('Paypal')
                    🌐 Paypal - Email: payments@xyzcorp.com
                    @break
                @case('Cash')
                    💵 Cash payment must be settled at our office: 134 Magsaysay Ext, Quezon City
                    @break
                @default
                    Please contact us for payment details.
            @endswitch
        </p>

        <hr style="margin: 20px 0;">

        <table style="width: 100%; border-collapse: collapse; font-size: 14px; margin-bottom: 20px;">
            <thead>
                <tr style="background-color: #007bff; color: white;">
                    <th style="padding: 10px; text-align: left;">Description</th>
                    <th style="padding: 10px; text-align: left;">Qty</th>
                    <th style="padding: 10px; text-align: left;">Price</th>
                    <th style="padding: 10px; text-align: left;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach (session('invoice')['items'] as $item)
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 8px;">{{ $item['description'] }}</td>
                        <td style="padding: 8px;">{{ $item['qty'] }}</td>
                        <td style="padding: 8px;">₱{{ number_format($item['price'], 2) }}</td>
                        <td style="padding: 8px;">₱{{ number_format($item['qty'] * $item['price'], 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p><strong>🧮 Subtotal:</strong> ₱{{ number_format(session('invoice')['subtotal'], 2) }}</p>
        <p><strong>➕ Tax (15%):</strong> ₱{{ number_format(session('invoice')['tax'], 2) }}</p>
        <p><strong>💰 Total:</strong> <span style="font-size: 1.2em; color: #007bff; font-weight: bold;">₱{{ number_format(session('invoice')['total'], 2) }}</span></p>

        @if (!empty(session('invoice')['note']))
            <hr>
            <p><strong>📝 Note:</strong><br> {{ session('invoice')['note'] }}</p>
        @endif

        <div style="margin-top: 30px; text-align: center;">
            <a href="{{ route('payments.upload', session('invoice')['invoice_id']) }}"
                style="background-color: #007bff; color: white; padding: 12px 20px; border-radius: 8px; text-decoration: none; display: inline-block;">
                📤 Upload Proof of Payment
            </a>
        </div>

        <p style="text-align: center; font-size: 12px; color: #999; margin-top: 20px;">
            This is a preview of the invoice email your client will receive.
        </p>
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
<script>
    // Auto hide success alert after 5 seconds
    const successAlert = document.getElementById('success-alert');
    if (successAlert) {
        setTimeout(() => {
            successAlert.style.transition = 'opacity 0.5s';
            successAlert.style.opacity = '0';
            setTimeout(() => successAlert.remove(), 500);
        }, 5000);
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const paymentSelect = document.getElementById('terms-select');
        const paymentDetailsDiv = document.getElementById('payment-details');

        const paymentDetailsMap = {
            "Bank Transfer": "Bank: BDO<br>Account Number: 1234-5678-9012<br>Account Name: Juan Dela Cruz",
            "GCash": "GCash Number: 0917-123-4567<br>Account Name: Maria Santos",
            "Paypal": "Paypal Email: yourname@paypal.com",
            "Cash": "Cash payment must be settled at our office: 134 Magsaysay Ext, Quezon City"
        };

        paymentSelect.addEventListener('change', function () {
            const selected = this.value;
            paymentDetailsDiv.innerHTML = paymentDetailsMap[selected] || 'Select a payment method to see details.';
        });
    });
</script>

@endsection
