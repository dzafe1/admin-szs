<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Club extends Model
{
    public function association() {
        return $this->belongsTo('App\Association');
    }

    public function category() {
        return $this->belongsTo('App\ClubCategory', 'club_category_id');
    }

    public function sport() {
        return $this->belongsTo('App\Sport');
    }

    public function region() {
        return $this->belongsTo('App\Region');
    }

    public function creator() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function trophies() {
        return $this->hasMany('App\Trophy');
    }

    public function histories() {
        return $this->hasMany('App\History');
    }

    public function all_images() {
        return $this->hasMany('App\Gallery');
    }

    public function images() {
        return $this->all_images()->where('is_proof', '=', false);
    }

    public function proof_images() {
        return $this->all_images()->where('is_proof', '=', true);
    }

    public function club_staff() {
        return $this->hasMany('App\ClubStaff');
    }

    public function players() {
        return $this->hasMany('App\Player');
    }

    public function staff() {
        return $this->hasMany('App\Staff');
    }
}
