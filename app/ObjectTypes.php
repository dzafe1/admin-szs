<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ObjectTypes extends Model
{
    protected $fillable = [
        'type',
        'object_table',
        'icon',
        'created_at',
        'updated_at'
    ];
}
