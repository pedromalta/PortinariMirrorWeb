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
    return view('uploadtest');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::post('dashboard/', 'DashboardController@dashboard');
Route::get('dashboard/', 'DashboardController@dashboard');
Route::get('dashboard/{userId}', 'DashboardController@dashboard');
Route::post('face-recognition/', 'DashboardController@faceRecognition');

