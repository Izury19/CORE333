<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Invoice; // 👈 Import natin yung Invoice model

class InvoiceCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $invoice; // gagamitin sa blade

    /**
     * Create a new message instance.
     */
    public function __construct(Invoice $invoice) // 👈 enforce model type
    {
        $this->invoice = $invoice;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Invoice from Cali-CMS')
                    ->markdown('emails.invoice') // 👈 ito yung nasa resources/views/emails/invoice.blade.php
                    ->with([
                        'invoice' => $this->invoice, // 👈 pass variable sa blade
                    ]);
    }
}
