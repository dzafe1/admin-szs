<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VijestKategorija extends Model
{
    protected $table = 'vijest_kategorija';

    public function vijesti() {
        return $this->hasMany('App\Vijest', 'vijest_kategorija_id');
    }
}
