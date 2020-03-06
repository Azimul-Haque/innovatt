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

    public function leaves() {
      return $this->hasMany('App\Leave', 'teacher_id', 'id');
    }

    public function issuedleaves() {
      return $this->hasMany('App\Leave', 'issuer_id', 'id');
    }


    protected $hidden = [
        'password', 'remember_token',
    ];
}
