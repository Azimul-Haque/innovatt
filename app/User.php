<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    public function upazilla() {
        return $this->belongsTo('App\Upazilla');
    }

    public function institute() {
        return $this->belongsTo('App\Institute');
    }

    public function institutes() {
        return $this->hasMany('App\Institute');
    }


    protected $hidden = [
        'password', 'remember_token',
    ];
}
