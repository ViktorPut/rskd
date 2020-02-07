<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = [
        'number',
        'house_id'
    ];

    public function houses(){
        return $this->hasMany(House::class);
    }

    public function street(){
        return $this->belongsTo(Street::class);
    }

}
