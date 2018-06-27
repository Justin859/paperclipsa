<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscribedUser extends Model
{
    //
    protected $fillable = [
        'user_id',
        'pf_payment_id',
        'payment_status',
        'item_name',
        'amount_gross',
        'amount_fee',
        'amount_net',
        'token',
        'signature',
        'status',
        'subscription_date',
    ];
}
