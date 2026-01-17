<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RecordController extends Controller
{
  public function index(Request $request)
{
    $query = \DB::table('billing_invoices')
        ->leftJoin('records', 'billing_invoices.id', '=', 'records.invoice_id')
        ->select(
            'billing_invoices.id as invoice_id',
            'billing_invoices.client_name',
            'billing_invoices.equipment_type',
            'billing_invoices.total_amount',
            'billing_invoices.status as invoice_status',
            'records.payment_uid',
            'records.payment_method',
            'records.reference_number',
            'records.status as payment_status',
            'records.total as amount_paid',
            'records.created_at as payment_date'
        );

    // Filter by status
    if ($request->filled('status')) {
        if ($request->status === 'pending') {
            $query->whereNull('records.status');
        } elseif ($request->status === 'completed') {
            $query->whereNotNull('records.status');
        }
    }

    // Search functionality
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('billing_invoices.client_name', 'like', "%{$search}%")
              ->orWhere('billing_invoices.id', 'like', "%{$search}%")
              ->orWhere('records.reference_number', 'like', "%{$search}%");
        });
    }

    // Date range filter
    if ($request->filled('start_date') && $request->filled('end_date')) {
        $query->whereBetween('records.created_at', [$request->start_date, $request->end_date]);
    }

    // Sorting
    $sortBy = $request->get('sort_by', 'invoice_id');
    $sortOrder = $request->get('sort_order', 'desc');
    $query->orderBy($sortBy, $sortOrder);

    $payments = $query->get();

    // Rest of your metrics code...
    $totalReceived = \DB::table('records')->where('status', 'completed')->sum('total');
    $totalInvoices = \DB::table('billing_invoices')->count();
    $paidInvoices = \DB::table('billing_invoices')->where('status', 'paid')->count();
    $collectionRate = $totalInvoices > 0 ? round(($paidInvoices / $totalInvoices) * 100, 1) : 0;

    $unpaidInvoices = \DB::table('billing_invoices')->where('status', 'billed')->get();

    return view('Record and Payment.index', compact(
        'payments',
        'totalReceived',
        'collectionRate',
        'unpaidInvoices'
    ));
}

   public function store(Request $request)
{
    $request->validate([
        'invoice_id' => 'required|exists:billing_invoices,id',
        'payment_mode' => 'required|in:cash,bank,gcash,check',
        'amount_paid' => 'required|numeric|min=0',
        'reference_number' => 'required|string|max=255'
    ]);

    // Get the invoice
    $invoice = \App\Models\BillingInvoice::findOrFail($request->invoice_id);
    
    // Verify amount matches invoice total
    if ($request->amount_paid != $invoice->total_amount) {
        return back()->withErrors(['amount_paid' => 'Payment amount must match invoice total: ₱' . number_format($invoice->total_amount, 2)]);
    }

    // Update existing record or create new
    $record = \App\Models\Record::where('invoice_id', $invoice->id)->first();
    
    if ($record) {
        // Update existing record
        $record->update([
            'payment_uid' => 'PAY-' . now()->format('Y') . '-' . str_pad($invoice->id, 4, '0', STR_PAD_LEFT),
            'payment_type' => 'client',
            'client_name' => $invoice->client_name,
            'total' => $request->amount_paid,
            'payment_method' => $request->payment_mode, // ← DITO ANG TAMA
            'reference_number' => $request->reference_number,
            'status' => 'completed'
        ]);
    } else {
        // Create new record
        $record = \App\Models\Record::create([
            'invoice_id' => $invoice->id,
            'payment_uid' => 'PAY-' . now()->format('Y') . '-' . str_pad($invoice->id, 4, '0', STR_PAD_LEFT),
            'payment_type' => 'client',
            'client_name' => $invoice->client_name,
            'total' => $request->amount_paid,
            'payment_method' => $request->payment_mode, // ← DITO ANG TAMA
            'reference_number' => $request->reference_number,
            'status' => 'completed'
        ]);
    }

    // Update invoice status to paid
    $invoice->update(['status' => 'paid']);

    return redirect()->route('record.index')
        ->with('success', 'Payment recorded successfully! Invoice status updated to PAID.');
}
}