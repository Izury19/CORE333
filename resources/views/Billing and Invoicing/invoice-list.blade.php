@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-bold">Invoices</h2>
        <a href="{{ route('invoice.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + New Invoice
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left">Invoice UID</th>
                    <th class="px-4 py-2 text-left">Client</th>
                    <th class="px-4 py-2 text-left">Amount</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($invoices as $inv)
                <tr>
                    <td class="px-4 py-2 font-mono">{{ $inv->invoice_uid }}</td>
                    <td class="px-4 py-2">{{ $inv->client_name }}</td>
                    <td class="px-4 py-2">₱{{ number_format($inv->total_amount, 2) }}</td>
                    <td class="px-4 py-2">
                        @if($inv->status == 'paid')
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs font-medium">Paid</span>
                        @elseif($inv->status == 'overdue')
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs font-medium">Overdue</span>
                        @else
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs font-medium">Issued</span>
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        <div class="flex gap-2 items-center">
                            <!-- View Button -->
                            <a href="{{ route('invoices.show', $inv->id) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                                👁️ View
                            </a>

                            <!-- Delete Button (only if not paid) -->
                            @if($inv->status !== 'paid')
                                <form action="{{ route('invoices.destroy', $inv->id) }}" method="POST" onsubmit="return confirm('Delete this invoice?')" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                        🗑️ Delete
                                    </button>
                                </form>
                            @endif

                            <!-- Status Menu Trigger & Content (only if not paid) -->
                            @if($inv->status !== 'paid')
                                <div class="relative">
                                    <button onclick="toggleMenu({{ $inv->id }})" class="text-gray-600 hover:text-gray-900">⋮</button>
                                    <div id="menu-{{ $inv->id }}" class="absolute right-0 z-10 hidden bg-white border rounded shadow-lg py-1 w-36 mt-1">
                                        @if($inv->status !== 'issued')
                                            <form method="POST" action="{{ route('invoices.update.status', $inv->id) }}">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="issued">
                                                <button type="submit" class="block w-full text-left px-4 py-1 hover:bg-gray-100 text-sm">Set as Issued</button>
                                            </form>
                                        @endif
                                        @if($inv->status !== 'paid')
                                            <form method="POST" action="{{ route('invoices.update.status', $inv->id) }}">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="paid">
                                                <button type="submit" class="block w-full text-left px-4 py-1 hover:bg-gray-100 text-sm">Mark as Paid</button>
                                            </form>
                                        @endif
                                        @if($inv->status !== 'overdue')
                                            <form method="POST" action="{{ route('invoices.update.status', $inv->id) }}">
                                                @csrf @method('PATCH')
                                                <input type="hidden" name="status" value="overdue">
                                                <button type="submit" class="block w-full text-left px-4 py-1 hover:bg-gray-100 text-sm">Set as Overdue</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($invoices->isEmpty())
            <p class="text-gray-500 text-center py-4">No invoices yet.</p>
        @endif
    </div>
</div>

<script>
function toggleMenu(id) {
    document.querySelectorAll('[id^="menu-"]').forEach(el => el.classList.add('hidden'));
    document.getElementById('menu-' + id).classList.toggle('hidden');
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('[id^="menu-"]') && !e.target.closest('button[onclick^="toggleMenu"]')) {
        document.querySelectorAll('[id^="menu-"]').forEach(el => el.classList.add('hidden'));
    }
});
</script>
@endsection