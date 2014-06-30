<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('landing/home');
});

Route::get('/vendors/{id}', function($id){
    return "vendor #".$id;
})->where('id', '[0-9]+')
;

Route::get('/vendors', function(){
    return "All vendors";
})
;
