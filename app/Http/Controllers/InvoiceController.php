<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\InvoiceCreatedMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Invoice;
use App\Mail\InvoiceUpdated;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function create()
    {
        return view('Billing and Invoicing.invoice');
    }

    public function store(Request $request)
    {
        // ✅ Validate input
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'note' => 'nullable|string',
            'client_address' => 'nullable|string',
            'terms_of_payment' => 'nullable|string',
        ]);

        $items = $validated['items'];

        // ✅ Compute subtotal, tax, total
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['qty'] * $item['price'];
        }
        $tax = $subtotal * 0.15;
        $total = $subtotal + $tax;

        // ✅ Save invoice
        $invoice = Invoice::create([
            'client_name'      => $validated['client_name'],
            'client_email'     => $validated['client_email'],
            'invoice_date'     => $validated['invoice_date'],
            'due_date'         => $validated['due_date'],
            'terms_of_payment' => $request->input('terms_of_payment'),
            'note'             => $request->input('note'),
            'client_address'   => $request->input('client_address'),
            'subtotal'         => $subtotal,
            'tax'              => $tax,
            'total'            => $total,
        ]);

        // ✅ Save invoice items
        foreach ($items as $item) {
            $invoice->items()->create([
                'description'   => $item['description'],
                'qty'           => $item['qty'],
                'price'         => $item['price'],
                'total'         => $item['qty'] * $item['price'],
                'client_name'   => $validated['client_name'],
                'client_email'  => $validated['client_email'],
                'invoice_date'  => $validated['invoice_date'],
                'due_date'      => $validated['due_date'],
            ]);
        }

        // ✅ Send email with invoice model
        Mail::to($invoice->client_email)->send(new InvoiceCreatedMail($invoice));

        // ✅ Redirect with preview
        return redirect()
            ->route('invoice.create')
            ->with('success', 'Invoice Created, Saved, and Email Sent Successfully!')
            ->with('invoice', $invoice->load('items')); // include items sa session
    }

    public function delivery()
    {
        $invoices = Invoice::with('items')->get();
        return view('Billing and Invoicing.delivery', compact('invoices'));
    }

    // 🔥 Billing Records page (lahat ng invoices)
    public function record()
    {
        $invoices = Invoice::all();
        return view('Billing and Invoicing.record', ['invoices' => $invoices]);
    }

    // 🔥 Single invoice receipt view
    public function show($id)
    {
        $invoice = Invoice::with('items')->findOrFail($id);
        return view('Billing and Invoicing.receipt', compact('invoice'));
    }

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
        $invoice = Invoice::with('items')->findOrFail($invoice_id);
        return view('Billing and Invoicing.edit', compact('invoice'));
    }

    public function update(Request $request, $invoice_id)
    {
        $invoice = Invoice::findOrFail($invoice_id);

        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.qty' => 'required|numeric|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
            'total' => 'required|numeric',
        ]);

        // ✅ Recompute totals
        $subtotal = 0;
        foreach ($validated['items'] as $item) {
            $subtotal += $item['qty'] * $item['price'];
        }
        $tax = $subtotal * 0.15;
        $total = $subtotal + $tax;

        // ✅ Update invoice
        $invoice->update([
            'client_name'      => $validated['client_name'],
            'client_email'     => $validated['client_email'],
            'invoice_date'     => $validated['invoice_date'],
            'due_date'         => $validated['due_date'],
            'terms_of_payment' => $request->input('terms_of_payment'),
            'note'             => $request->input('note'),
            'client_address'   => $request->input('client_address'),
            'subtotal'         => $subtotal,
            'tax'              => $tax,
            'total'            => $total,
        ]);

        // ✅ Replace items
        $invoice->items()->delete();
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

        // ✅ Send updated invoice
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

    public function index(Request $request)
    {
        $search = $request->input('search');
        $month = $request->input('month');
        $year = $request->input('year');

        $invoices = Invoice::with('items')
            ->when($search, fn($q) => $q->where('client_name', 'LIKE', "%{$search}%"))
            ->when($month, fn($q) => $q->whereMonth('invoice_date', $month))
            ->when($year, fn($q) => $q->whereYear('invoice_date', $year))
            ->orderBy('invoice_date', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('Billing and Invoicing.delivery', compact('invoices', 'search', 'month', 'year'));
    }
}
