<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SchoolMember extends Model
{
    protected $fillable = [
        'avatar',
        'firstname',
        'lastname',
        'biography',
        'school_id'
    ];
}
