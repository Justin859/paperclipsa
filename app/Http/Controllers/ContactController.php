<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\ContactEmail;
use App\Mail\MessageSentEmail;

class ContactController extends Controller
{
    //

    public function contact_page()
    {


        return view('contact.contact_page');

    }

    public function send_query(Request $request)
    {

        $validatedData = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|string',
            'user_query' => 'required|string'
        ]);

        $data = [ 'user_query' => $request->user_query, 'email' => $request->email, 'name' => $request->name];

        $beautymail = app()->make(\Snowfire\Beautymail\Beautymail::class);
        $beautymail->send('emails.contact', $data, function($message) use($data)
        {

            $message
                ->from('noreply@paperclipsa.co.za')
                ->to('justin@yourdev.co.za', 'Justin')
                ->subject('Paperclip SA Online Query');
        });

        $beautymail_message_sent = app()->make(\Snowfire\Beautymail\Beautymail::class);
        $beautymail_message_sent->send('emails.messsage_sent', $data, function($message) use($data)
        {

            $message
                ->from('noreply@paperclipsa.co.za')
                ->to($data['email'], $data['name'])
                ->subject('Paperclip SA Query Sent');
        });

        // \Mail::to($request->email)->send(new MessageSentEmail($data));
        // \Mail::to('justin@paperclipsa.co.za')->send(new ContactEmail($data));

        \Session::flash('success', 'Your query has been sent.');

        return redirect()->back();

    }
}
