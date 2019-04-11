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

Route::get('/image/{file_name}', 'FilesController@getFile');
Route::get('/image/download/{file_name}', 'FilesController@downloadFile');

Route::get('/cli/download/{platform}', 'FilesController@downloadCLI');