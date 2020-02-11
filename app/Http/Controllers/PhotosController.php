<?php

namespace App\Http\Controllers;
use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class PhotosController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('manager');
    }

    public function destroy(App\Photo $photo){
        Storage::delete($photo->path);
        $photo->delete();
        return back();
    }

    public function create(Request $request, App\House $house){

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
        return back();
    }

}
