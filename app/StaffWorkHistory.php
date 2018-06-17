<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class StaffWorkHistory extends Model
{
    protected $fillable = [
        'season',
        'club',
        'staff_id'
    ];
}
