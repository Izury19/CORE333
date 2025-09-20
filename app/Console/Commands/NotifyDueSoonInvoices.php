<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NotifyDueSoonInvoices extends Notification
{
    use Queueable;

    protected $invoice;

    /**
     * Create a new notification instance.
     */
    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('⏰ Reminder: Your Invoice is Due Soon')
            ->greeting('Hi ' . $this->invoice->client_name . ',')
            ->line('This is a reminder that your invoice is due soon.')
            ->line('Due Date: ' . $this->invoice->due_date->format('F d, Y'))
            ->line('Total Amount: ₱' . number_format($this->invoice->total, 2))
            ->action('View Invoice', url('/invoices/' . $this->invoice->invoice_id))
            ->line('Please make sure to settle the payment before the due date.')
            ->line('Thank you for your business!');
    }
}
