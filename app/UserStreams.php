<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserStreams extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'stream_ids', 'vod_ids', 'live_ids',];

    // user that owns this userstream
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
