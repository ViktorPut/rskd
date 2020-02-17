<?php

namespace App\Http\Controllers;

use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class HousesController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin')->only('destroy');
        $this->middleware('manager')->except(['index', 'show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $houses = App\House::all();
        return view('/house/index_house', compact('houses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //Вызов view для создание объекта
        return view('/house/create_house');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Save after create
        //Сохранение адреса

        $country = App\Country::firstOrNew(['name' => $request->countryName ]);
        $country->save();
        $city = $country->addCitiesAttribute($request->cityName);
        $district = $city->addDistrictsAttribute($request->districtName);
        $street = $district->addStreetsAttribute($request->streetName);
        $address = $street->addAddressesAttribute($request->houseNumber);

        $house = new App\House;

        $house->address_id = $address->id;
        $house->rooms  = isset($request->rooms) ? $request->rooms : '*';
        $house->floors = isset($request->floors) ? $request->floors : '*';
        $house->description = isset($request->description) ? $request->description : '*';
        $house->cost  = (double)$request->cost;
        $house->space = (double)$request->space;
        $house->save();

        /*
         * создание и связывание параметров
         */

        $parameters = array();
        foreach (array_keys($request->parameters) as $fieldKey){
            foreach ($request->parameters[$fieldKey] as $key => $parameterRequest) {
                if($parameterRequest == null)
                    continue;
                $parameters[$key][$fieldKey] = $parameterRequest;
            }
        }
        foreach ($parameters as $parameter){
            $params[] = App\Parameter::firstOrCreate([ 'name' => $parameter['name'], 'value' => $parameter['value'] ])->id;
        }
        $house->parameters()->attach($params);
        //фотографии
        Storage::makeDirectory('public/photos/house_photos/'.$house->id);

        foreach ($request->photos as $file) {
            while (true) {
                $name = uniqid() . '.png';
                $path = 'storage/photos/house_photos/' . $house->id . '/';
                if(!file_exists($path.$name)){
                    break;
                }
            }
            //Узнать размер, чтобы слишком сильно не сжать width()/height()
            $image_resize = Image::make($file->getRealPath()); //Возьмем фото
            $width = $image_resize->width();
            $height = $image_resize->height();
            //уменьшим в 2 раза размер
            if ($width > 1280){
                $width /= 2;
            }
            if($height > 720 ){
                $height /= 2;
            }
            $image_resize->resize($width, $height)
            ->save($path . $name);//сохраним

            $temp = new App\Photo;
            $temp->path = $path . $name;
            $temp->rank = 100;
            $house->photos()->save($temp);//кинем в БД в связке с объектом
        }
        if(Auth::user()->isAdmin()){
            $house->users()->attach($request->user);
        }else{
            $house->users()->attach(Auth::user()->id);
        }

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(App\House $house)
    {
        //
        return view('/house/show_house', compact('house'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(App\House $house)
    {
        //првоерить что это его объект
//        if(Auth::user()->id == )
        return view('/house/edit_house', compact('house'));
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, App\House $house)
    {
        //
        $house->rooms  = isset($request->rooms) ? $request->rooms : $house->rooms;
        $house->floors = isset($request->floors) ? $request->floors : $house->floors;
        $house->description = isset($request->description) ? $request->description : $house->description;
        $house->cost  = isset($request->cost) ? $request->cost : $house->cost;
        $house->space = isset($request->space) ? $request->space : $house->space;

        $country = App\Country::firstOrNew(['name' => $request->countryName ]);
        $country->save();
        $city = $country->addCitiesAttribute($request->cityName);
        $district = $city->addDistrictsAttribute($request->districtName);
        $street = $district->addStreetsAttribute($request->streetName);
        $address = $street->addAddressesAttribute($request->houseNumber);

        $house->address_id = $address->id;
        $house->save();

        $parameters = array();
        foreach (array_keys($request->parameters) as $fieldKey){
            foreach ($request->parameters[$fieldKey] as $key => $parameterRequest) {
                if($parameterRequest == null)
                    continue;
                $parameters[$key][$fieldKey] = $parameterRequest;
            }
        }
        foreach ($parameters as $parameter){
            $params[] = App\Parameter::firstOrCreate([ 'name' => $parameter['name'], 'value' => $parameter['value'] ])->id;
        }
        $house->parameters()->sync($params);

        foreach ($request->photos as $file) {
            while (true) {
                $name = uniqid() . '.png';
                $path = 'storage/photos/house_photos/' . $house->id . '/';
                if(!file_exists($path.$name)){
                    break;
                }
            }
            //Узнать размер, чтобы слишком сильно не сжать width()/height()
            $image_resize = Image::make($file->getRealPath()); //Возьмем фото
            $width = $image_resize->width();
            $height = $image_resize->height();
            //уменьшим в 2 раза размер
            if ($width > 1280){
                $width /= 2;
            }
            if($height > 720 ){
                $height /= 2;
            }
            $image_resize->resize($width, $height)
                ->save($path . $name);//сохраним

            $temp = new App\Photo;
            $temp->path = $path . $name;
            $temp->rank = 100;
            $house->photos()->save($temp);//кинем в БД в связке с объектом
        }

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(App\House $house)
    {
        //
//        App\Photo::where('house_id', $house->id)->delete();
        Storage::deleteDirectory('public/photos/house_photos/'.$house->id);
        $house->parameters()->detach();
        $house->delete();
        return redirect('/');
    }
}
