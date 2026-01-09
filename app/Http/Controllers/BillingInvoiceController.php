<?php

namespace App\Http\Controllers;

use App\Models\BillingInvoice;
use Illuminate\Http\Request;

class BillingInvoiceController extends Controller
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
            'equipment_type' => 'required|in:crane,truck',
            'equipment_id' => 'required|string|max=100',
            'hours_used' => 'required|integer|min=1',
            'hourly_rate' => 'required|numeric|min=0',
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
            ]);
        }

        // ✅ Generate UID & Save
        $uid = BillingInvoice::generateUid($request->equipment_type);
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
            'status' => 'billed'
        ]);

        return redirect()->route('billing.invoices.index')->with('success', 'Invoice generated: ' . $uid);
    }

    public function show($id)
    {
        $invoice = BillingInvoice::findOrFail($id);
        return view('Billing and Invoicing.show', compact('invoice'));
    }
}