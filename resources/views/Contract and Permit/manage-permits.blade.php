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

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Permit Document Management</h1>
            <p class="text-gray-600 mt-1">
                Upload and track scanned copies of official government permits for compliance monitoring.
            </p>
        </div>
        <button type="button" onclick="openUploadPermitModal()"
                class="mt-4 md:mt-0 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-medium flex items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
            </svg>
            Upload Scanned Permit
        </button>
    </div>

    <!-- Uploaded Permits Table -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Uploaded Government Permits</h2>
            <p class="text-sm text-gray-600 mt-1">Scanned copies of official permits from LGU, DPWH, LTO, and other authorities</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Permit #</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Authority</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Validity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Compliance Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Document</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($permits as $permit)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $permit->contract_number }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ $permit->company_name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ ucfirst(str_replace('_', ' ', $permit->contract_type)) }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                            {{ \Carbon\Carbon::parse($permit->start_date)->format('M d, Y') }} –
                            {{ \Carbon\Carbon::parse($permit->end_date)->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($permit->status == 'pending')
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    ⏳ Pending Review
                                </span>
                            @elseif($permit->status == 'approved')
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800">
                                    ✅ Verified
                                </span>
                            @else
                                <span class="px-2.5 py-0.5 inline-flex text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800">
                                    ❌ Rejected
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($permit->document_path)
                                <a href="{{ asset('storage/' . $permit->document_path) }}" target="_blank" 
                                   class="text-blue-600 hover:text-blue-900 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    View
                                </a>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <!-- View Button -->
                                <a href="{{ route('permits.view', $permit->contract_id) }}"
                                   class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    View
                                </a>
                                
                                @if($permit->status == 'pending')
                                    <!-- Edit Button -->
                                    <a href="{{ route('permits.edit', $permit->contract_id) }}"
                                       class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-yellow-600 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition-colors">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        Edit
                                    </a>
                                    
                                    <!-- Delete Button -->
                                    <form action="{{ route('permits.destroy', $permit->contract_id) }}" 
                                          method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Are you sure you want to delete this permit?')"
                                                class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                            Delete
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
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No permits uploaded yet</h3>
                            <p class="mt-1 text-sm text-gray-500">Upload scanned copies of official government permits for compliance tracking.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Upload Permit Modal -->
<div id="uploadPermitModal" class="hidden fixed inset-0 flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
        <!-- Modal Header -->
        <div class="px-6 py-4 bg-gradient-to-r from-emerald-600 to-teal-700 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-bold">Upload Scanned Government Permit</h3>
                    <p class="text-emerald-100 text-xs mt-1">Submit scanned copy of official permit for compliance tracking</p>
                </div>
                <button type="button" onclick="closeUploadPermitModal()" class="text-white hover:text-gray-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6">
            <form action="{{ route('permits.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Permit Number & Authority -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Permit Number *</label>
                        <input type="text" name="permit_number" required placeholder="e.g., LGU-2026-001"
                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Issuing Authority *</label>
                        <input type="text" name="issuing_authority" required placeholder="e.g., City of Makati, DPWH"
                               class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                    </div>
                </div>

                <!-- Permit Type -->
                <div class="mb-4">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Permit Type *</label>
                    <select name="permit_type" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                        <option value="">Select Permit Type</option>
                        <option value="construction">🏗️ LGU Construction Permit</option>
                        <option value="oversize_vehicle">🚛 DPWH Oversize Vehicle Permit</option>
                        <option value="tollway_pass">🛣️ SLEx/NLEX Special Pass</option>
                        <option value="roadworthiness">📋 LTO Roadworthiness Certificate</option>
                        <option value="environmental">🌱 DENR Environmental Certificate</option>
                    </select>
                </div>

                <!-- Validity Dates -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Issue Date *</label>
                        <input type="date" name="issue_date" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Expiry Date *</label>
                        <input type="date" name="expiry_date" required class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                    </div>
                </div>

                <!-- Document Upload -->
                <div class="mb-6">
                    <label class="block text-xs font-medium text-gray-700 mb-1">Scanned Permit Document (PDF/JPG) *</label>
                    <input type="file" name="permit_document" accept=".pdf,.jpg,.jpeg,.png" required
                           class="w-full px-3 py-2 border border-gray-300 rounded text-sm">
                    <p class="text-xs text-gray-500 mt-1">Upload scanned copy of official government permit document</p>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end space-x-2 pt-2">
                    <button type="button" onclick="closeUploadPermitModal()"
                            class="px-3 py-1.5 text-gray-600 text-sm rounded hover:bg-gray-100">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-3 py-1.5 bg-emerald-600 text-white text-sm rounded hover:bg-emerald-700 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                        </svg>
                        Upload Scanned Permit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openUploadPermitModal() {
    document.getElementById('uploadPermitModal').classList.remove('hidden');
}
function closeUploadPermitModal() {
    document.getElementById('uploadPermitModal').classList.add('hidden');
}
// Close on outside click
document.addEventListener('click', function(e) {
    const modal = document.getElementById('uploadPermitModal');
    if (e.target === modal) closeUploadPermitModal();
});
</script>
@endsection