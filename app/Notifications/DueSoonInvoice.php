<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DueSoonInvoice extends Notification
{
    public $invoice;

    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('⏰ Invoice Due Soon!')
            ->greeting('Hey ' . $this->invoice->client_name . ',')
            ->line('This is a reminder that your invoice is due soon.')
            ->line('**Due Date:** ' . $this->invoice->due_date->format('F d, Y'))
            ->line('**Total Amount:** ₱' . number_format($this->invoice->total, 2))
            ->line('Please make sure to settle the payment before the due date.')
            ->action('View Invoice', url('/your-invoice-url')) // optional
            ->line('Thank you!');
    }
}
