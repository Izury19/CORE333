@extends('layouts.app')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="flex items-center mb-8">
        <a href="{{ route('manage-permits') }}" 
           class="mr-4 text-gray-600 hover:text-gray-900">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Permit Details</h1>
            <p class="text-gray-600 mt-1">View government permit information and compliance status</p>
        </div>
    </div>

    <!-- Permit Details Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Left Column -->
            <div>
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Permit Number</label>
                    <p class="text-lg font-medium text-gray-900">{{ $permit->contract_number }}</p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Issuing Authority</label>
                    <p class="text-lg font-medium text-gray-900">{{ $permit->company_name }}</p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Permit Type</label>
                    <p class="text-lg font-medium text-gray-900">
                        {{ ucfirst(str_replace('_', ' ', $permit->contract_type)) }}
                    </p>
                </div>
            </div>
            
            <!-- Right Column -->
            <div>
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Issue Date</label>
                    <p class="text-lg font-medium text-gray-900">
                        {{ \Carbon\Carbon::parse($permit->start_date)->format('F d, Y') }}
                    </p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Expiry Date</label>
                    <p class="text-lg font-medium text-gray-900">
                        {{ \Carbon\Carbon::parse($permit->end_date)->format('F d, Y') }}
                    </p>
                </div>
                
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-500 mb-1">Compliance Status</label>
                    @if($permit->status == 'pending')
                        <span class="px-3 py-1 inline-flex text-sm leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            ⏳ Pending Review
                        </span>
                    @elseif($permit->status == 'approved')
                        <span class="px-3 py-1 inline-flex text-sm leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                            ✅ Verified
                        </span>
                    @else
                        <span class="px-3 py-1 inline-flex text-sm leading-4 font-semibold rounded-full bg-red-100 text-red-800">
                            ❌ Rejected
                        </span>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Document Section -->
        <div class="mb-6">
            <label class="block text-xs font-medium text-gray-500 mb-2">Permit Document</label>
            @if($permit->document_path)
                <a href="{{ asset('storage/' . $permit->document_path) }}" target="_blank"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    View Official Document
                </a>
            @else
                <p class="text-gray-500">No document uploaded</p>
            @endif
        </div>
        
        <!-- Action Buttons -->
        <div class="flex justify-end space-x-3 pt-4">
            <a href="{{ route('manage-permits') }}" 
               class="px-4 py-2 text-gray-600 border border-gray-300 rounded hover:bg-gray-100">
                Back to List
            </a>
            
            @if($permit->status == 'pending')
                <a href="{{ route('permits.edit', $permit->contract_id) }}"
                   class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700 font-medium">
                    Edit Permit
                </a>
            @endif
        </div>
    </div>
</div>
@endsection