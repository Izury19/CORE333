@extends('layouts.app')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex items-center mb-8">
        <a href="{{ route('contract.management') }}" 
           class="mr-4 text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Contract Details</h1>
            <p class="text-gray-600 mt-1">View contract information and status</p>
        </div>
    </div>

    <!-- Contract Details Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Left Column -->
            <div>
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Contract Number</label>
                    <p class="text-lg font-medium text-gray-900">{{ $contract->contract_number }}</p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Contract Type</label>
                    <p class="text-lg font-medium text-gray-900">
                        {{ ucfirst(str_replace('_', ' ', $contract->contract_type)) }}
                    </p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Counterparty</label>
                    <p class="text-lg font-medium text-gray-900">{{ $contract->company_name }}</p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Contact Email</label>
                    <p class="text-lg font-medium text-gray-900">
                        {{ $contract->client_email ?? 'N/A' }}
                    </p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Contact Number</label>
                    <p class="text-lg font-medium text-gray-900">
                        {{ $contract->client_number ?? 'N/A' }}
                    </p>
                </div>
            </div>
            
            <!-- Right Column -->
            <div>
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Effective Date</label>
                    <p class="text-lg font-medium text-gray-900">
                        {{ \Carbon\Carbon::parse($contract->start_date)->format('F d, Y') }}
                    </p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Expiration Date</label>
                    <p class="text-lg font-medium text-gray-900">
                        {{ \Carbon\Carbon::parse($contract->end_date)->format('F d, Y') }}
                    </p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Total Amount</label>
                    <p class="text-lg font-medium text-gray-900">
                        ₱{{ number_format($contract->total_amount ?? 0, 2) }}
                    </p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Payment Terms</label>
                    <p class="text-lg font-medium text-gray-900">
                        {{ $contract->payment_type ?? 'N/A' }}
                    </p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Equipment Type</label>
                    <p class="text-lg font-medium text-gray-900">
                        {{ $contract->equipment_type ?? 'N/A' }}
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Contract Details -->
        <div class="mb-6">
            <label class="block text-xs font-medium text-gray-500 mb-2">Contract Details / Scope of Work</label>
            <div class="bg-gray-50 p-4 rounded-lg min-h-24">
                <p class="text-gray-900 whitespace-pre-wrap">
                    {{ $contract->contract_details ?? 'No details provided.' }}
                </p>
            </div>
        </div>
        
        <!-- Status Badge -->
        <div class="mb-6">
            <label class="block text-xs font-medium text-gray-500 mb-2">Legal Status</label>
            @if($contract->status == 'pending')
                <span class="px-3 py-1 inline-flex text-sm leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                    ⏳ Pending Review
                </span>
            @elseif($contract->status == 'approved')
                <span class="px-3 py-1 inline-flex text-sm leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                    ✅ Approved
                </span>
            @else
                <span class="px-3 py-1 inline-flex text-sm leading-4 font-semibold rounded-full bg-red-100 text-red-800">
                    ❌ Rejected
                </span>
            @endif
        </div>
        
        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('contract.management') }}" 
               class="px-4 py-2 text-gray-600 border border-gray-300 rounded hover:bg-gray-100">
                Back to List
            </a>
            
            @if($contract->status == 'pending')
                <a href="{{ route('contracts.edit', $contract->contract_id) }}"
                   class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 font-medium">
                    Edit Contract
                </a>
            @endif
        </div>
    </div>
</div>
@endsection