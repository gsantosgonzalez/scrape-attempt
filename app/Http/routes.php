<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use Blacktrue\Scraping\URLS;

Route::get('/', function () {
    return redirect('renderLogin');
});

Route::get('/renderLogin', 'SATController@renderLogin')->name('renderLogin');
Route::get('/startSession', 'SATController@startSession')->name('startSession');
Route::post('/satLogin', 'SATController@satLogin')->name('satLogin');
Route::post('/findEmitted', 'SATController@findEmitted')->name('findEmitted');
Route::post('/findRecieved', 'SATController@findRecieved')->name('findRecieved');
Route::post('/download', 'SATController@downloadXML')->name('download');
