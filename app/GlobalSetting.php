<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GlobalSetting extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'wowza_username', 'wowza_password', 'wowza_server_ip', 'wowza_licence', 'currency_mode', 'rands_to_points_rate',
    ];
}
