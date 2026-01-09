{{-- LOADING SPINNER --}}
<div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-lg px-8 py-6 text-center shadow-xl">
        <div class="animate-spin w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full mx-auto mb-4"></div>
        <div class="text-xl font-bold text-blue-600 mb-2">CaliCrane</div>
        <p class="text-gray-700 font-medium">Processing your request...</p>
    </div>
</div>

<style>
    #loading-overlay {
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s ease;
    }
    #loading-overlay.show {
        opacity: 1;
        pointer-events: auto;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('form[method="POST"]');
        forms.forEach(form => {
            form.addEventListener('submit', function () {
                document.getElementById('loading-overlay').classList.add('show');
            });
        });
    });
</script>

@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-6">Generate New Invoice</h2>

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

       @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
        @if($errors->has('duplicate_warning'))
            {{ $errors->first('duplicate_warning') }}
        @else
            {{ $errors->first() }}
        @endif
    </div>
@endif

        <form action="{{ route('invoice.store') }}" method="POST">
            @csrf

            <!-- Client Name -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Client Name</label>
                <input type="text" name="client_name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Equipment Type -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Equipment Type</label>
                <select name="equipment_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="">-- Select --</option>
                    <option value="crane">Crane</option>
                    <option value="truck">Truck</option>
                </select>
            </div>

            <!-- Equipment ID -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Equipment ID</label>
                <input type="text" name="equipment_id" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Hours Used -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Hours Used</label>
                <input type="number" name="hours_used" min="1" max="1000" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Hourly Rate -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Hourly Rate (₱)</label>
                <input type="number" step="0.01" name="hourly_rate" step="0.01" min="0" max="999999" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Billing Period Start -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Billing Period Start</label>
                <input type="date" name="billing_period_start" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Billing Period End -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Billing Period End</label>
                <input type="date" name="billing_period_end" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition">
                    Generate Invoice
                </button>
                <a href="{{ route('invoices.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md transition">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection