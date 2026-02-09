@extends('layouts.app')

@section('content')
<!-- Session Messages (FIXED: Removed duplicates) -->
@if(session('success'))
    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center">
        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        <span class="text-green-800">{{ session('success') }}</span>
    </div>
@endif

@if(session('error'))
    <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center">
        <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        <span class="text-red-800">{{ session('error') }}</span>
    </div>
@endif

<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Billing & Invoicing</h1>
            <p class="text-gray-600 mt-1">Auto-generated invoices with AI-powered intelligent billing</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            @php
                $invoiceCount = isset($invoices) ? $invoices->count() : 0;
            @endphp
            <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-100 text-blue-800 text-sm font-medium">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                </svg>
                {{ $invoiceCount }} Total Invoices
            </div>
            
            <!-- SCAN BUTTON FOR AI ANALYSIS -->
            <form action="{{ route('billing.invoices.scan') }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                        class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium">
                    🔍 Scan for Duplicates
                </button>
            </form>
            
            <!-- DEMO BUTTON FOR INTEGRATION DEMONSTRATION -->
            <button type="button" onclick="openGenerateInvoiceModal()"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium">
                + Generate Invoice
        </div>
    </div>

    <!-- AI-Powered Intelligent Billing Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-8">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                AI-Powered Intelligent Billing
            </h2>
            <p class="text-sm text-gray-600 mt-1">AI features focused on billing accuracy and duplicate prevention</p>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Duplicate Detection AI -->
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex items-center mb-2">
                        <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="ml-3 font-medium text-gray-900">Duplicate AI</h3>
                    </div>
                    <p class="text-sm text-gray-600">
                        Duplicate alerts:<br>
                        <span class="font-bold text-yellow-700">{{ $aiPredictions['duplicate_alerts'] ?? 0 }}</span> potential duplicates<br>
                        <span class="text-xs text-gray-500">Last 7 days monitoring</span>
                    </p>
                </div>
                
                <!-- Rate Validation AI -->
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex items-center mb-2">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7l4-4m0 0l4 4m-4-4v18m0 0l-4-4m4 4l4-4" />
                            </svg>
                        </div>
                        <h3 class="ml-3 font-medium text-gray-900">Rate AI</h3>
                    </div>
                    <p class="text-sm text-gray-600">
                        Recommended rate:<br>
                        <span class="font-bold text-blue-700">{{ $aiPredictions['recommended_rate'] ?? '₱1,800/hr' }}</span><br>
                        <span class="text-xs text-gray-500">Based on equipment type</span>
                    </p>
                </div>
                
                <!-- Invoice Verification AI -->
                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex items-center mb-2">
                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="ml-3 font-medium text-gray-900">Verification AI</h3>
                    </div>
                    <p class="text-sm text-gray-600">
                        Invoices verified:<br>
                        <span class="font-bold text-green-700">{{ $aiPredictions['verified_invoices'] ?? 0 }}</span> clean invoices<br>
                        <span class="text-xs text-gray-500">AI-verified status</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- AUTO-GENERATED INVOICES -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Auto-Generated Invoices
                </h2>
                
                <!-- BULK FORWARD BUTTON -->
                <button type="button" 
                        onclick="openBulkForwardModal()" 
                        class="mt-4 md:mt-0 px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium"
                        id="bulkForwardBtn" style="display: none;">
                    📤 Forward Selected to Financials
                </button>
            </div>
            <p class="text-sm text-gray-600 mt-1">Invoices automatically generated from completed job orders</p>
        </div>
        
        <!-- GLOBAL SEARCH BAR -->
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <label class="block text-sm font-medium text-gray-700 mb-2 sm:mb-0">
                    Search Invoices:
                </label>
                <input type="text" 
                       id="globalSearch" 
                       placeholder="Search by Ref #, Client, Equipment, or Amount..." 
                       class="w-full sm:w-64 px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       onkeyup="globalSearch(this.value)">
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <!-- BULK FORWARD FORM -->
            <form id="bulkForwardForm" method="POST" action="{{ route('billing.invoices.bulk-forward') }}">
                @csrf
                <input type="hidden" name="invoice_ids_json" id="selectedInvoiceIds">
            </form>
            
            <table class="min-w-full divide-y divide-gray-200" id="invoicesTable">
                <thead class="bg-gray-50">
                    <tr>
                        <!-- Checkbox Column -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="selectAll" class="rounded">
                        </th>
                        
                        <!-- Ref # Column -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ref #
                        </th>
                        
                        <!-- Job Order Column -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Job Order
                        </th>
                        
                        <!-- Client Column -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Client
                        </th>
                        
                        <!-- Equipment Column -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Equipment
                        </th>
                        
                        <!-- Period Column -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Period
                        </th>
                        
                        <!-- Amount Column -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Amount
                        </th>
                        
                        <!-- Status Column -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        
                        <!-- AI Status Column -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            AI Status
                        </th>
                        
                        <!-- Actions Column -->
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php
                        $invoices = $invoices ?? collect();
                    @endphp
                    
                    @forelse($invoices as $invoice)
                    <tr class="hover:bg-gray-50 transition-colors invoice-row" 
                        data-client="{{ strtolower($invoice->client_name ?? '') }}"
                        data-equipment="{{ strtolower($invoice->equipment_type ?? '') }}"
                        data-status="{{ strtolower($invoice->status ?? '') }}"
                        data-ref="{{ strtolower($invoice->invoice_uid ?? '') }}"
                        data-amount="{{ str_replace(['₱', ','], '', number_format($invoice->total_amount ?? 0, 2)) }}"
                        data-date="{{ \Carbon\Carbon::parse($invoice->created_at)->format('Y-m-d') }}">
                        <!-- Checkbox -->
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if(in_array(strtolower($invoice->status), ['issued', 'billed']))
                                <input type="checkbox" name="invoice_ids[]" value="{{ $invoice->id }}" class="invoice-checkbox rounded">
                            @endif
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $invoice->invoice_uid ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            @if($invoice->job_order_id ?? null)
                                JO-{{ str_pad($invoice->job_order_id, 4, '0', STR_PAD_LEFT) }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 client-name">
                            {{ $invoice->client_name ?? 'Unknown Client' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            @if($invoice->equipment_type)
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
                                {{ $equipmentDisplay[$invoice->equipment_type] ?? ucfirst(str_replace('_', ' ', $invoice->equipment_type)) }}
                            @else
                                N/A
                            @endif
                            <br>
                            <span class="text-xs text-gray-500">{{ $invoice->equipment_id ?? 'No ID' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            @if($invoice->billing_period_start && $invoice->billing_period_end)
                                {{ \Carbon\Carbon::parse($invoice->billing_period_start)->format('M d') }} -<br>
                                {{ \Carbon\Carbon::parse($invoice->billing_period_end)->format('M d, Y') }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 amount-value">
                            ₱{{ number_format($invoice->total_amount ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $status = $invoice->status ?? 'unknown';
                            @endphp
                            @if($status == 'billed' || $status == 'issued')
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800 status-badge">
                                    Issued
                                </span>
                            @elseif($status == 'paid')
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800 status-badge">
                                    Paid
                                </span>
                            @elseif($status == 'overdue')
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800 status-badge">
                                    Overdue
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-gray-100 text-gray-800 status-badge">
                                    {{ ucfirst($status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($invoice->ai_duplicate_flag ?? false)
                                <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800">
                                    ⚠️ Review Needed
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                                    ✅ Verified
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <a href="{{ route('billing.invoices.show', $invoice->id) }}" 
                                   class="text-blue-600 hover:text-blue-900 p-1.5 rounded-md hover:bg-blue-50 transition"
                                   title="View Invoice">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </a>
                                
                                @if(isset($invoice->id))
                                    <a href="#" 
                                       onclick="downloadProtectedPdf({{ $invoice->id }})"
                                       class="text-green-600 hover:text-green-900 p-1.5 rounded-md hover:bg-green-50 transition"
                                       title="Download PDF (Password Protected)">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No invoices generated yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Invoices will be auto-generated when job orders are completed.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Professional Generate Demo Invoice Modal -->
<div id="invoiceModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md mx-4 overflow-hidden">
        <!-- Modal Header -->
        <div class="px-6 py-4 bg-gradient-to-r from-blue-600 to-indigo-700 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold">Generate Demo Invoice</h3>
                    <p class="text-blue-100 text-sm mt-1">Professional invoice for equipment rental</p>
                </div>
                <button type="button" onclick="closeModal()" class="text-white hover:text-gray-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form action="{{ route('billing.invoices.demo-store') }}" method="POST">
                @csrf

                <!-- Client Name -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Client Name</label>
                    <input type="text" name="client_name" 
                           value="ABC Construction"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg"
                           required>
                </div>

                <!-- Equipment Type -->
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Equipment Type</label>
                    <select name="equipment_type" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg appearance-none bg-white"
                            required>
                        <option value="mobile_crane">Mobile Crane (50T)</option>
                        <option value="tower_crane">Tower Crane (20T)</option>
                        <option value="dump_truck">Dump Truck (10T)</option>
                        <option value="concrete_mixer">Concrete Mixer Truck</option>
                    </select>
                </div>

                <!-- Hours Used -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hours Used</label>
                    <div class="flex items-center space-x-4">
                        <input type="number" name="hours_used" 
                               value="8"
                               min="1" step="0.5"
                               class="w-1/2 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-lg"
                               required>
                        <div class="text-gray-500 text-sm">
                            <p>Hourly Rate: <span class="font-semibold text-blue-600">₱2,500.00</span></p>
                            <p>Total: <span class="font-bold text-green-600">₱20,000.00</span></p>
                        </div>
                    </div>
                </div>

                <!-- Invoice Preview -->
                <div class="bg-gray-50 rounded-lg p-5 mb-6">
                    <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Invoice Preview
                    </h4>
                    <div class="border border-gray-200 rounded-lg p-4 bg-white">
                        <!-- Bill To & Equipment -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-sm text-gray-500">Bill To:</p>
                                <p class="font-medium">ABC Construction</p>
                                <p class="text-sm text-gray-600">123 Construction Ave, Manila</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Equipment:</p>
                                <p class="font-medium">Mobile Crane (50T)</p>
                                <p class="text-sm text-gray-600">Hours: 8 | Rate: ₱2,500/hr</p>
                            </div>
                        </div>
                        <!-- Totals -->
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal</span>
                                <span class="font-medium">₱20,000.00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">VAT (12%)</span>
                                <span class="font-medium">₱2,400.00</span>
                            </div>
                            <div class="pt-3 border-t border-gray-200 flex justify-between">
                                <span class="text-lg font-bold text-gray-900">Total Amount</span>
                                <span class="text-xl font-bold text-green-600">₱22,400.00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-3">
                    <button type="button" 
                            onclick="closeModal()"
                            class="px-6 py-3 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-medium rounded-lg hover:from-green-600 hover:to-emerald-700 transition-all shadow-md hover:shadow-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Generate Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Global search function
function globalSearch(searchValue) {
    const rows = document.querySelectorAll('.invoice-row');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const ref = row.getAttribute('data-ref') || '';
        const client = row.getAttribute('data-client') || '';
        const equipment = row.getAttribute('data-equipment') || '';
        const amount = row.getAttribute('data-amount') || '';
        
        const searchLower = searchValue.toLowerCase();
        const shouldShow = ref.includes(searchLower) || 
                          client.includes(searchLower) || 
                          equipment.includes(searchLower) || 
                          amount.includes(searchLower);
        
        row.style.display = shouldShow ? '' : 'none';
        if (shouldShow) visibleCount++;
    });
}

// Modal functions
function openGenerateInvoiceModal() {
    document.getElementById('invoiceModal').classList.remove('hidden');
}
function closeModal() {
    document.getElementById('invoiceModal').classList.add('hidden');
}

// Close modal when clicking outside
document.addEventListener('click', function(e) {
    const modal = document.getElementById('invoiceModal');
    if (e.target === modal) {
        closeModal();
    }
});

// Select All / Deselect All
document.getElementById('selectAll').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.invoice-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    updateBulkForwardButton();
});

// Update bulk forward button visibility
function updateBulkForwardButton() {
    const selectedCount = document.querySelectorAll('.invoice-checkbox:checked').length;
    const bulkForwardBtn = document.getElementById('bulkForwardBtn');
    bulkForwardBtn.style.display = selectedCount > 0 ? 'inline-block' : 'none';
}

// Individual checkbox change
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('invoice-checkbox')) {
        updateBulkForwardButton();
    }
});

// Bulk forward modal
function openBulkForwardModal() {
    const selectedIds = Array.from(document.querySelectorAll('.invoice-checkbox:checked'))
        .map(checkbox => checkbox.value);
    
    if (selectedIds.length === 0) {
        alert('Please select at least one invoice to forward.');
        return;
    }
    
    if (confirm(`Are you sure you want to forward ${selectedIds.length} invoice(s) to Financials System?`)) {
        document.getElementById('selectedInvoiceIds').value = JSON.stringify(selectedIds);
        document.getElementById('bulkForwardForm').submit();
    }
}

// Password-protected PDF download
function downloadProtectedPdf(invoiceId) {
    // Show password info
    alert("🔒 PDF Password Information:\n\nYour password is: [client_name][invoice_id]\nExample: abcconstruction60\n\nClick OK to download PDF");
    
    // Redirect to PDF download
    window.location.href = '{{ route("billing.invoices.pdf", ":id") }}'.replace(':id', invoiceId);
}
</script>

<!-- 3-MINUTE AUTO-LOCK -->
<script>
    let inactivityTimer;
    const INACTIVITY_LIMIT = 7200000; // 3 minutes

    function startInactivityTimer() {
        inactivityTimer = setTimeout(() => {
            alert('🔒 System auto-locked due to inactivity!');
            window.location.href = '{{ route("auto.logout") }}';
        }, INACTIVITY_LIMIT);
    }

    function resetInactivityTimer() {
        clearTimeout(inactivityTimer);
        startInactivityTimer();
    }

    document.addEventListener('DOMContentLoaded', () => {
        startInactivityTimer();
        ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart'].forEach(event => {
            document.addEventListener(event, resetInactivityTimer, true);
        });
    });
</script>
@endsection