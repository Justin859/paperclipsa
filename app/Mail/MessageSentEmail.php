<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MessageSentEmail extends Mailable
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
        $subject = 'You Have Submitted a Query to Paperclip SA';
        $name = 'Paperclip SA Team';
        return $this->view('emails.messsage_sent')
                    ->from($address, $name)
                    ->subject($subject)
                    ->with([ 'name' => $this->data['name'], 'email' => $this->data['email'], 'user_query' => $this->data['user_query'] ]);
    }
}
