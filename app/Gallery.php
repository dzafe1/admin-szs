<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'galeries';

    protected $fillable = [
        'image',
        'club_id',
        'player_id',
        'staff_id',
        'school_id',
        'object_id'
    ];
}
