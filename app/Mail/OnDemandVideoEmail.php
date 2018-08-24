<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OnDemandVideoEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    public $data;

    public function __construct($data)
    {
        //
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $address = 'noreply@paperclipsa.co.za';
        $subject = 'Paperclip SA Notification.';
        $name = 'Paperclip SA Team';

        return $this->markdown('emails.notification')
                    ->from($address)
                    ->subject($subject)
                    ->with(['email' => $this->data['email'], 'name' => $this->data['name'], 'message' => $this->data['message'], 'url_link' => $this->data['url_link']]);
    }
}
