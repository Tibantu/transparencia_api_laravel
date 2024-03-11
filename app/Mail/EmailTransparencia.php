<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EmailTransparencia extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   */
  public function __construct(private string $title, private string  $body)
  {
  }

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope
  {
      return new Envelope(
          subject: 'Bem vindo ao trasparência',
      );
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content
  {
      return new Content(
          view: 'emails.bemvindo',
          with: [
              'title' => $this->title,
              'body' => $this->body,
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
        return [];
    }
}
