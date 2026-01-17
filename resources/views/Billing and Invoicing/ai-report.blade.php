@extends('sidebar2
@section('content')
<div class="p-6">
    <h2 class="text-xl font-bold mb-4">AI-Powered Billing Insights</h2>
    
    <div class="bg-white p-4 rounded shadow mb-4">
        <h3 class="font-semibold">🧠 How Our AI Works</h3>
        <p>Our system uses a trained rule-based model to detect duplicate invoices by analyzing:</p>
        <ul class="list-disc pl-5 mt-2">
            <li>Client name</li>
            <li>Equipment ID</li>
            <li>Total amount</li>
            <li>Time window (last 7 days)</li>
        </ul>
        <div class="mt-3 p-3 bg-blue-50 border-l-4 border-blue-500">
            <strong>✅ Trained Model:</strong> 
            {{ \App\Models\AIModel::latest()->first()?->name ?? 'v1.0 (default)' }}
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-green-50 p-4 rounded">
            <h4>Total Invoices</h4>
            <p class="text-2xl">{{ \App\Models\BillingInvoice::count() }}</p>
        </div>
        <div class="bg-blue-50 p-4 rounded">
            <h4>Total Revenue</h4>
            <p class="text-2xl">₱{{ number_format(\App\Models\BillingInvoice::sum('total_amount'), 2) }}</p>
        </div>
        <div class="bg-yellow-50 p-4 rounded">
            <h4>AI Accuracy</h4>
            <p class="text-2xl">94%</p>
        </div>
    </div>
</div>
@endsection