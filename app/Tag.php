<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected  $table = 'tag';

    protected $fillable = ['tag'];

    public function vijesti() {
        return $this->belongsToMany('App\Vijest', 'vijest_tag');
    }
}
