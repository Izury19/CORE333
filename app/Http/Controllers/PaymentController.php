<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Invoice;

class PaymentController extends Controller
{
    // Show list of payments (for admin checking)
    public function index()
    {
        $payments = Payment::with('invoice')->orderBy('created_at', 'desc')->get();
        return view('Record And Payment.manage-payment', compact('payments'));
    }

    // Show upload form for client
    public function create($invoiceId)
    {
        $invoice = Invoice::findOrFail($invoiceId); 
        return view('Record And Payment.payment-create', compact('invoice'));
    }

    // Store uploaded proof of payment
    public function store(Request $request, $invoiceId)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'proof' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        // Upload proof file
        $proofPath = $request->file('proof')->store('proofs', 'public');

        Payment::create([
            'invoice_id'   => $invoiceId,
            'amount'       => $validated['amount'],
            'proof'        => $proofPath,
            'status'       => 'pending',
            'payment_date' => now(), // ✅ match sa DB column
        ]);

        return redirect()->back()->with('success', 'Proof of payment uploaded successfully! Please wait for verification.');
    }

    // Mark a payment as approved (for admin)
    public function markApproved($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->status = 'approved';
        $payment->save();

        return redirect()->back()->with('success', 'Payment marked as Approved!');
    }

    // Mark a payment as rejected (for admin)
    public function markRejected($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->status = 'rejected';
        $payment->save();

        return redirect()->back()->with('error', 'Payment has been Rejected!');
    }
}
