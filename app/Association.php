<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Association extends Model
{
    protected $fillable = [
        'image',
        'name',
        'established_in',
        'president',
        'vice_president',
        'description',
        'region_id',
        'sport_id'
    ];

    public function region() {
        return $this->belongsTo('App\Region');
    }

    public function sport() {
        return $this->belongsTo('App\Sport');
    }
}
