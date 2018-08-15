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

        \Mail::to($request->email)->send(new MessageSentEmail($data));
        \Mail::to('justin@paperclipsa.co.za')->send(new ContactEmail($data));

        \Session::flash('success', 'Your query has been sent.');

        return redirect()->back();

    }
}
