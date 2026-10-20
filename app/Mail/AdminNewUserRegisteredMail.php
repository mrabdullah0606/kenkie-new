<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNewUserRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public ?string $ipAddress = null
    ) {}

    public function envelope(): Envelope
    {
        $fromAddress = config('mail.from.address', 'info@kenkie.com');
        $fromName = config('mail.from.name', 'Kenkie Store');

        return new Envelope(
            from: new Address($fromAddress, $fromName),
            subject: "New Customer Registration: {$this->user->name} ({$this->user->email})",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-new-user',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
