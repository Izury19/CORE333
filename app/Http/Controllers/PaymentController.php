<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Invoice; // optional, if gagamitin mo for dropdown

class PaymentController extends Controller
{
    // Show list of payments
    public function index()
    {
        // eager load 'invoice' relationship if you have it
        $payments = Payment::with('invoice')->orderBy('created_at', 'desc')->get();
        return view('Billing and Invoicing.payment', compact('payments'));
    }

    // Show the form to create a new payment
    public function create()
    {
        // Optionally, load invoices to select from
        // $invoices = Invoice::orderBy('id', 'desc')->get();
        return view('payments.create'); // point to the Blade file for the form
    }

    // Process and store the new payment
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|integer',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:BankTransfer,GCash,PayPal,Cash',
            'payment_date' => 'required|date',
        ]);

        Payment::create([
            'order_id' => $validated['order_id'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'payment_date' => $validated['payment_date'],
            'status' => 'Paid',  // since processing now
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('payments.create')->with('success', 'Payment processed successfully!');
    }

    // Mark a payment as Paid
    public function markPaid($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->status = 'Paid';
        $payment->date_paid = now();
        $payment->save();

        return redirect()->back()->with('success', 'Payment marked as Paid!');
    }
}
