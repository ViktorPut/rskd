<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    //
    protected $fillable = [
        'rooms',
        'floors',
        'space',
        'cost',
        'description',
        'address_id',
    ];

    public function address(){
        return $this->belongsTo(Address::class);
    }

    public function photos(){
        return $this->hasMany(Photo::class);
    }

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function parameters(){
        return $this->belongsToMany(Parameter::class);
    }
}
