<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public function files() {
        return $this->hasMany('App\File');
    }
}
