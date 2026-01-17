<?php

namespace App\Http\Controllers;

use App\Models\BillingInvoice;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = BillingInvoice::latest()->get();
        return view('Billing and Invoicing.invoice-list', compact('invoices'));
    }

    public function create()
    {
        return view('Billing and Invoicing.invoice');
    }

   public function store(Request $request)
{
    $request->validate([
        'client_name' => 'required|string|max:255',
        'equipment_type' => 'required|in:crane,truck,Generator,Air Compressor,Water Pump,Crane',
        'equipment_id' => 'required|string|max:100',
        'hours_used' => 'required|integer|min:1|max:1000',
        'hourly_rate' => 'required|numeric|min:0|max:999999',
        'billing_period_start' => 'required|date',
        'billing_period_end' => 'required|date|after_or_equal:billing_period_start',
    ]);

    // 🔍 AI SIMULATION: Check for duplicates (last 7 days)
    $totalAmount = $request->hours_used * $request->hourly_rate;
    $similar = BillingInvoice::where('client_name', $request->client_name)
        ->where('equipment_id', $request->equipment_id)
        ->where('total_amount', $totalAmount)
        ->whereBetween('created_at', [now()->subDays(7), now()])
        ->exists();

    if ($similar) {
        return back()->withErrors([
            'duplicate_warning' => '⚠️ AI Alert: A similar invoice was created in the last 7 days. Please verify if this is intentional.'
        ])->withInput();
    }

    // ✅ Temporary UID (kung ayaw gumana ang generateUid)
    $uid = 'INV-' . now()->format('Y') . '-' . str_pad(BillingInvoice::count() + 1, 4, '0', STR_PAD_LEFT);

    BillingInvoice::create([
        'invoice_uid' => $uid,
        'contract_id' => null,
        'client_name' => $request->client_name,
        'equipment_type' => $request->equipment_type,
        'equipment_id' => $request->equipment_id,
        'hours_used' => $request->hours_used,
        'hourly_rate' => $request->hourly_rate,
        'total_amount' => $totalAmount,
        'billing_period_start' => $request->billing_period_start,
        'billing_period_end' => $request->billing_period_end,
        'status' => 'issued',
    ]);

    return redirect()->route('invoices.index')->with('success', 'Invoice generated: ' . $uid);
}

    public function show($id)
    {
        $invoice = BillingInvoice::findOrFail($id);
        return view('Billing and Invoicing.show', compact('invoice'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'in:issued,paid,overdue']);
        $invoice = BillingInvoice::findOrFail($id);
        $invoice->status = $request->status;
        $invoice->save();

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back()->with('success', 'Status updated to ' . $request->status);
    }

    public function exportPdf($id)
    {
        $invoice = BillingInvoice::findOrFail($id);
        $pdf = Pdf::loadView('Billing and Invoicing.pdf', compact('invoice'));
        return $pdf->download('invoice_' . $invoice->invoice_uid . '.pdf');
    }

    public function destroy($id)
    {
        $invoice = BillingInvoice::findOrFail($id);
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted.');
    }

    public function update(Request $request, $id)
    {
        return $this->updateStatus($request, $id);
    }
}