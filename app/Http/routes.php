<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

/**
 * API v1
 */
Route::filter('cache', function ($route, $request, $response, $age = 60) {
    $response->setTtl($age);
});

Route::group(
    [
        'prefix' => 'api/v1',
        //'before' => 'throttle:50,30',
        'after'  => 'cache:60',
    ],
    function () {
        Route::group(['middleware' => 'auth.basic'], function () {
            Route::group(['namespace' => 'Users'], function () {
                Route::resource('users', 'UserController', ['except' => ['index', 'create', 'edit']]);
                Route::resource('users.images', 'ImageController', ['except' => ['create', 'edit']]);

                Route::group(['namespace' => 'Images'], function () {
                    Route::resource('users.images.comments', 'CommentController', ['except' => ['create', 'edit']]);
                });
            });
        });
});