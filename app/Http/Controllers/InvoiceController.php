<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\InvoiceCreatedMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Invoice;
use App\Mail\InvoiceUpdated;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;  // <- import DB facade

class InvoiceController extends Controller
{
    public function create()
    {
        return view('Billing and Invoicing.invoice');
    }

    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $items = $validated['items'];

        // Compute subtotal
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['qty'] * $item['price'];
        }

        // Tax and total
        $tax = $subtotal * 0.15;
        $total = $subtotal + $tax;

        // Save to `invoices` table
        $invoice = Invoice::create([
            'client_name' => $validated['client_name'],
            'client_email' => $validated['client_email'],
            'invoice_date' => $validated['invoice_date'],
            'due_date' => $validated['due_date'],
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
        ]);

        // Save items to `invoice_items` table
        foreach ($items as $item) {
            $invoice->items()->create([
                'description' => $item['description'],
                'qty' => $item['qty'],
                'price' => $item['price'],
                'total' => $item['qty'] * $item['price'],
                'client_name' => $validated['client_name'],
                'client_email' => $validated['client_email'], 
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'],
            ]);
        }

        // Email data
        $invoiceData = [
            'client_name' => $invoice->client_name,
            'invoice_date' => $invoice->invoice_date,
            'due_date' => $invoice->due_date,
            'items' => $items,
            'subtotal' => $subtotal,
            'tax' => $tax,
            'total' => $total,
        ];

        // Send email
        Mail::to($invoice->client_email)->send(new InvoiceCreatedMail($invoiceData));

        // Redirect back with flash message
        return redirect()
            ->route('invoice.create')
            ->with('success', 'Invoice Created, Saved, and Email Sent Successfully!')
            ->with('invoice', $invoiceData);
    }

    public function delivery()
    {
        $invoices = Invoice::with('items')->get(); // Load items
        return view('Billing and Invoicing.delivery', compact('invoices'));
    }

    // New method for grouped invoice list
    public function indexGrouped()
    {
        $invoices = DB::table('invoice_items')
            ->select('client_name', 'client_email', 'invoice_date', 'due_date', 'subtotal', 'tax', 'total')
            ->groupBy('client_name', 'client_email', 'invoice_date', 'due_date', 'subtotal', 'tax', 'total')
            ->get();

        return view('Billing and Invoicing.invoices_grouped', compact('invoices'));
    }
   public function edit($invoice_id)
{
    $invoice = Invoice::findOrFail($invoice_id);
    return view('Billing and Invoicing.edit', compact('invoice'));
}

public function update(Request $request, $invoice_id)
{
    $invoice = Invoice::findOrFail($invoice_id);

    $validated = $request->validate([
        'client_name'   => 'required|string|max:255',
        'client_email'  => 'required|email',
        'invoice_date'  => 'required|date',
        'due_date'      => 'required|date|after_or_equal:invoice_date',
        'items'         => 'required|array|min:1',
        'items.*.description' => 'required|string',
        'items.*.qty' => 'required|numeric|min:1',
        'items.*.price' => 'required|numeric|min:0',
        'items.*.total' => 'required|numeric|min:0',
        'total'         => 'required|numeric',
    ]);

    // ✅ Recompute subtotal & tax (in case user tampers)
    $subtotal = 0;
    foreach ($validated['items'] as $item) {
        $subtotal += $item['qty'] * $item['price'];
    }

    $tax = $subtotal * 0.15;
    $total = $subtotal + $tax;

    // ✅ Update invoice
    $invoice->update([
        'client_name'   => $validated['client_name'],
        'client_email'  => $validated['client_email'],
        'invoice_date'  => $validated['invoice_date'],
        'due_date'      => $validated['due_date'],
        'subtotal'      => $subtotal,
        'tax'           => $tax,
        'total'         => $total,
    ]);

    // ✅ Delete old items
    $invoice->items()->delete();

    // ✅ Create new items
    foreach ($validated['items'] as $item) {
        $invoice->items()->create([
            'description'   => $item['description'],
            'qty'           => $item['qty'],
            'price'         => $item['price'],
            'total'         => $item['total'],
            'client_name'   => $validated['client_name'],
            'client_email'  => $validated['client_email'],
            'invoice_date'  => $validated['invoice_date'],
            'due_date'      => $validated['due_date'],
        ]);
    }

    // ✅ Send updated invoice via email
    Mail::to($invoice->client_email)->send(new InvoiceUpdated($invoice));

    return redirect()->route('invoices.edit', $invoice_id)
                     ->with('success', 'Invoice updated and re-sent to client successfully.');
}

public function destroy($invoice_id)
{
    $invoice = Invoice::findOrFail($invoice_id);
    $invoice->delete();

    return redirect()->route('invoices.index')
                     ->with('success', 'Invoice deleted successfully.');
}
public function index()
{
    $invoices = Invoice::with('items')->get(); // kunin lahat ng invoices kasama ang items
    return view('Billing and Invoicing.delivery', compact('invoices'));
}
}
