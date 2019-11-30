<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Institute extends Model
{
    public function upazilla() {
        return $this->belongsTo('App\Upazilla');
    }

    public function users() {
        return $this->hasMany('App\User');
    }
}
