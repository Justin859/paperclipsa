<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SquashUserStream extends Model
{
    //
    protected $fillable = ['user_id', 'stream_ids'];
}
