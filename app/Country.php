<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Country extends Model{
    protected $fillable = [
        'name'
    ];

    public function cities(){
        return $this->hasMany(City::class);
    }
    /*
     * return City
     * */
    public function addCitiesAttribute($cityName): City{
        $city = City::firstOrNew([
            'name' => $cityName,
            'country_id' => $this->id
        ]);
        $city->country_id = $this->id;
        $city->save();
        return $city;
    }
}
