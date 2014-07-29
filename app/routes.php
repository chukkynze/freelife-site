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


// Custom Error Pages
Route::get('/there-was-a-problem/{errorNumber}',         array('as' =>  'custom-error',     'uses'  =>  'HomeController@processErrors',));




// Outside Paywall Routes - Landing Pages
Route::get('/',             'HomeController@showHome');
Route::get('/terms',        'HomeController@showTerms');
Route::get('/privacy',      'HomeController@showPrivacy');



// Entering Paywall Filters
Route::filter('csrf', function()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'PUT')
    {
        if (Session::token() != Input::get('_token'))
        {
            return Redirect::route('custom-error',array('errorNumber' => 23));
        }
    }
});

// Entering Paywall Routes - Access Authorization
Route::get('/login',                                                    array('as' =>  'login',                                 'uses'  =>  'AuthController@showAccess',                        ));
Route::get('/login-again',                                              array('as' =>  'loginAgain',                            'uses'  =>  'AuthController@loginAgain',                        ));
Route::get('/you-have-successfully-logged-out',                         array('as' =>  'successfulLogout',                      'uses'  =>  'AuthController@successfulLogout',                  ));
Route::get('/you-have-successfully-changed-your-access-credentials',    array('as' =>  'successfulAccessCredentialChange',      'uses'  =>  'AuthController@successfulAccessCredentialChange',  ));
Route::get('/login-captcha',                                            array('as' =>  'loginCaptcha',                          'uses'  =>  'AuthController@loginCaptcha',                      ));
Route::get('/member-logout',                                            array('as' =>  'memberLogout',                          'uses'  =>  'AuthController@memberLogout',                      ));
Route::get('/member-logout-expired-session',                            array('as' =>  'memberLogoutExpiredSession',            'uses'  =>  'AuthController@memberLogoutExpiredSession',        ));
Route::get('/signup',                                                   array('as' =>  'signup',                                'uses'  =>  'AuthController@signup',                            ));
Route::post('/signup',                                                  array('as' =>  'processSignup',                         'uses'  =>  'AuthController@processSignup',                     'before' => 'csrf',));
Route::get('/vendor-signup',                                            array('as' =>  'vendorSignup',                          'uses'  =>  'AuthController@vendorSignup',                      ));
Route::get('/freelancer-signup',                                        array('as' =>  'freelancerSignup',                      'uses'  =>  'AuthController@freelancerSignup',                  ));
Route::get('/forgot',                                                   array('as' =>  'forgot',                                'uses'  =>  'AuthController@forgot',                            ));
Route::get('/reset-password',                                           array('as' =>  'resetPassword',                         'uses'  =>  'AuthController@resetPassword',                     ));
Route::get('/password-change',                                          array('as' =>  'changePasswordWithOldPassword',         'uses'  =>  'AuthController@changePasswordWithOldPassword',     ));
Route::get('/verification-details',                                     array('as' =>  'processVerificationDetails',            'uses'  =>  'AuthController@processVerificationDetails',        ));
Route::get('/resend-signup-confirmation',                               array('as' =>  'resendSignupConfirmation',              'uses'  =>  'AuthController@resendSignupConfirmation',          ));
Route::get('/email-verification/{vcode}',                               array('as' =>  'verifyEmail',                           'uses'  =>  'AuthController@verifyEmail',                       ));
Route::get('/change-password-verification/{vcode}',                     array('as' =>  'changePasswordWithVerifyEmailLink',     'uses'  =>  'AuthController@changePasswordWithVerifyEmailLink', ));



// Behind Paywall Routes



// API Routes - No Auth
Route::group(array('prefix' => 'api'), function()
{
    Route::group(array('prefix' => 'vendor'), function()
    {
        Route::get('user', function()
        {
            //
        });

    });

    Route::group(array('prefix' => 'vendors'), function()
    {
        Route::get('user', function()
        {
            //
        });

    });

    Route::group(array('prefix' => 'freelancer'), function()
    {
        Route::get('user', function()
        {
            //
        });

    });

    Route::group(array('prefix' => 'freelancers'), function()
    {
        Route::get('user', function()
        {
            //
        });

    });

});


// API Routes - With Auth
Route::group(array('prefix' => 'api'), function()
{
    Route::group(array('prefix' => 'admin'), function()
    {
        Route::get('user', function()
        {
            //
        });

    });

    Route::group(array('prefix' => 'admin'), function()
    {
        Route::get('user', function()
        {
            //
        });

    });

});