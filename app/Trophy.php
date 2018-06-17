<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trophy extends Model
{
    protected $table = 'trophies';

    protected $fillable = [
        'type',
        'place',
        'competition_name',
        'level_of_competition',
        'season',
        'club_id',
        'player_id',
        'staff_id',
        'school_id'
    ];
}
