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

Route::get('/', function () {
    return view('welcome');
});

Route::get('robots.txt', 'RobotsController@index');

Route::get('adminImage/{object}/{size}/{id}', 'ImageController@adminImage')
    ->where('size', 'thumbnail|preview')
    ->name('admin_image');
