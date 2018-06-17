<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = [
        'logo',
        'name',
        'nature',
        'city',
        'established_in',
        'home_field',
        'competition',
        'phone_1',
        'phone_2',
        'fax',
        'email',
        'website',
        'address',
        'pioniri',
        'kadeti',
        'juniori',
        'facebook',
        'twitter',
        'instagram',
        'youtube',
        'video',
        'club_category_id',
        'sport_id',
        'region_id',
        'user_id',
        'history'
    ];

    public function category() {
        return $this->belongsTo('App\ClubCategory', 'club_category_id');
    }

    public function sport() {
        return $this->belongsTo('App\Sport');
    }

    public function region() {
        return $this->belongsTo('App\Region');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function trophies() {
        return $this->hasMany('App\Trophy');
    }

    public function images() {
        return $this->hasMany('App\Gallery');
    }

    public function members() {
        return $this->hasMany('App\SchoolMember');
    }
}
