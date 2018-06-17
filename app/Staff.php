<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    protected $table = 'staff';

    protected $fillable = [
        'firstname',
        'lastname',
        'avatar',
        'date_of_birth',
        'facebook',
        'twitter',
        'instagram',
        'youtube',
        'video',
        'region_id',
        'profession_id',
        'biography',
        'user_id',
        'city',
        'education',
        'additional_education',
        'number_of_certificates',
        'number_of_projects',
        'work_experience',
        'club_name',
        'requested_club',
        'club_id'
    ];

    protected $with = ['profession'];

    public function languages() {
        return $this->belongsToMany('App\Language');
    }

    public function profession() {
        return $this->belongsTo('App\Profession');
    }

    public function work_history() {
        return $this->hasMany('App\StaffWorkHistory');
    }

    public function region() {
        return $this->belongsTo('App\Region');
    }

    public function current_club() {
        return $this->belongsTo('App\Club', 'club_id');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function club() {
        return $this->belongsTo('App\Club');
    }

    public function trophies() {
        return $this->hasMany('App\Trophy');
    }

    public function images() {
        return $this->hasMany('App\Gallery');
    }
}
