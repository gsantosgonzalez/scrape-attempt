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
    return view('welcome');
});

Route::post('satLogin', 'SATController@login')->name('satLogin');
Route::get('/renderLogin', 'SATController@loadLogin')->name('loginPage');
