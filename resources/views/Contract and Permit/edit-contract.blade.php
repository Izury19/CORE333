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
            <h1 class="text-2xl font-bold text-gray-900">Edit Contract</h1>
            <p class="text-gray-600 mt-1">Update contract details before legal review</p>
        </div>
    </div>

    <!-- Edit Contract Form -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('contracts.update', $contract->contract_id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Contract Type & Number -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Contract Type *</label>
                    <select name="contract_type" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                        <option value="">Select type</option>
                        <option value="rental" {{ $contract->contract_type == 'rental' ? 'selected' : '' }}>Rental</option>
                        <option value="service" {{ $contract->contract_type == 'service' ? 'selected' : '' }}>Service</option>
                        <option value="procurement" {{ $contract->contract_type == 'procurement' ? 'selected' : '' }}>Procurement</option>
                        <option value="maintenance" {{ $contract->contract_type == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Contract Number *</label>
                    <input type="text" name="contract_number" value="{{ $contract->contract_number }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm bg-gray-100" readonly>
                </div>
            </div>

            <!-- Counterparty -->
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-700 mb-1">Counterparty (Client/Vendor) *</label>
                <input type="text" name="counterparty" value="{{ $contract->company_name }}" required
                       class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
            </div>

            <!-- Contact Info -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Contact Email</label>
                    <input type="email" name="contact_email" value="{{ $contract->client_email }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Contact Number</label>
                    <input type="text" name="contact_number" value="{{ $contract->client_number }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                </div>
            </div>

            <!-- Dates -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Effective Date *</label>
                    <input type="date" name="effective_date" value="{{ $contract->start_date }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Expiration Date *</label>
                    <input type="date" name="expiration_date" value="{{ $contract->end_date }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                </div>
            </div>

            <!-- Amount & Payment Terms -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Total Amount (₱) *</label>
                    <input type="number" name="total_amount" step="0.01" min="0" 
                           value="{{ $contract->total_amount ?? 0 }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-700 mb-1">Payment Terms</label>
                    <select name="payment_terms" class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                        <option value="net_30" {{ ($contract->payment_type ?? '') == 'net_30' ? 'selected' : '' }}>Net 30 Day</option>
                        <option value="net_60" {{ ($contract->payment_type ?? '') == 'net_60' ? 'selected' : '' }}>Net 60 Day</option>
                        <option value="upon_delivery" {{ ($contract->payment_type ?? '') == 'upon_delivery' ? 'selected' : '' }}>Upon Delivery</option>
                    </select>
                </div>
            </div>

            <!-- Equipment Type -->
            <div class="mb-4">
                <label class="block text-xs font-medium text-gray-700 mb-1">Equipment Type (if apply)</label>
                <select name="equipment_type" class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                    <option value="">Select equipment</option>
                    <option value="tower_crane" {{ $contract->equipment_type == 'tower_crane' ? 'selected' : '' }}>Tower Crane</option>
                    <option value="mobile_crane" {{ $contract->equipment_type == 'mobile_crane' ? 'selected' : '' }}>Mobile Crane</option>
                    <option value="dump_truck" {{ $contract->equipment_type == 'dump_truck' ? 'selected' : '' }}>Dump Truck</option>
                    <option value="concrete_mixer" {{ $contract->equipment_type == 'concrete_mixer' ? 'selected' : '' }}>Concrete Mixer</option>
                </select>
            </div>

            <!-- Scope of Work -->
            <div class="mb-6">
                <label class="block text-xs font-medium text-gray-700 mb-1">Contract Details / Scope of Work</label>
                <textarea name="contract_details" rows="4" 
                          class="w-full px-3 py-2 border border-gray-300 rounded text-sm">{{ $contract->contract_details }}</textarea>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 pt-4">
                <a href="{{ route('contract.management') }}" 
                   class="px-4 py-2 text-gray-600 border border-gray-300 rounded hover:bg-gray-100">
                    Cancel
                </a>
                <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 font-medium">
                    Update Contract
                </button>
            </div>
        </form>
    </div>
</div>
@endsection