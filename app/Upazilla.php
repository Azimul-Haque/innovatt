<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Upazilla extends Model
{
	public $timestamps = false;

    public function users() {
        return $this->hasMany('App\User');
    }

    public function institutes() {
        return $this->hasMany('App\Institute');
    }
}
