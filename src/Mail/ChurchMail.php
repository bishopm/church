<?php

namespace Bishopm\Church\Mail;

use Bishopm\Church\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class ChurchMail extends Mailable
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
        if ((isset($this->data['sender'])) and ($this->data['sender']<>0)){
            $user=User::find($this->data['sender']);
            $sender=$user->email;
            $sendername=$user->name;
        } else {
            $sender=setting('email.church_email');
            $sendername=setting('general.church_name');
        }
        return new Envelope(
            subject: $this->data['subject'],
            replyTo: [
                new Address($sender, $sendername),
            ],
            from: new Address($sender, $sendername)
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'church::mail.templates.email',
            with: [
                'firstname' => $this->data['firstname'],
                'url' => setting('general.church_website'),
                'subject' => $this->data['subject'] ?? 'Message from ' . setting('general.church_name'),
                'body' => $this->data['body'],
                'sender' => $this->data['sendername'] ?? ''
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
        if ((array_key_exists('attachment',$this->data)) and ($this->data['attachment']<>'')){
            return [
                Attachment::fromPath(storage_path($this->data['attachment']))
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
