<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PayfastInfo extends Model
{
        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'm_payment_id', 'pf_payment_id', 'payment_status', 'amount_gross', 'item_name', 'item_description', 'item_price', 'custom_int1', 'custom_str1', 'signature', 'last_modified'];

    // user that owns this PayfastInfo account
    public function user()
    {
        return $this->belongsTo('App\PayfastInfo');
    }
}
