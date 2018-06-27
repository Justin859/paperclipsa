<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class PromocodeController extends Controller
{
    //
    public function enter_promocode()
    {

        return view('public.promocode');

    }

    public function check_promocode(Request $request)
    {
        $promo = \App\Promocode::where('code', $request->code)->first();
        $user = Auth::user();
        $used_code = \App\UsedPromocode::where('user_id', $user->id)->first();
        $account_balance = \App\AccountBalance::where('user_id', $user->id)->first();

        if($promo)
        {
            if($promo->count > 0 and $used_code == null and $account_balance !== null)
            {
                $new_used_promo = \App\UsedPromocode::create(['promo_id' => $promo->id, 'user_id' => $user->id]);
                $account_balance->balance_value = $account_balance->balance_value + 50;
                $promo->count = $promo->count - 1;
                $account_balance->save();
                $promo->save();

                \Session::flash('success', " Your Promotional Code Has Been Redeemed.");
            } else {
                \Session::flash('error', " This promotional code requires you to Have existing credits in your account and can only be used once.");
            }

        }

        return view('public.promocode');
        
    }
}
