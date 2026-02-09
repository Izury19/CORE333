@extends('layouts.app')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Record & Payment Management</h1>
            <p class="text-gray-600 mt-1">Central hub for issued bills and payment tracking</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            @php
                $paymentCount = isset($total) ? $total : 0;
            @endphp
            <div class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 text-sm font-medium">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                </svg>
                {{ $paymentCount }} Transactions
            </div>
            
            <!-- Record Payment Button -->
            <button type="button" onclick="openRecordPaymentModal()"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Record Payment
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Total Revenue Received -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Total Revenue Received</h3>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($totalReceived ?? 0, 2) }}</p>
                    <p class="text-xs text-green-600 mt-1">From crane and truck rental services</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Payment Collection Rate -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Payment Collection Rate</h3>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $collectionRate ?? 0 }}%
                    </p>
                    <p class="text-xs text-gray-600 mt-1">
                        Of issued bills collected
                    </p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>  

    <!-- Compact Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <form id="filterForm" method="GET" class="flex flex-col sm:flex-row gap-3">
            <!-- Search -->
            <div class="flex-1">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search by client, invoice ID..."
                           class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           onkeyup="applyFilters()">
                </div>
            </div>
            
            <!-- Status Filter -->
            <div class="sm:w-48">
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="applyFilters()">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            
            <!-- Date Range -->
            <div class="sm:w-48">
                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="applyFilters()">
            </div>
            
            <div class="sm:w-48">
                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="applyFilters()">
            </div>
            
            <!-- Export Buttons -->
            <div class="flex gap-2">
                <button type="button" class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Excel
                </button>
                <button type="button" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    PDF
                </button>
            </div>
        </form>
    </div>

    <!-- PAYMENT TRANSACTIONS TABLE -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Issued Bills & Payment Status</h2>
            <p class="text-sm text-gray-600 mt-1">All invoices sent from Billing & Invoicing Module with payment status</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Invoice ID
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Client
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Equipment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Amount
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Payment Method</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reference No.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $payment->invoice_uid ?? 'INV-' . str_pad($payment->invoice_id, 3, '0', STR_PAD_LEFT) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $payment->client_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            @if($payment->equipment_type)
                                @php
                                    $equipmentDisplay = [
                                        'tower_crane' => ' Tower Crane',
                                        'mobile_crane' => ' Mobile Crane',
                                        'rough_terrain_crane' => ' Rough Terrain Crane',
                                        'crawler_crane' => ' Crawler Crane',
                                        'dump_truck' => ' Dump Truck',
                                        'concrete_mixer' => ' Concrete Mixer',
                                        'flatbed_truck' => ' Flatbed Truck',
                                        'tanker_truck' => ' Tanker Truck'
                                    ];
                                @endphp
                                {{ $equipmentDisplay[$payment->equipment_type] ?? ucfirst(str_replace('_', ' ', $payment->equipment_type)) }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            @if($payment->payment_status === null)
                                <span class="text-gray-500">₱{{ number_format($payment->total_amount, 2) }} (Pending)</span>
                            @else
                                <span class="text-green-600">₱{{ number_format($payment->amount_paid, 2) }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            @if($payment->payment_status === null)
                                <span class="text-gray-500">Not Paid Yet</span>
                            @elseif($payment->payment_method == 'bank')
                                <span class="flex items-center text-blue-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Bank Transfer
                                </span>
                            @elseif($payment->payment_method == 'check')
                                <span class="flex items-center text-gray-700">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Check
                                </span>
                            @elseif($payment->payment_method == 'gcash')
                                <span class="flex items-center text-green-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    GCash
                                </span>
                            @elseif($payment->payment_method == 'cash')
                                <span class="flex items-center text-amber-600">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Cash
                                </span>
                            @else
                                <span class="text-gray-500">Not Paid Yet</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $payment->reference_number ?? '—' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            @if($payment->payment_date)
                                {{ \Carbon\Carbon::parse($payment->payment_date)->format('Y-m-d') }}
                            @else
                                —
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($payment->payment_status === null)
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pending Payment
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                                    Completed
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No issued bills yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Issued bills will be automatically synced from Billing & Invoicing Module</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-700">Items per page:</span>
                <select id="itemsPerPage" class="border border-gray-300 rounded px-2 py-1 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        onchange="changeItemsPerPage()">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
                <span class="text-sm text-gray-700">
                    {{ ($page - 1) * $perPage + 1 }} to {{ min($page * $perPage, $total) }} of {{ $total }}
                </span>
            </div>

            <div class="flex items-center space-x-2">
                <!-- First Page -->
                <button type="button" 
                        onclick="goToPage(1)"
                        class="p-1.5 rounded border border-gray-300 hover:bg-gray-50 disabled:opacity-50"
                        {{ $page <= 1 ? 'disabled' : '' }}>
                    <<
                </button>
                
                <!-- Previous Page -->
                <button type="button" 
                        onclick="goToPage({{ $page - 1 }})"
                        class="p-1.5 rounded border border-gray-300 hover:bg-gray-50 disabled:opacity-50"
                        {{ $page <= 1 ? 'disabled' : '' }}>
                    <
                </button>
                
                <!-- Next Page -->
                <button type="button" 
                        onclick="goToPage({{ $page + 1 }})"
                        class="p-1.5 rounded border border-gray-300 hover:bg-gray-50 disabled:opacity-50"
                        {{ $page * $perPage >= $total ? 'disabled' : '' }}>
                    >
                </button>
                
                <!-- Last Page -->
                <button type="button" 
                        onclick="goToPage({{ ceil($total / $perPage) }})"
                        class="p-1.5 rounded border border-gray-300 hover:bg-gray-50 disabled:opacity-50"
                        {{ $page * $perPage >= $total ? 'disabled' : '' }}>
                    >>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Record Payment Modal -->
<div id="recordPaymentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
        <!-- Modal Header -->
        <div class="px-6 py-5 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold">Record Client Payment</h3>
                    <p class="text-blue-100 text-sm mt-1">Link payment to an issued invoice</p>
                </div>
                <button type="button" onclick="closeRecordPaymentModal()" class="text-white hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form action="{{ route('record.store') }}" method="POST">
                @csrf

                <!-- Select Invoice -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Invoice</label>
                    <select name="invoice_id" required 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg appearance-none bg-white"
                            id="invoiceSelect">
                        <option value="">Choose an unpaid invoice</option>
                        @foreach(($unpaidInvoices ?? collect()) as $invoice)
                            <option value="{{ $invoice->id }}" data-amount="{{ $invoice->total_amount }}">
                                {{ $invoice->invoice_uid ?? 'INV-' . str_pad($invoice->id, 3, '0', STR_PAD_LEFT) }}
                                - {{ $invoice->client_name }}
                                (₱{{ number_format($invoice->total_amount, 2) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Payment Mode & Amount -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Payment Mode</label>
                        <select name="payment_mode" required 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg appearance-none bg-white">
                            <option value="">Select method</option>
                            <option value="cash">Cash</option>
                            <option value="bank">Bank Transfer</option>
                            <option value="gcash">GCash</option>
                            <option value="check">Check</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Amount</label>
                        <input type="number" name="amount_paid" step="0.01" min="0" readonly
                               id="paymentAmount"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-lg font-semibold text-gray-900">
                    </div>
                </div>

                <!-- Reference Number -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reference Number</label>
                    <input type="text" name="reference_number" required
                           placeholder="OR #, Check #, or Transaction ID"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg">
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeRecordPaymentModal()" 
                            class="px-5 py-3 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-5 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-medium rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all shadow-md hover:shadow-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Record Payment
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openRecordPaymentModal() {
    document.getElementById('recordPaymentModal').classList.remove('hidden');
}

function closeRecordPaymentModal() {
    document.getElementById('recordPaymentModal').classList.add('hidden');
}

// Auto-fill amount when invoice is selected
document.getElementById('invoiceSelect').addEventListener('change', function() {
    const invoiceId = this.value;
    if (invoiceId) {
        const selectedOption = this.options[this.selectedIndex];
        const amount = selectedOption.getAttribute('data-amount');
        document.getElementById('paymentAmount').value = amount;
    } else {
        document.getElementById('paymentAmount').value = '';
    }
});

// Live search and filtering
function applyFilters() {
    document.getElementById('filterForm').submit();
}

// Pagination functions
function goToPage(page) {
    const url = new URL(window.location);
    url.searchParams.set('page', page);
    window.location.href = url.toString();
}

function changeItemsPerPage() {
    const select = document.getElementById('itemsPerPage');
    const url = new URL(window.location);
    url.searchParams.set('page', 1);
    url.searchParams.set('per_page', select.value);
    window.location.href = url.toString();
}
</script>
@endsection