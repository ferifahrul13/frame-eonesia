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


Auth::routes(['register' => false, 'password.request' => false]); //disable karena mau custom login
Route::get('/login', function () {
    return view('errors.404');
});
Route::post('/login', function () {
    return view('errors.404');
});
Route::get('/logout', function () {
    return view('errors.404');
});

Route::get('/admin', function () {
    return redirect('/admin/home');
});
Route::post('/login', function () {
    return view('errors.404');
});

Route::group(['namespace' => 'Auth'], function () {
    Route::get('/admin/login', 'LoginController@showLoginForm')->name('login');
    Route::post('/admin/login', 'LoginController@login');
    Route::post('/admin/logout', 'LoginController@logout')->name('logout');
});


Route::group(['namespace' => 'b', 'prefix' => 'admin'], function () {
    Route::get('home', 'HomeController@index')->name('home');
    Route::resource('frame', 'FrameController');
    Route::get('frame/{id}/result/', 'ResultController@index')->name('result.index');
    Route::get('frame/{id}/result/download/{name}.zip', 'ResultController@download')->name('result.download');

    Route::resource('background', 'BackgroundController');
    Route::post('preview/background', 'BackgroundController@preview')->name('background.preview');

    Route::resource('photo', 'PhotoController', ['only' => ['index', 'show','destroy']]);
    Route::get('photoDownload/{id}', 'PhotoController@downloadPhoto')->name('downloadPhoto');
    Route::get('prepare', 'FrameController@prepare')->name('frame.prepare');
});



Route::group(['namespace' => 'f',], function () {
    Route::get('/', 'FrontendController@index')->name('frontend.index');
    Route::get('frame/{id}', 'UploadController@upload')->name('upload');
    Route::post('frame/{id}', 'UploadController@store')->name('upload.store');
    Route::get('download/{id}', 'UploadController@download')->name('download');
});
