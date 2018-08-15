<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class VoucherController extends Controller
{
    //

    public function enter_voucher()
    {
        $user = \Auth::user();

        $is_referee = \App\Referee::where('user_id', $user->id)->first();
        $is_admin = \App\Admin::where('user_id', $user->id)->first();

        return view('users.submit_voucher', ['is_referee' => $is_referee, 'is_admin' => $is_admin]);
    }

    public function check_voucher(Request $request)
    {

        $user = Auth::user();
        $user_account_balance = \App\AccountBalance::where('user_id', $user->id)->first();
        $voucher_key = \App\Voucher::where('voucher_key', $request->voucher_key)->first();

        if($voucher_key)
        {
            if(!$voucher_key->used)
            {
                if($user_account_balance)
                {
                    $user_account_balance->balance_value = (string)((int)$user_account_balance->balance_value + (int)$voucher_key->points_value);
                    $voucher_key->used = true;
                    $voucher_key->save();
                    $user_account_balance->save();

                    \Session::flash('success', " Your voucher has been successfully been redeemed.");
                    return redirect()->to('/user-profile/submit-voucher');
                } else {
                    $new_user_account_balance = \App\AccountBalance::create(['user_id' => $user->id, 'balance_value' => $voucher_key->points_value, 'balance_currency' => 'points']);
                    \Session::flash('succcess', " Your voucher has been successfully been redeemed.");
                    return redirect()->to('/user-profile/submit-voucher');
                }
            } else {
                \Session::flash('error', " This voucher key has already been used.");
                return redirect()->to('/user-profile/submit-voucher');
            }
        } else {
            \Session::flash('error', " This voucher key does not exist.");
            return redirect()->to('/user-profile/submit-voucher');
        }


    }
}
