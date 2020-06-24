<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

//Auth::routes(['verify' => true]);

Route::prefix('auth')->group(function () {

    Route::post('/register', 'AuthController@register')->name('register');
    Route::post('/login', 'AuthController@login')->name('login');
    Route::get('/check', 'AuthController@checkAuthUser')->name('check');


    Route::middleware('auth:api')->group(function () {
        Route::get('logout', 'AuthController@logout')->name('logout');
        Route::get('details', 'AuthController@details')->name('details');
    });
});

Route::middleware('auth:api')->group(function () {

    Route::post('/comment/store', 'CommentController@store')->name('comment.add');
    Route::post('/reply/store', 'CommentController@replyStore')->name('reply.add');

    Route::get('news/popular', 'NewsController@mostCommentable');
    Route::put('news/{news}', 'NewsController@store')->name('news.update');
    Route::apiResource('news', 'NewsController', ['except' => ['update']]);
    Route::get('{user}/news', "NewsController@showByUser")->name('news.byUser');
});
