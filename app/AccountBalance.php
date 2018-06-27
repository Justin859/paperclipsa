<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccountBalance extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'balance_value', 'balance_currency',];
    
    // user that owns this account balance
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
