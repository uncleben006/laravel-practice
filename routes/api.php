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

// post api 
Route::get('/posts', 'PostController@index');
Route::get('/posts/{id}', 'PostController@show');
Route::post('/posts', 'PostController@store');

// product api
Route::get('/products', 'ProductController@api');
Route::get('/products/{category}/sorting', 'ProductController@sortingApi');
Route::get('/products/{category}', 'ProductController@filterApi');

// Route::get('/products/rackets', 'ProductController@racketApi');
// Route::get('/products/footwears', 'ProductController@footwearApi');
// Route::get('/products/bags', 'ProductController@bagApi');
// Route::get('/products/apparels', 'ProductController@apparelApi');
// Route::get('/products/accessories', 'ProductController@apparelApi');

Route::get('/products/{id}/show', 'ProductController@singleApi');
Route::get('/products/{id}/images', 'ProductController@imagesApi');