@extends('layouts.app')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    @if(session('info'))
        <div class="mb-6 p-4 bg-blue-100 border border-blue-400 text-blue-700 rounded-lg">
            {{ session('info') }}
        </div>
    @endif
    
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Contract Management</h1>
            <p class="text-gray-600 mt-1">
                Create and manage legal contracts. All contracts are automatically sent to Legal Management Module for approval.
            </p>
        </div>
        <button type="button" onclick="openCreateContractModal()"
                class="mt-4 md:mt-0 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Create Contract
        </button>
    </div>

    <!-- Contract Metrics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Contracts -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Total Contracts</h3>
                    <p class="text-2xl font-bold text-gray-900">{{ $contracts->count() }}</p>
                    <p class="text-xs text-gray-600 mt-1">Active and pending contracts</p>
                </div>
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Pending Review -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Pending Review</h3>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $contracts->where('status', 'pending')->count() }}
                    </p>
                    <p class="text-xs text-yellow-600 mt-1">Awaiting legal approval</p>
                </div>
                <div class="w-10 h-10 bg-yellow-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
        
        <!-- Approved Contracts -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Approved Contracts</h3>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ $contracts->where('status', 'approved')->count() }}
                    </p>
                    <p class="text-xs text-green-600 mt-1">Ready for execution</p>
                </div>
                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Contract Filters -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search by contract #, client, or type..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                       onkeyup="this.form.submit()">
            </div>
            
            <div class="sm:w-48">
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending Review</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
            
            <div class="sm:w-48">
                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" onchange="this.form.submit()">
            </div>
            
            <div class="sm:w-48">
                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" onchange="this.form.submit()">
            </div>
        </form>
    </div>

    <!-- Submitted Contracts Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Submitted Contracts</h2>
            <p class="text-sm text-gray-600 mt-1">Contracts sent to Legal Management Module for review and approval</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contract #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Counterparty</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Validity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Legal Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($contracts as $contract)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $contract->contract_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ ucfirst(str_replace('_', ' ', $contract->contract_type)) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $contract->company_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ \Carbon\Carbon::parse($contract->start_date)->format('M d, Y') }} –
                            {{ \Carbon\Carbon::parse($contract->end_date)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            ₱{{ number_format($contract->total_amount ?? 0, 2) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($contract->status == 'pending')
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    ⏳ Pending Review
                                </span>
                            @elseif($contract->status == 'approved')
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                                    ✅ Approved
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800">
                                    ❌ Rejected
                                </span>
                            @endif
                        </td>
                       <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
    <div class="flex items-center space-x-2">
        <!-- View Button -->
        <a href="{{ route('contracts.view', $contract->contract_id) }}"
           class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
            </svg>
            View
        </a>
        
        @if($contract->status == 'pending')
            <!-- Edit Button -->
            <a href="{{ route('contracts.edit', $contract->contract_id) }}"
               class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-yellow-600 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
                Edit
            </a>
            
            <!-- Delete Button -->
            <form action="{{ route('contracts.destroy', $contract->contract_id) }}" 
                  method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        onclick="return confirm('Are you sure you want to delete this contract?')"
                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Delete
                </button>
            </form>
        @else
            <!-- Refresh Status Button -->
            <form action="{{ route('contracts.refresh-status', $contract->contract_id) }}" 
                  method="POST" style="display: inline;">
                @csrf
                <button type="submit"
                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    Refresh
                </button>
            </form>
        @endif
    </div>
</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No contracts submitted yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Click "+ Create Contract" to submit a new contract for legal review.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($contracts->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Showing {{ $contracts->firstItem() }} to {{ $contracts->lastItem() }} of {{ $contracts->total() }} results
            </div>
            <div>
                {{ $contracts->links() }}
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Submit New Contract Modal -->
<div id="createContractModal" class="hidden fixed inset-0 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
        <!-- Header -->
        <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-700 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold">Submit New Contract</h3>
                    <p class="text-indigo-100 text-xs mt-1">This will be sent to Legal Management Module for approval</p>
                </div>
                <button type="button" onclick="closeCreateContractModal()" class="text-white hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Body -->
        <div class="p-6">
            <form action="{{ route('contracts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Contract Type & Number -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Contract Type *</label>
                        <select name="contract_type" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                            <option value="">Select type</option>
                            <option value="rental">Rental</option>
                            <option value="service">Service</option>
                            <option value="procurement">Procurement</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Contract Number *</label>
                        <input type="text" name="contract_number" required placeholder="e.g., CT-2C"
                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                    </div>
                </div>

                <!-- Counterparty -->
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Counterparty (Client/Vendor) *</label>
                    <input type="text" name="counterparty" required placeholder="Company or individual name"
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                </div>

                <!-- Contact Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Contact Email</label>
                        <input type="email" name="contact_email" placeholder="contact@c"
                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Contact Number</label>
                        <input type="text" name="contact_number" placeholder="+63 912 34"
                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                    </div>
                </div>

                <!-- Dates (with calendar picker) -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Effective Date *</label>
                        <input type="date" name="effective_date" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Expiration Date *</label>
                        <input type="date" name="expiration_date" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                    </div>
                </div>

                <!-- Amount & Payment Terms -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Total Amount (₱) *</label>
                        <input type="number" name="total_amount" step="0.01" min="0" required placeholder="0.00"
                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Payment Terms</label>
                        <select name="payment_type" class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                            <option value="net_30">Net 30 Day</option>
                            <option value="net_60">Net 60 Day</option>
                            <option value="upon_delivery">Upon Delivery</option>
                        </select>
                    </div>
                </div>

                <!-- Equipment Type -->
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Equipment Type (if apply)</label>
                    <select name="equipment_type" class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                        <option value="">Select equipment</option>
                        <option value="tower_crane">Tower Crane</option>
                        <option value="mobile_crane">Mobile Crane</option>
                        <option value="dump_truck">Dump Truck</option>
                        <option value="concrete_mixer">Concrete Mixer</option>
                    </select>
                </div>

                <!-- Scope of Work -->
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Contract Details / Scope of Work</label>
                    <textarea name="contract_details" rows="3" placeholder="Describe the scope of work, deliverables, and terms..."
                              class="w-full px-3 py-2 border border-gray-300 rounded text-sm"></textarea>
                </div>

                <!-- Supporting Documents -->
                <div class="mb-6">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Supporting Documents (PDF/DOCX) *</label>
                    <div class="flex items-center space-x-3">
                        <button type="button" class="px-4 py-2 bg-gray-100 border border-gray-300 rounded text-sm hover:bg-gray-200">
                            Choose Files
                        </button>
                        <span class="text-gray-500 text-sm">N...sen</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Upload SOW, specs, or other supporting documents</p>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-2">
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 font-medium text-sm flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Submit for Legal Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openCreateContractModal() {
    document.getElementById('createContractModal').classList.remove('hidden');
}
function closeCreateContractModal() {
    document.getElementById('createContractModal').classList.add('hidden');
}
// Close on outside click
document.addEventListener('click', function(e) {
    const modal = document.getElementById('createContractModal');
    if (e.target === modal) closeCreateContractModal();
});
</script>



<script>
function viewContract(contractNumber) {
    fetch(`/contracts/${contractNumber}`)
        .then(response => response.json())
        .then(contract => {
            document.getElementById('contractDetailsContent').innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><strong>Contract Number:</strong> ${contract.contract_number}</div>
                    <div><strong>Type:</strong> ${contract.contract_type}</div>
                    <div><strong>Counterparty:</strong> ${contract.company_name}</div>
                    <div><strong>Status:</strong> 
                        <span class="px-2 py-1 rounded-full text-xs ${
                            contract.status === 'approved' ? 'bg-green-100 text-green-800' : 
                            contract.status === 'rejected' ? 'bg-red-100 text-red-800' : 
                            'bg-yellow-100 text-yellow-800'
                        }">
                            ${contract.status.charAt(0).toUpperCase() + contract.status.slice(1)}
                        </span>
                    </div>
                    <div><strong>Effective Date:</strong> ${contract.start_date}</div>
                    <div><strong>Expiration Date:</strong> ${contract.end_date}</div>
                    <div class="md:col-span-2"><strong>Details:</strong> ${contract.contract_details || 'N/A'}</div>
                </div>
            `;
            document.getElementById('viewContractModal').classList.remove('hidden');
        })
        .catch(error => {
            console.error('Error fetching contract:', error);
            alert('Failed to load contract details.');
        });
}

function closeViewContractModal() {
    document.getElementById('viewContractModal').classList.add('hidden');
}
</script>
@endsection