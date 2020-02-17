<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    public const ADMIN = 1;
    public const MANAGER = 2;
    //
    public  function  users(){
        return $this->hasMany(User::class);
    }
}
