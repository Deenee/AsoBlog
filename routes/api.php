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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//auth Endpoints
Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::post('logout/{id}', 'AuthController@logout');

//posts 
Route::resource('posts', 'PostController');

//comments
Route::resource('comments', 'CommentController');

Route::get('user/comments/{id}', 'UserController@getComments');
Route::get('user/posts', 'UserController@getAllPosts');
