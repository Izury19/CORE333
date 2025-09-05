<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\InvoiceCreatedMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Invoice;
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
}
