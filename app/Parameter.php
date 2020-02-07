<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    protected $fillable = [
        'name',
        'value'
    ];
    public function houses(){
        return $this->belongsToMany(House::class);
    }
    //
}
