<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Record;

class ReceiptController extends Controller
{
    /**
     * Display a listing of the receipts.
     */
    public function index()
    {
        // Kunin lahat ng records kasama items
        $records = Record::with('items')->latest()->paginate(6);
        return view('record', compact('records')); // 🔄 papunta sa record.blade.php
    }

    /**
     * Store a newly created receipt in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'invoice_id'     => 'required|integer',
            'client_name'    => 'required|string|max:255',
            'client_email'   => 'nullable|email',
            'client_address' => 'nullable|string|max:255',
            'payment_method' => 'nullable|string|max:255',
            'status'         => 'required|string',
            'items'          => 'required|array',
            'items.*.description' => 'required|string|max:255',
            'items.*.qty'    => 'required|integer|min:1',
            'items.*.price'  => 'required|numeric|min:0',
        ]);

        // 🔎 Check kung may existing receipt para sa invoice
        $existing = Record::where('invoice_id', $request->invoice_id)->first();
        if ($existing) {
            return redirect()->route('record')
                ->with('error', "Receipt for Invoice #{$request->invoice_id} already exists.");
        }

        // 🧮 Compute Subtotal + Tax + Total
        $subtotal = collect($request->items)->sum(fn($item) => $item['qty'] * $item['price']);
        $tax = $subtotal * 0.15; // 15% tax, pwede mong ilagay sa .env
        $total = $subtotal + $tax;

        // ✅ Create kung wala pang existing
        $record = Record::create([
            'invoice_id'     => $request->invoice_id,
            'client_name'    => $request->client_name,
            'client_email'   => $request->client_email,
            'client_address' => $request->client_address,
            'payment_method' => $request->payment_method,
            'status'         => $request->status,
            'subtotal'       => $subtotal,
            'tax'            => $tax,
            'total'          => $total,
        ]);

        // 💾 Save items
        foreach ($request->items as $item) {
            $record->items()->create($item);
        }

        return redirect()->route('record')
            ->with('success', 'Receipt created successfully!');
    }

    /**
     * Display the specified receipt.
     */
    public function show($id)
    {
        $record = Record::with('items')->where('record_id', $id)->firstOrFail();
        return view('receipts.show', compact('record'));
    }

    /**
     * Show the form for editing the specified receipt.
     */
    public function edit($id)
    {
        $record = Record::with('items')->findOrFail($id);
        return view('receipts.edit', compact('record'));
    }

    /**
     * Update the specified receipt in storage.
     */
    public function update(Request $request, $id)
    {
        $record = Record::with('items')->findOrFail($id);

        $request->validate([
            'client_name'    => 'required|string|max:255',
            'client_email'   => 'nullable|email',
            'client_address' => 'nullable|string|max:255',
            'payment_method' => 'nullable|string|max:255',
            'status'         => 'required|string',
            'items'          => 'required|array',
            'items.*.description' => 'required|string|max:255',
            'items.*.qty'    => 'required|integer|min:1',
            'items.*.price'  => 'required|numeric|min:0',
        ]);

        // 🧮 Compute totals
        $subtotal = collect($request->items)->sum(fn($item) => $item['qty'] * $item['price']);
        $tax = $subtotal * 0.15;
        $total = $subtotal + $tax;

        $record->update([
            'client_name'    => $request->client_name,
            'client_email'   => $request->client_email,
            'client_address' => $request->client_address,
            'payment_method' => $request->payment_method,
            'status'         => $request->status,
            'subtotal'       => $subtotal,
            'tax'            => $tax,
            'total'          => $total,
        ]);

        // 🔄 Sync items (delete old + insert new)
        $record->items()->delete();
        foreach ($request->items as $item) {
            $record->items()->create($item);
        }

        return redirect()->route('record')
            ->with('success', 'Receipt updated successfully!');
    }

    /**
     * Remove the specified receipt from storage.
     */
    public function destroy($id)
    {
        $record = Record::findOrFail($id);
        $record->delete();

        return redirect()->route('record')
            ->with('success', 'Receipt deleted successfully!');
    }
}
