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
Route::post('storeArticle', 'ArticlesController@store');
Route::get('getArticles','ArticlesController@index');
Route::post('updateArticle/{id}','ArticlesController@update');
Route::get('showArticle/{id}','ArticlesController@show');
Route::post('deleteArticle/{id}','ArticlesController@destroy');

Route::post('signup','AuthController@signup');
Route::post('signin','AuthController@signin');

Route::post('storePost', 'PostController@store');
Route::get('getPost','PostController@index');
Route::post('updatePost/{id}','PostController@update');
Route::get('showPost/{id}','PostController@show');
Route::post('deletePost/{id}','PostController@destroy');



Route::any('(path?)', 'UsersController@index')->where("path"," .+ ")

//Route::middleware('auth:api')->get('/user', function (Request $request) {
    //return $request->user();
//});
