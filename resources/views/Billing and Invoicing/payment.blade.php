@extends('layouts.maintenance')

@section('content')
<!-- Payment Processing Dashboard -->

<style>
  body { background: #f4f6f8; font-family: 'Segoe UI', sans-serif; }
  .container { max-width: 1100px; margin: 32px auto; }
  .card { background: #fff; border-radius: 10px; padding: 18px; box-shadow: 0 6px 18px rgba(20,20,30,0.06); }
  .grid { display: grid; gap: 16px; }
  .grid-4 { grid-template-columns: repeat(4, 1fr); }
  .flex { display:flex; gap:12px; align-items:center; }
  h1{ margin:0 0 12px 0; font-size:22px }
  .muted{ color:#6b7280 }
  table { width:100%; border-collapse:collapse; }
  th, td { padding:10px 12px; border-bottom:1px solid #eef2f7; text-align:left; }
  th { background:#f8fafc; font-weight:600; }
  .btn{ padding:8px 10px; border-radius:8px; cursor:pointer; border:none; font-size:14px }
  .btn-primary{ background:#007bff; color:#fff }
  .btn-ghost{ background:transparent; border:1px solid #e5e7eb }
  .badge{ padding:6px 8px; border-radius:999px; font-size:13px }
  .badge-paid{ background:#d1fae5; color:#065f46 }
  .badge-overdue{ background:#fee2e2; color:#991b1b }
  .search { padding:8px 12px; border-radius:8px; border:1px solid #e6edf3 }
  .controls { display:flex; flex-wrap:wrap; gap:8px; align-items:center }
  .skeleton { background: linear-gradient(90deg,#f2f4f7,#eef2f7,#f2f4f7); background-size:200% 100%; animation: shimmer 1.6s linear infinite; border-radius:6px }
  @keyframes shimmer{ 0%{ background-position:200% 0 } 100%{ background-position:-200% 0 } }

  /* Responsive */
  @media (max-width:900px){ .grid-4 { grid-template-columns: repeat(2,1fr); } .search{ flex:1 } }
  @media (max-width:520px){ 
    .grid-4{ grid-template-columns: 1fr } 
    table th:nth-child(3), table td:nth-child(3){ display:none } 
    table th:nth-child(6), table td:nth-child(6){ display:block; }
  }
</style>

<div class="container">
  <!-- Header -->
  <div class="flex" style="justify-content:space-between; margin-bottom:12px; flex-wrap:wrap">
    <div>
      <h1>Payment Processing Dashboard</h1>
      <div class="muted">Fast connections • quick actions • consistent with Invoice pages</div>
    </div>

    <div class="controls">
      <input id="search" class="search" placeholder="Search invoice or client..." style="min-width:200px"/>
      <select id="statusFilter" class="search">
        <option value="">All status</option>
        <option value="unpaid">Unpaid</option>
        <option value="paid">Paid</option>
        <option value="overdue">Overdue</option>
        <option value="cancelled">Cancelled</option>
      </select>
      <button id="refreshBtn" class="btn btn-ghost">Refresh</button>
      <a href="{{ route('invoices.create') }}" class="btn btn-primary">+ New Invoice</a>
    </div>
  </div>

  <!-- Summary Cards -->
  <div class="grid grid-4" style="margin-bottom:16px">
    <div class="card">
      <div class="muted">Total Due</div>
      <div id="totalDue" style="font-size:20px; font-weight:700; margin-top:6px">₱0.00</div>
      <div class="muted" id="countDue">0 invoices</div>
    </div>

    <div class="card">
      <div class="muted">Total Paid</div>
      <div id="totalPaid" style="font-size:20px; font-weight:700; margin-top:6px">₱0.00</div>
      <div class="muted">Since start</div>
    </div>

    <div class="card">
      <div class="muted">Overdue</div>
      <div id="totalOverdue" style="font-size:20px; font-weight:700; margin-top:6px">₱0.00</div>
      <div class="muted" id="countOverdue">0 invoices</div>
    </div>

    <div class="card">
      <div class="muted">Recent Activity</div>
      <div id="recentActivity" style="margin-top:8px; font-size:13px; color:#374151">—</div>
    </div>
  </div>

  <!-- Invoice Table -->
  <div class="card" style="margin-bottom:12px">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px; flex-wrap:wrap">
      <strong>Invoices</strong>
      <div class="muted">Showing <span id="invoicesCount">0</span> results</div>
    </div>

    <div style="overflow:auto">
      <table id="invoicesTable">
        <thead>
          <tr>
            <th>Invoice #</th>
            <th>Client</th>
            <th>Due</th>
            <th>Amount</th>
            <th>Status</th>
            <th style="width:190px">Actions</th>
          </tr>
        </thead>
        <tbody id="invoicesBody">
          <tr><td colspan="6"><div class="skeleton" style="height:80px"></div></td></tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div style="display:flex; justify-content:flex-end; margin-top:12px; gap:8px">
      <button id="prevPage" class="btn btn-ghost">Prev</button>
      <button id="nextPage" class="btn btn-ghost">Next</button>
    </div>
  </div>
</div>

<!-- Simple Toast -->
<script>
let page = 1, perPage = 10;
let lastSearch = '', lastStatus = '';
let debounceTimer = null;

const formatPeso = v => '₱' + Number(v || 0).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});

async function fetchSummary(){
  try{
    const res = await fetch('/dashboard/payments/summary');
    if(!res.ok) throw res;
    const json = await res.json();
    totalDue.textContent = formatPeso(json.total_due);
    totalPaid.textContent = formatPeso(json.total_paid);
    totalOverdue.textContent = formatPeso(json.total_overdue);
    countDue.textContent = (json.count_due||0)+' invoices';
    countOverdue.textContent = (json.count_overdue||0)+' invoices';
    recentActivity.textContent = json.recent_activity || '—';
  }catch(e){ console.warn('summary fetch failed', e); }
}

async function fetchInvoices(){
  const q = new URLSearchParams({ page, per_page: perPage, search: lastSearch, status: lastStatus });
  try{
    const res = await fetch('/dashboard/payments?'+q.toString());
    if(!res.ok) throw res;
    const json = await res.json();

    invoicesBody.innerHTML = '';
    json.data.forEach(inv=>{
      const tr=document.createElement('tr');
      tr.innerHTML=`
        <td>#${inv.invoice_id}</td>
        <td>${inv.client_name}<div class="muted" style="font-size:12px">${inv.client_email||''}</div></td>
        <td>${inv.due_date} ${isPast(inv.due_date)?'<div class="badge badge-overdue">Overdue</div>':''}</td>
        <td>${formatPeso(inv.total)}</td>
        <td>${statusBadge(inv.status)}</td>
        <td>
          <a class="btn btn-ghost" href="/invoices/${inv.invoice_id}/edit">Edit</a>
          <button class="btn btn-primary" onclick="markPaid(${inv.invoice_id}, this)">Mark Paid</button>
          <button class="btn btn-ghost" onclick="sendReminder(${inv.invoice_id}, this)">Send Reminder</button>
        </td>`;
      invoicesBody.appendChild(tr);
    });

    invoicesCount.textContent = json.total || (json.data ? json.data.length : 0);
    prevPage.disabled = !json.prev_page_url;
    nextPage.disabled = !json.next_page_url;

  }catch(e){ console.warn('invoices fetch failed', e); }
}

function statusBadge(s){
  if(s==='paid') return '<span class="badge badge-paid">Paid</span>';
  if(s==='overdue') return '<span class="badge badge-overdue">Overdue</span>';
  return '<span class="muted">'+(s?s.charAt(0).toUpperCase()+s.slice(1):'Unknown')+'</span>';
}
function isPast(dateStr){ return new Date(dateStr) < new Date(new Date().toDateString()); }

async function markPaid(id, btn){
  const original=btn.textContent; btn.disabled=true; btn.textContent='Processing...';
  try{
    const res=await fetch(`/dashboard/payments/${id}/status`,{
      method:'PUT',
      headers:{
        'Content-Type':'application/json',
        'X-CSRF-TOKEN':'{{ csrf_token() }}'
      },
      body: JSON.stringify({status: 'paid'})
    });
    if(!res.ok) throw res; await res.json();
    await fetchInvoices(); await fetchSummary(); toast('Marked as paid');
  }catch(e){ console.error(e); alert('Failed to mark paid'); }
  finally{ btn.disabled=false; btn.textContent=original; }
}

async function sendReminder(id,btn){
  const original=btn.textContent; btn.disabled=true; btn.textContent='Sending...';
  try{
    const res=await fetch(`/dashboard/payments/${id}/reminder`,{
      method:'POST',
      headers:{
        'Content-Type':'application/json',
        'X-CSRF-TOKEN':'{{ csrf_token() }}'
      }
    });
    if(!res.ok) throw res; await res.json(); toast('Reminder sent');
  }catch(e){ console.error(e); alert('Failed to send reminder'); }
  finally{ btn.disabled=false; btn.textContent=original; }
}

function toast(msg){
  const el=document.createElement('div');
  el.textContent=msg;
  Object.assign(el.style,{position:'fixed',right:'18px',bottom:'18px',padding:'10px 14px',background:'#111827',color:'#fff',borderRadius:'8px',zIndex:9999,transition:'opacity .3s'});
  document.body.appendChild(el); setTimeout(()=>el.style.opacity='0',2600); setTimeout(()=>el.remove(),3000);
}

// events
search.addEventListener('input', e=>{ clearTimeout(debounceTimer); debounceTimer=setTimeout(()=>{ lastSearch=e.target.value.trim(); page=1; fetchInvoices(); },350); });
statusFilter.addEventListener('change', e=>{ lastStatus=e.target.value; page=1; fetchInvoices(); });
refreshBtn.addEventListener('click', ()=>{ fetchInvoices(); fetchSummary(); });
prevPage.addEventListener('click', ()=>{ if(page>1){page--; fetchInvoices();} });
nextPage.addEventListener('click', ()=>{ page++; fetchInvoices(); });

// init
fetchSummary(); fetchInvoices();
</script>

@endsection
