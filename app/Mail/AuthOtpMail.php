<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AuthOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $code,
        public string $type = 'login', // 'login' or 'registration'
        public string $userName = 'Customer'
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->type === 'registration'
            ? "Your {$this->code} Registration Verification Code - Kenkie"
            : "Your {$this->code} Login Verification Code - Kenkie";

        $fromAddress = config('mail.from.address', 'info@kenkie.com');
        $fromName = config('mail.from.name', 'Kenkie');

        return new Envelope(
            from: new Address($fromAddress, $fromName),
            subject: $subject,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.auth-otp',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
