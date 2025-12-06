<?php

namespace App\Mail;

use App\Models\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PersonLikesThresholdMail extends Mailable
{
    use Queueable, SerializesModels;

    public $person;
    public $likesCount;

    /**
     * Create a new message instance.
     */
    public function __construct(Person $person, int $likesCount)
    {
        $this->person = $person;
        $this->likesCount = $likesCount;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🎉 Person Likes Threshold Reached - ' . $this->person->name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.person-likes-threshold',
            with: [
                'personName' => $this->person->name,
                'personAge' => $this->person->age,
                'personLocation' => $this->person->location,
                'likesCount' => $this->likesCount,
                'personId' => $this->person->id,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
