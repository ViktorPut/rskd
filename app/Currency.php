<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $fillable = [
        'name',
        'country_id'
    ];
    //

    public function country(){
        return $this->belongsTo(Country::class);
    }
}
