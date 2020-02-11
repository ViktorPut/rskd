<?php

namespace App\Http\Controllers;
use App;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('admin')->only(['create', 'destroy']);
        $this->middleware('manager')->only(['edit']);
    }

    public function index()
    {
        $users = App\User::getManagerList();
        return view('user/index_user', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('user/create_user');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $user = new App\User;
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = bcrypt($request->newPassword);
        $user->user_info = $request->userInfo;
        $user->phone = $request->phone;
        $user->rank = 100;//стандрат
        $user->role_id = 2;
        //photo
        if(isset($request->photo)) {
            while (true) {
                $name = uniqid() . '.png';
                $path = 'storage/photos/user_photos/' . $name;
                if (!file_exists($path)) {
                    break;
                }
            }

            $image_resize = Image::make($request->photo->getRealPath()); //Возьмем фото
            $width = $image_resize->width();
            $height = $image_resize->height();
            //уменьшим в 2 раза размер
            if ($width > 1280) {
                $width /= 2;
            }
            if ($height > 720) {
                $height /= 2;
            }
            $image_resize->resize($width, $height)
                ->save($path);//сохраним

            $user->photo = $path;
        }

        $user->save();
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(App\User $user)
    {
        //
        return view('user/show_user',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(App\User $user)
    {
        //
        if($user->id == Auth::user()->id || Auth::user()->isAdmin())
            return view('user/edit_user',compact('user'));
        else
            return view('user/show_user', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, App\User $user)
    {
        //
        $user->email = $request->email;
        $user->name = $request->name;
        $user->password = bcrypt($request->newPassword);
        $user->user_info = $request->userInfo;
        $user->phone = $request->phone;
        $user->rank = $request->rank;//стандрат
        //photo
        if(isset($request->photo)) {
            while (true) {
                $name = uniqid() . '.png';
                $path = 'storage/photos/user_photos/' . $name;
                if (!file_exists($path)) {
                    break;
                }
            }

            $image_resize = Image::make($request->photo->getRealPath()); //Возьмем фото
            $width = $image_resize->width();
            $height = $image_resize->height();
            //уменьшим в 2 раза размер
            if ($width > 1280) {
                $width /= 2;
            }
            if ($height > 720) {
                $height /= 2;
            }
            $image_resize->resize($width, $height)
                ->save($path);//сохраним
            Storage::delete($user->photo);//удалим старое
            $user->photo = $path;//вкинем новое
        }
        $user->save();
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(App\User $user)
    {
        //
        $user->houses()->detach();
        if($user->photo != null)
            Storage::delete($user->photo);
        $user->delete();
        return redirect('/');
    }

}
