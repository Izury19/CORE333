<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Record;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    /**
     * Display a listing of receipts.
     */
    public function index()
    {
        // eager load invoice para makuha agad client_name, etc.
        $records = Record::with(['items', 'invoice'])
            ->latest()
            ->paginate(10);

        return view('Billing and Invoicing.record', compact('records'));
    }

    /**
     * Generate a receipt from a given invoice.
     */
    public function generateReceipt($invoiceId)
    {
        // Kunin yung invoice + items
        $invoice = Invoice::with('items')->findOrFail($invoiceId);

        // Check kung meron nang receipt para sa invoice na ito
        $existingRecord = Record::where('invoice_id', $invoice->invoice_id)->first();
        if ($existingRecord) {
            return redirect()->route('record')
                ->with('success', 'Receipt for Invoice #'.$invoice->invoice_id.' already exists.');
        }

        // Gumawa ng bagong receipt
        $record = Record::create([
            'invoice_id'     => $invoice->invoice_id,
            'client_name'    => $invoice->client_name,
            'client_email'   => $invoice->client_email,
            'client_address'   => $invoice->client_address,
            'total'          => $invoice->total,
            'payment_method' => $invoice->terms_of_payment ?? 'N/A',
            'status'         => $invoice->status ?? 'pending',
        ]);

        // Kopyahin lahat ng invoice items papunta sa record_items
        foreach ($invoice->items as $item) {
            $record->items()->create([
                'description' => $item->description,
                'qty'         => $item->qty,
                'price'       => $item->price,
                'total'       => $item->total,
            ]);
        }

        return redirect()->route('record')
            ->with('success', 'Receipt for Invoice #'.$invoice->invoice_id.' generated successfully!');
    }
}
