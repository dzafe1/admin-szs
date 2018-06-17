<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Objects extends Model
{
    protected $fillable = [
        'name',
        'image',
        'city',
        'established_in',
        'facebook',
        'instagram',
        'twitter',
        'youtube',
        'video',
        'region_id',
        'object_type_id',
        'latitude',
        'longitude',
        'history',
        'user_id',
        'created_at',
        'updated_at'
    ];

    public function type(){
        return $this->belongsTo('App\ObjectTypes', 'object_type_id');
    }

    public function region() {
        return $this->belongsTo('App\Region');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function images() {
        return $this->hasMany('App\Gallery', 'object_id');
    }
}
