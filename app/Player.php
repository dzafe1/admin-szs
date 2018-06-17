<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    protected $fillable = ['firstname', 'lastname', 'avatar', 'date_of_birth', 'city', 'weight', 'height', 'facebook', 'instagram', 'twitter', 'youtube', 'video','biography', 'region_id', 'club_id', 'player_type_id', 'requested_club', 'player_nature', 'user_id', 'created_at', 'updated_at'];

    public function player_type() {
        return $this->belongsTo('App\Sport');
    }

    public function nature() {
        return $this->belongsTo('App\PlayerNature', 'player_nature');
    }

    public function club_history() {
        return $this->hasMany('App\PlayerClubHistory');
    }

    public function images() {
        return $this->hasMany('App\Gallery');
    }

    public function club() {
        return $this->belongsTo('App\Club');
    }

    public function trophies() {
        return $this->hasMany('App\Trophy');
    }

    public function region() {
        return $this->belongsTo('App\Region');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function requests() {
        return $this->belongsToMany('App\ClubRequest');
    }
}
