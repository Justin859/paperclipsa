<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactEmail extends Mailable
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
        $subject = 'Paperclip SA Online Query.';
        $name = 'Paperclip SA Team';
        return $this->view('emails.contact')
                    ->from($address, $name)
                    ->subject($subject)
                    ->with([ 'email' => $this->data['email'], 'name' => $this->data['name'], 'user_query' => $this->data['user_query'] ]);
    }
}
