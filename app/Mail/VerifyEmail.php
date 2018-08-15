<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyEmail extends Mailable
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
        $subject = 'Paperclip SA Verify Email Address.';
        $name = 'Paperclip SA Team';
        return $this->view('emails.verify_email')
                    ->from($address, $name)
                    ->subject($subject)
                    ->with([ 'message' => $this->data['message'], 'user_id' => $this->data['user_id'], 'verifyToken' => $this->data['verifyToken'] ]);
    }
}
