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
// System Routes
App::missing(function($exception){
    return Response::make("Page not found", 404);
});
App::missing(function($exception){
    return Response::make("Page not found", 500);
});


// Outside Paywall Routes

Route::get('/login',
        array
        (
            'as'        =>  'login',
            'uses'      =>  'AuthController@showAccess',
        ))
;

Route::get('/login-again',
        array
        (
            'as'        =>  'login',
            'uses'      =>  'AuthController@loginAgain',
        ))
;

/*


Route::get
    (
        '/login',
        array
        (
            'as'        =>  'login',
            'uses'      =>  'AuthController@showAccess',
        )
    );
;
 */



Route::get('/login-with-verification', function(){
    return "login-with-verification";
})
;

Route::get('/signup', function(){
    return Redirect::to("freelancer-signup");
})
;

Route::get('/vendor-signup', function(){
    return "vendor-signup";
})
;

Route::get('/freelancer-signup', function(){
    return "freelancer-signup";
})
;


// Behind Paywall Routes
Route::get('/', 'HomeController@showHome');

Route::get('/vendors/{id}', function($id){
    return "vendor #".$id;
})->where('id', '[0-9]+')
;

Route::get('/vendors', function(){
    return "All vendors";
})
;