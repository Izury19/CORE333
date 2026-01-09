@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto p-6">
    <div class="bg-white border rounded-lg shadow-sm p-6">
        <!-- Header -->
        <div class="flex justify-between items-start mb-6">
            <div>
                <h2 class="text-xl font-bold text-gray-800">CaliCrane Billing</h2>
                <p class="text-gray-600">Official Invoice</p>
            </div>
            <div class="text-right">
                <h3 class="text-xl font-bold">{{ $invoice->invoice_uid }}</h3>
                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                    @if($invoice->status == 'paid') bg-green-100 text-green-800
                    @elseif($invoice->status == 'overdue') bg-red-100 text-red-800
                    @else bg-blue-100 text-blue-800
                    @endif">
                    {{ ucfirst($invoice->status) }}
                </span>
            </div>
        </div>

        <!-- Client & Billing Info -->
        <div class="grid grid-cols-2 gap-6 mb-8">
            <div>
                <h4 class="font-semibold text-gray-700">Billed To:</h4>
                <p class="font-medium">{{ $invoice->client_name }}</p>
            </div>
            <div class="text-right">
                <h4 class="font-semibold text-gray-700">Billing Period:</h4>
                <p>{{ \Carbon\Carbon::parse($invoice->billing_period_start)->format('M d, Y') }} – 
                   {{ \Carbon\Carbon::parse($invoice->billing_period_end)->format('M d, Y') }}</p>
            </div>
        </div>

        <!-- Invoice Table -->
        <div class="mb-8">
            <table class="w-full border-collapse">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="text-left py-2 text-gray-700">Description</th>
                        <th class="text-right py-2 text-gray-700">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b border-gray-100">
                        <td class="py-3">
                            {{ ucfirst($invoice->equipment_type) }} Rental<br>
                            <span class="text-gray-500 text-sm">
                                {{ $invoice->hours_used }} hours @ ₱{{ number_format($invoice->hourly_rate, 2) }}/hr
                            </span>
                        </td>
                        <td class="text-right py-3 font-medium">₱{{ number_format($invoice->total_amount, 2) }}</td>
                    </tr>
                </tbody>
                <tfoot class="font-bold">
                    <tr>
                        <td class="pt-4">TOTAL</td>
                        <td class="pt-4 text-right">₱{{ number_format($invoice->total_amount, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Late Fee (if overdue) -->
        @if($invoice->status == 'overdue')
            @php
                $daysOverdue = now()->diffInDays(\Carbon\Carbon::parse($invoice->billing_period_end));
                $lateFee = $invoice->total_amount * 0.05 * floor($daysOverdue / 7);
                $totalWithLate = $invoice->total_amount + $lateFee;
            @endphp
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="font-semibold text-red-800">⚠️ Overdue by {{ $daysOverdue }} day(s)</p>
                <p class="text-sm">Late fee (5% per week): <strong>₱{{ number_format($lateFee, 2) }}</strong></p>
                <p class="font-bold text-red-900">Total Due: ₱{{ number_format($totalWithLate, 2) }}</p>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex gap-3">
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md flex items-center">
                🖨️ Print Invoice
            </button>

            <a href="{{ route('invoices.pdf', $invoice->id) }}" class="bg-red-600 text-white px-4 py-2 rounded">
    📄 Download PDF
</a>
            <a href="{{ route('invoices.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md">
                ← Back to List
            </a>
        </div>
    </div>
</div>
@endsection

<!-- Print Styles -->
<style>
    @media print {
        body * { 
            visibility: hidden; 
        }
        .bg-white, .bg-white * { 
            visibility: visible; 
        }
        .bg-white { 
            position: absolute; 
            left: 0; 
            top: 0; 
            width: 100%; 
            padding: 20px;
        }
        .flex { display: block !important; }
    }
</style>