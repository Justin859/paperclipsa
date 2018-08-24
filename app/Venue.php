<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    protected $fillable = ['admin_id', 'name', 'wow_app_name', 'username', 'password', 'description', 'phone', 'venue_ip', 'logo_img', 'banner_img', 'intro_vid_url', 'web_url', 'twitter_url', 'fb_url', 'active_status', 'venue_type'];

    //protected $hidden = ['username', 'password',];


    public function field()
    {
        return $this->hasMany('App\Field');
    }

    public function team()
    {
        return $this->hasMany('App\Team');
    }

    public function referee()
    {
        return $this->hasMany('App\Referee');
    }

    public function admin()
    {
        return $this->hasMany('App\Admin');
    }

    public function streams()
    {
        return $this->hasMany('\App\Stream', 'venue_id');
    }

    public function fixtures()
    {
        return $this->hasMany('\App\Fixture', 'venue_id');
    }
}
