<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice;

    /**
     * Create a new message instance.
     */
    public function __construct($invoice)
    {
        // Accept invoice data as an array or object
        $this->invoice = is_array($invoice) ? (object) $invoice : $invoice;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('📢 Your Updated Invoice')
                    ->markdown('emails.invoice_updated')
                    ->with([
                        'invoice' => $this->invoice,
                    ]);
    }
}
