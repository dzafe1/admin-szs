<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ClubStaff extends Model
{
    protected $table = 'club_staff';

    protected $fillable = ['avatar', 'firstname', 'lastname', 'biography', 'club_id'];
}
