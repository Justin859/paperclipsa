<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Advertisers extends Model
{
    protected $fillable = ['venue_id', 'title', 'image', 'company_name', 'link', 'desc', 'active_status', 'coverage'];
}
