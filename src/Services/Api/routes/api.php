<?php

/*
|--------------------------------------------------------------------------
| Service - API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for this service.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::group(['middleware' => 'auth:api'], function () {
    Route::group(['prefix' => 'task'], function () {
        Route::get('new-tasks-list', 'TaskController@newTasksList');
        Route::get('my-tasks-list', 'TaskController@myTasksList');
    });
});