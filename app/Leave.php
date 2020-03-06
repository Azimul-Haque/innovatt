<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Leave extends Model
{
    public function institute() {
      return $this->belongsTo('App\Institute');
    }

    public function teacher() {
      return $this->belongsTo('App\User', 'teacher_id', 'id');
    }

    public function issuer() {
      return $this->belongsTo('App\User', 'issuer_id', 'id');
    }
}
