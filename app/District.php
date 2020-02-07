<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Scalar\String_;

class District extends Model
{
    protected $fillable = [
        'name',
        'city_id'
    ];
    //
    public function city(){
        return $this->belongsTo(City::class);
    }

    public function streets(){
        return $this->hasMane(Street::class);
    }

    public function addStreetsAttribute($streetName) : Street{
        $street = Street::firstOrNew([
            'name'  => $streetName,
            'district_id' => $this->id
        ]);

        $street->district_id = $this->id;
        $street->save();
        return $street;
    }
}
