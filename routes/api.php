<?php

use Illuminate\Http\Request;

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

Route::group(['middleware' => 'api','prefix' => 'v1'], function ($router) {
    Route::post('login', 'AuthController@login');
    /*Route::post('refresh', 'AuthController@refresh');
    Route::post('logout', 'AuthController@logout');*/
});


Route::group(['middleware' => 'auth:api','prefix' => 'v1'], function ($router) {
	
	Route::post('me', 'AuthController@meme');

	Route::post('me-v2', 'AuthController@memev2');
	
	Route::post('posts', 'AuthController@getMyPosts');
	
	Route::post('posts-v2', 'AuthController@getMyPostsv2');
	
	Route::post('posts-v3', 'AuthController@getMyPostsv3');

});







/*Route::middleware('throttle:60,1')->group(function ($router) {
	    Route::post('login', 'AuthController@login');
	});*/