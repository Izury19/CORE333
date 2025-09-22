@extends('layouts.app')

@section('content')

<style>
    body { background-color: #f4f6f8; font-family: 'Segoe UI', sans-serif; padding: 0; }
    h2 { font-weight: 700; color: #2d3748; margin-bottom: 20px; }
    .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; padding: 10px 15px; border-radius: 5px; margin-bottom: 20px; }

    /* Card style for table */
    .table-wrapper { padding: 20px; background: #fff; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.12); margin-left: -250px; }
    table { width: 100%; border-collapse: separate; border-spacing: 0; font-size: 14px; background-color: #fff; border-radius: 8px; overflow: hidden; }
    th { background: linear-gradient(135deg, #007bff, #0056b3); color: #fff; padding: 12px 10px; text-align: left; font-weight: 600; }
    td { padding: 10px 12px; border-top: 1px solid #eee; word-wrap: break-word; overflow: hidden; }
    tr:nth-child(even) { background-color: #f9f9f9; }
    tr:hover { background-color: #eef5ff; transition: 0.2s ease-in-out; }
    td strong { color: #1e7e34; }

    /* Adjust column headers */
th.client, td.client { width: 150px; word-wrap: break-word; white-space: normal; }
th.email, td.email { width: 180px; word-wrap: break-word; white-space: normal; }
th.description, td.description { width: 200px; word-wrap: break-word; white-space: normal; }
th.qty, td.qty { width: 70px; white-space: nowrap; }
th.invoice-date, td.invoice-date,
th.due-date, td.due-date { width: 140px; white-space: nowrap; }
th.payment-method, td.payment-method { 
    width: 120px; 
    white-space: nowrap; 
    text-align: center;
}
th.total, td.total { width: 90px; white-space: nowrap; text-align: right; }
th.status, td.status { width: 90px; white-space: nowrap; text-align: center; }
th.actions, td.actions { width: 160px; white-space: nowrap; text-align: center; }

    .status-paid { color: #28a745; font-weight: 700; }
    .status-unpaid { color: #fd7e14; font-weight: 700; }
    .status-overdue { color: #dc3545; font-weight: 700; }

    /* Buttons */
    .btn { padding: 6px 12px; font-size: 12px; border-radius: 4px; margin: 2px; border: none; cursor: pointer; }
    .btn-warning { background-color: #ffc107; color: #000; }
    .btn-danger { background-color: #dc3545; color: #fff; }
    .btn-primary { background-color: #007bff; color: #fff; }
    .btn-secondary { background-color: #6c757d; color: #fff; }

    /* Modal styles */
    .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: none; justify-content: center; align-items: center; z-index: 1000; }
    .modal-overlay.active { display: flex; }
    .modal { background: #fff; padding: 20px 25px; border-radius: 8px; max-width: 400px; width: 100%; box-shadow: 0 2px 10px rgba(0,0,0,0.2); text-align: center; }
    .modal h3 { margin-bottom: 15px; font-size: 20px; color: #333; }
    .modal p { margin-bottom: 25px; font-size: 16px; color: #555; }
    .modal-buttons { display: flex; justify-content: center; gap: 15px; }
    .modal-buttons button { padding: 8px 16px; font-size: 14px; border-radius: 5px; border: none; cursor: pointer; transition: background-color 0.3s ease; }
    .modal-buttons .btn-cancel { background-color: #6c757d; color: white; }
    .modal-buttons .btn-cancel:hover { background-color: #5a6268; }
    .modal-buttons .btn-confirm { background-color: #dc3545; color: white; }
    .modal-buttons .btn-confirm:hover { background-color: #c82333; }

    /* Search + Filter */
    .filter-row { display: flex; gap: 15px; align-items: flex-end; margin-bottom: 15px; }
    .filter-row input, .filter-row select { padding: 6px 10px; border-radius: 4px; border: 1px solid #ccc; }
    .filter-row button { padding: 6px 12px; border-radius: 4px; border: none; cursor: pointer; }
    .search-container { flex:1; display:flex; gap:5px; position:relative; }
    .search-container input { flex:1; padding:8px 12px 8px 32px; border-radius: 4px; border:1px solid #ccc; }
    .search-container .search-icon { position:absolute; left:8px; top:50%; transform:translateY(-50%); color:#aaa; }
    .search-container .clear-btn { position:absolute; right:80px; top:50%; transform:translateY(-50%); cursor:pointer; display:none; }
    
    #month {
    min-width: 140px; /* o kahit 150px */
}
</style>

<!-- breadcrumb -->
        <div class="flex mb-5" aria-label="Breadcrumb" style="justify-content: flex-start; padding-left: 17.5rem;">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="#" class="inline-flex items-center text-sm font-medium text-black hover:text-blue-600">
                        <svg class="w-3 h-3 mr-2.5 text-black" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                        </svg>
                            Core 3
                    </a>
                </li>
                <li>
                <div class="flex items-center">
                    <svg class="w-3 h-3 text-black mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                    </svg>
                    <a href="#" class="ml-1 text-sm font-medium text-black text-black hover:text-blue-900 md:ml-2">Invoice Delivery</a>
                </div>
                </li>   
            </ol>
        </div>
<!-- breadcrumb -->

<!-- Table Card -->
<div class="table-wrapper">
    <h2>📦 Invoice Delivery Dashboard</h2>

    <!-- Filter Row: Search + Month/Year -->
    <form method="GET" action="{{ route('invoices.index') }}" class="filter-row">
        <div class="search-container">
            <svg class="search-icon h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M17 11a6 6 0 11-12 0 6 6 0 0112 0z" /></svg>
            <input type="text" name="search" placeholder="Search by client name..." value="{{ request('search') }}">
            <span class="clear-btn">❌</span>
            <button type="submit" class="btn btn-primary">Search</button>
        </div>

        <div>
            <label for="month">Month</label>
            <select name="month" id="month">
                <option value="">--Select--</option>
                @for($m=1;$m<=12;$m++)
                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                        {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                    </option>
                @endfor
            </select>
        </div>

        <div>
            <label for="year">Year</label>
            <input type="number" name="year" id="year" value="{{ request('year') }}" min="2000" max="3000" placeholder="YYYY">
        </div>

        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Reset</a>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($invoices->isEmpty())
        <p>No invoices available.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th class="client">Client</th>
                    <th class="email">Email</th>
                    <th class="description">Equipment(s)</th>
                    <th class="qty">Total Qty</th>
                    <th class="invoice-date">Invoice Date</th>
                    <th class="due-date">Due Date</th>
                    <th class="total">Total</th>
                    <th class="payment-method">Payment Method</th>
                    <th class="status">Status</th>
                    <th class="actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoices as $invoice)
                <tr>
                    <td class="client">{{ $invoice->client_name }}</td>
                    <td class="email">{{ $invoice->client_email }}</td>
                    @php $groupedItems = $invoice->items->groupBy('description')->map(fn($g)=>$g->sum('qty')); @endphp
                    <td class="description">
                        <ul style="padding-left:15px; margin:0;">
                            @foreach($groupedItems as $desc=>$qty)
                                <li>{{ $desc }} ({{ $qty }})</li>
                            @endforeach
                        </ul>
                    </td>
                    <td class="qty">{{ $groupedItems->sum() }}</td>
                    <td>{{ \Carbon\Carbon::parse($invoice->invoice_date)->format('Y-m-d') }}</td>
                    <td>{{ \Carbon\Carbon::parse($invoice->due_date)->format('Y-m-d') }}</td>
                    <td><strong>₱{{ number_format($invoice->total,2) }}</strong></td>
                    <td>{{ $invoice->terms_of_payment ?? 'N/A' }}</td>
                    @php
                        $status = strtolower($invoice->status);
                        $statusClass = match($status) {
                            'paid'=>'status-paid',
                            'pending','unpaid'=>'status-unpaid',
                            'overdue'=>'status-overdue',
                            default=>'',
                        };
                    @endphp
                    <td class="status {{ $statusClass }}">{{ ucfirst($status) }}</td>
                    <td class="actions">
                        <a href="{{ route('invoices.edit',$invoice->invoice_id) }}" class="btn btn-warning">Edit</a>
                        <button type="button" class="btn btn-danger btn-delete" data-invoice-id="{{ $invoice->invoice_id }}" data-client-name="{{ $invoice->client_name }}">Delete</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<!-- Delete Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal">
        <h3>Confirm Delete</h3>
        <p>Are you sure you want to delete the invoice for <strong id="modalClientName"></strong>?</p>
        <div class="modal-buttons">
            <button type="button" class="btn btn-cancel" id="cancelDelete">Cancel</button>
            <form id="deleteForm" method="POST" style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-confirm">Delete</button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function(){
    // Auto fade success alert
    let alertBox = document.querySelector(".alert-success");
    if(alertBox){ setTimeout(()=>{ alertBox.style.opacity="0"; setTimeout(()=>alertBox.remove(),500); },3000); }

    // Search clear
    const searchInput = document.querySelector(".search-container input");
    const clearBtn = document.querySelector(".clear-btn");
    if(searchInput.value.trim()!=="") clearBtn.style.display="block";
    searchInput.addEventListener("input", ()=>{ clearBtn.style.display = searchInput.value.trim()!=="" ? "block":"none"; });
    clearBtn.addEventListener("click", ()=>{ searchInput.value=""; window.location.href="{{ route('invoices.index') }}"; });

    // Delete Modal
    const modal = document.getElementById('deleteModal');
    const cancelBtn = document.getElementById('cancelDelete');
    const modalClientName = document.getElementById('modalClientName');
    const deleteForm = document.getElementById('deleteForm');
    document.querySelectorAll('.btn-delete').forEach(btn=>{
        btn.addEventListener('click', function(){
            const invoiceId = this.dataset.invoiceId;
            const clientName = this.dataset.clientName;
            modalClientName.textContent = clientName;
            deleteForm.action = `/invoices/${invoiceId}`;
            modal.classList.add('active');
        });
    });
    cancelBtn.addEventListener('click', ()=>modal.classList.remove('active'));
    modal.addEventListener('click', e=>{ if(e.target===modal) modal.classList.remove('active'); });
});
</script>

@endsection
