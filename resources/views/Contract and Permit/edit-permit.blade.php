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
            <h1 class="text-2xl font-bold text-gray-900">Edit Permit</h1>
            <p class="text-gray-600 mt-1">Update permit details before compliance verification</p>
        </div>
    </div>

    <!-- Edit Permit Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('permits.update', $permit->contract_id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Permit Number & Authority -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Permit Number *</label>
                    <input type="text" name="permit_number" value="{{ $permit->contract_number }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Issuing Authority *</label>
                    <input type="text" name="issuing_authority" value="{{ $permit->company_name }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                </div>
            </div>

            <!-- Permit Type -->
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-700 mb-1">Permit Type *</label>
                <select name="permit_type" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                    <option value="">Select Permit Type</option>
                    <option value="construction" {{ $permit->contract_type == 'construction' ? 'selected' : '' }}>🏗️ LGU Construction Permit</option>
                    <option value="oversize_vehicle" {{ $permit->contract_type == 'oversize_vehicle' ? 'selected' : '' }}>🚛 DPWH Oversize Vehicle Permit</option>
                    <option value="tollway_pass" {{ $permit->contract_type == 'tollway_pass' ? 'selected' : '' }}>🛣️ SLEx/NLEX Special Pass</option>
                    <option value="roadworthiness" {{ $permit->contract_type == 'roadworthiness' ? 'selected' : '' }}>📋 LTO Roadworthiness Certificate</option>
                    <option value="environmental" {{ $permit->contract_type == 'environmental' ? 'selected' : '' }}>🌱 DENR Environmental Certificate</option>
                </select>
            </div>

            <!-- Validity Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Issue Date *</label>
                    <input type="date" name="issue_date" value="{{ $permit->start_date }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Expiry Date *</label>
                    <input type="date" name="expiry_date" value="{{ $permit->end_date }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                </div>
            </div>

            <!-- Document Upload -->
            <div class="mb-6">
                <label class="block text-xs font-medium text-gray-700 mb-1">Permit Document (PDF/JPG)</label>
                <input type="file" name="permit_document" accept=".pdf,.jpg,.jpeg,.png"
                       class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                @if($permit->document_path)
                    <p class="text-xs text-gray-500 mt-1">
                        Current: <a href="{{ asset('storage/' . $permit->document_path) }}" target="_blank" class="text-blue-600">View current document</a>
                    </p>
                @endif
                <p class="text-xs text-gray-500 mt-1">Upload new scanned copy if needed</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('manage-permits') }}" 
                   class="px-4 py-2 text-gray-600 border border-gray-300 rounded hover:bg-gray-100">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700 font-medium">
                    Update Permit
                </button>
            </div>
        </form>
    </div>
</div>
@endsection