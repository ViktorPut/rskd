<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

//Route::get('/home', 'HomeController@index')->name('home');

Route::resource('/houses', 'HousesController', ['except' => 'index']);

Route::get('/', 'HousesController@index')->name('home');

Route::resource('users', 'UsersController');

Route::get('/additional/mortgage', 'AdditionalController@mortgage');

Route::get('/additional/feedback', 'AdditionalController@feedback');

Route::post('/additional/feedback', 'AdditionalController@send');

Route::get('/photos/{photo}/destroy', 'PhotosController@destroy');

Route::post('photos/{house}/create', 'PhotosController@create');
