<?php

use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return view('starting_page');
});

Route::get('/info', function () {
    return view('info');
});



Route::resource('hobby',"HobbyController");
Route::resource('tag',"TagController");
Route::resource('user', 'UserController');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


Route::get('/hobby/tag/{tag_id}', 'HobbyTagController@getFilteredHobbies')->name('FilterHobbiesByTagID');


Route::get('/hobby/{hobby_id}/tag/{tag_id}/attach', 'HobbyTagController@attachTag')->name('attachTag');
Route::get('/hobby/{hobby_id}/tag/{tag_id}/detach', 'HobbyTagController@detachTag')->name('detachTag');



Route::get('/delete-images/hobby/{hobby_id}', 'HobbyController@deleteImages');


Route::get('/user/{user_id}/edit', 'UserController@edit');


Route::get('/delete-images/user/{user_id}', 'UserController@deleteImages');