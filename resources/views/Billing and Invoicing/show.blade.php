@extends('layouts.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Invoice Details</h1>
            <p class="text-gray-600 mt-1">Detailed view of invoice #{{ $invoice->invoice_uid }}</p>
        </div>
        <div class="mt-4 md:mt-0 flex space-x-3">
            <a href="{{ route('billing.invoices.pdf', $invoice->id) }}" 
               class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download PDF
            </a>
            <a href="{{ route('billing.invoices.index') }}" 
               class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg">
                Back to List
            </a>
        </div>
    </div>

    <!-- AI Status Banner -->
    @if($invoice->ai_duplicate_flag ?? false)
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span class="text-red-800">⚠️ AI Alert: This invoice has similar entries in the last 30 days.</span>
        </div>
    @else
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span class="text-green-800">✅ AI Verified: No duplicate issues detected.</span>
        </div>
    @endif

    <!-- Invoice Details Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Invoice Information</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Invoice ID</label>
                        <p class="mt-1 text-lg font-medium text-gray-900">{{ $invoice->invoice_uid }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Job Order</label>
                        <p class="mt-1 text-gray-900">
                            @if($invoice->job_order_id)
                                JO-{{ str_pad($invoice->job_order_id, 4, '0', STR_PAD_LEFT) }}
                            @else
                                Not linked to job order
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Client Name</label>
                        <p class="mt-1 text-gray-900">{{ $invoice->client_name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Equipment Type</label>
                        <p class="mt-1 text-gray-900">{{ ucfirst(str_replace('_', ' ', $invoice->equipment_type)) }}</p>
                    </div>
                </div>
                
                <!-- Right Column -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Equipment ID</label>
                        <p class="mt-1 text-gray-900">{{ $invoice->equipment_id }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Billing Period</label>
                        <p class="mt-1 text-gray-900">
                            @if($invoice->billing_period_start && $invoice->billing_period_end)
                                {{ \Carbon\Carbon::parse($invoice->billing_period_start)->format('F d, Y') }} to<br>
                                {{ \Carbon\Carbon::parse($invoice->billing_period_end)->format('F d, Y') }}
                            @else
                                Not specified
                            @endif
                        </p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hours Used</label>
                        <p class="mt-1 text-gray-900">{{ $invoice->hours_used ?? 'N/A' }} hours</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <p class="mt-1">
                            @if($invoice->status == 'billed' || $invoice->status == 'issued')
                                <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Issued
                                </span>
                            @elseif($invoice->status == 'paid')
                                <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                                    Paid
                                </span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Amount Section -->
            <div class="border-t border-gray-200 pt-6 mt-6">
                <div class="flex justify-between items-center">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hourly Rate</label>
                        <p class="text-lg font-medium text-gray-900">₱{{ number_format($invoice->hourly_rate, 2) }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Total Amount</label>
                        <p class="text-2xl font-bold text-gray-900">₱{{ number_format($invoice->total_amount, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection