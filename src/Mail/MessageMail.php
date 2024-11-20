<?php

namespace Bishopm\Church\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;

class MessageMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(protected array $data)
    {
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Message from website",
            from: new Address(
                Config::get('mail.mailers.' . setting('email.mailer') . '.from_address'),
                Config::get('mail.mailers.' . setting('email.mailer') . '.from_name')
            ),
            replyTo: [
                new Address($this->data['user'], 'Website feedback'),
            ]
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'church::mail.templates.message',
            with: [
                'subject' => 'Message from website',
                'body' => $this->data['message'],
                'useremail' => $this->data['user']
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
        ];
    }
}
