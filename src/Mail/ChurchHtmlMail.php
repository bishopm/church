<?php

namespace Bishopm\Church\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class ChurchHtmlMail extends Mailable
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
            subject: $this->data['subject'],
            from: new Address(setting('email.mail_from_address'),setting('email.mail_from_name')),
            replyTo: [
                new Address(setting('email.church_email'),setting('general.church_name')),
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'church::mail.templates.htmlemail',
            with: [
                'firstname' => $this->data['firstname'],
                'subject' => $this->data['subject'],
                'body' => $this->data['body']
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
        if (array_key_exists('attachment',$this->data)){
            return [
                Attachment::fromPath(storage_path('app/public/' . $this->data['attachment']))
            ]; 
        } elseif ((array_key_exists('attachdata',$this->data)) and ($this->data['attachdata']<>'')){
            return [
                Attachment::fromData(fn () => base64_decode($this->data['attachdata']), $this->data['attachname'])
                ->withMime('application/pdf'),
            ]; 
        } else {
            return [];
        }
    }
}
