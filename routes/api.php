<?php

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

Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');
Route::get('food', 'FoodController@all');


Route::group(['middleware' => ['auth:sanctum']], function () {
   Route::get('user', 'UserController@fetch');
   Route::post('update-profile', 'UserController@updateProfile');
   Route::post('user/photo', 'UserController@updatePhoto');
   Route::get('transaction', 'TransactionController@all');
   Route::post('transaction/{id}', 'TransactionController@update');
   Route::post('logout', 'UserController@logout');
   Route::post('transaction', 'TransactionController@checkout');
});

Route::post('midtrans/callback', 'MidtransController@callback');
