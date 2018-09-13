<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SearchController extends Controller
{
    //
    public function searchUsers(Request $request)
    {
        return \App\User::where('email', 'LIKE', '%'.$request->q.'%')->get();
    }

    public function searchStreams(Request $request)
    {
        return \App\Stream::where('name', 'LIKE', '%'.$request->q.'%')->get();
    }
}
