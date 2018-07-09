<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stream extends Model
{
       /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function fixture()
    {
        return $this->hasOne('\App\Fixture', 'stream_id');
    }

    protected $fillable = ['name', 'thumbnail', 'stream_type', 'venue_id', 'fixture_id', 'field_name', 'field_port', 'uri', 'http_url', 'storage_location'];
}
