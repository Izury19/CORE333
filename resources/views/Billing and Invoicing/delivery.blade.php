@extends('layouts.app')

@section('content')

<style>
    /* Body and page background */
    body {
        background-color: #2e2e2e;
        font-family: 'Segoe UI', sans-serif;
        padding: 0;
    }

    /* Main container */
    .container {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        margin-top: 30px;

        /* ✅ Pinalawak na width para magkasya ang table */
        max-width: 1300px;

        /* ✅ Centered na layout */
        margin-left: auto;
        margin-right: auto;

        /* ❌ Removed scroll (overflow-x removed) */
    }

    /* Dashboard title styling */
    h2 {
        font-weight: 700;
        color: #2d3748;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;

        /* Still using fixed layout for controlled columns */
        table-layout: fixed;
        font-size: 13px;
    }

    th, td {
        text-align: left;
        padding: 8px 10px;
        border: 1px solid #ddd;
        word-wrap: break-word;
        overflow: hidden;
    }

    th {
        background-color: #007bff;
        color: white;
    }

    tr:nth-child(even) {
        background-color: #f2f2f2;
    }

    tr:hover {
        background-color: #e2e6ea;
    }

    td strong {
        color: #1e7e34;
    }

    /* Fixed column widths */
    th.client, td.client {
        width: 180px;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    th.email, td.email {
        width: 220px;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    th.description, td.description {
        width: 140px;
        white-space: normal;
        word-wrap: break-word;
    }

    th.qty, td.qty {
        width: 40px;
        white-space: nowrap;
    }

    th.invoice-date, td.invoice-date,
    th.due-date, td.due-date {
        width: 90px;
        white-space: nowrap;
    }

    th.subtotal, td.subtotal,
    th.tax, td.tax,
    th.total, td.total {
        width: 80px;
        white-space: nowrap;
        text-align: right;
    }

    td.total strong {
        color: #1e7e34;
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
                    <a href="#" class="ml-1 text-sm font-medium text-black text-white hover:text-blue-900 md:ml-2">Invoice Delivery</a>
                </div>
                </li>   
            </ol>
        </div>
        <!-- breadcrumb -->

{{-- ✅ Container is now wider and centered --}}
<div class="container" style="margin-left: -120px">
    <h2 class="mb-4">📦 Invoice Delivery Dashboard</h2>

    {{-- Show success message if available --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Check if there are any invoices --}}
    @if($invoices->isEmpty())
        <p>No invoices available.</p>
    @else
        <table class="table table-bordered" style="margin-left: -13px;">
            <thead>
                <tr>
                    <th class="client">Client</th>
                    <th class="email">Email</th>
                    <th class="description">Description</th>
                    <th class="qty">Qty</th>
                    <th class="invoice-date">Invoice Date</th>
                    <th class="due-date">Due Date</th>
                    <th class="subtotal">Subtotal</th>
                    <th class="tax">Tax</th>
                    <th class="total">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                    @foreach($invoice->items as $item)
                    <tr>
                        <td class="client">{{ $invoice->client_name }}</td>
                        <td class="email">{{ $invoice->client_email }}</td>
                        <td class="description">{{ $item->description }}</td>
                        <td class="qty">{{ $item->qty }}</td>
                        <td class="invoice-date">{{ $invoice->invoice_date }}</td>
                        <td class="due-date">{{ $invoice->due_date }}</td>
                        <td class="subtotal">₱{{ number_format($invoice->subtotal, 2) }}</td>
                        <td class="tax">₱{{ number_format($invoice->tax, 2) }}</td>
                        <td class="total"><strong>₱{{ number_format($invoice->total, 2) }}</strong></td>
                    </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@endsection
