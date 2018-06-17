<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClubRequest extends Model
{
    protected $fillable = ['club_id', 'player_id', 'staff_id', 'approved'];

    public function club() {
        return $this->belongsTo('App\Club');
    }

    public function player() {
        return $this->belongsTo('App\Player');
    }

    public function staff() {
        return $this->belongsTo('App\Staff');
    }
}
