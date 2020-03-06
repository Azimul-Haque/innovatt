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
      return $this->hasMany('App\Payment', 'member_id', 'id');
    }

    public function leaveissuers() {
      return $this->hasMany('App\Payment', 'payer_id', 'id');
    }


    protected $hidden = [
        'password', 'remember_token',
    ];
}
