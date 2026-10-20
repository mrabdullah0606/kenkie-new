<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerOrderConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order
    ) {}

    public function envelope(): Envelope
    {
        $fromAddress = config('mail.from.address', 'info@kenkie.com');
        $fromName = config('mail.from.name', 'Kenkie');

        return new Envelope(
            from: new Address($fromAddress, $fromName),
            subject: "Your Kenkie Order Confirmation #{$this->order->order_number}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.customer-order-confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
