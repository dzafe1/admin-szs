<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'histories';

    protected $fillable = [
        'content',
        'club_id'
    ];

    public function club() {
        return $this->belongsTo('App\Club');
    }
}
