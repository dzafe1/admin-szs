<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlayerClubHistory extends Model
{
    protected $table = 'player_club_histories';

    protected $fillable = ['season', 'club', 'player_id', 'created_at', 'updated_at'];
}
