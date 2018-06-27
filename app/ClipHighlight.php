<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClipHighlight extends Model
{
    //
    protected $fillable = ['stream_id', 'clip_name', 'clip_extracted', 'time'];

}
