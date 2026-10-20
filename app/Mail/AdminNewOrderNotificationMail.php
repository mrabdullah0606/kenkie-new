<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNewOrderNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order
    ) {}

    public function envelope(): Envelope
    {
        $fromAddress = config('mail.from.address', 'info@kenkie.com');
        $fromName = config('mail.from.name', 'Kenkie Store');

        $riskTag = $this->order->risk_score === 'high' ? ' [HIGH RISK]' : '';

        return new Envelope(
            from: new Address($fromAddress, $fromName),
            subject: "New Order #{$this->order->order_number} (£{$this->order->total}) from {$this->order->customer_name}{$riskTag}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-new-order',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
