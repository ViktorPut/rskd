<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
    //
    protected $fillable = [
        'name',
        'district_id'
    ];

    public  function addresses(){
        return $this->hasMany(Address::class);
    }

    public function district(){
        return $this->belongsTo(District::class);
    }

    public function addAddressesAttribute($addressNumber) : Address{
        $address = Address::firstOrNew([
            'number' => $addressNumber,
            'street_id' => $this->id
        ]);
        $address->street_id = $this->id;
        $address->save();
        return $address;
    }
}
