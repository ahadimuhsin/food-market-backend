<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
// use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//homepage
Route::get('/', function () {
    return redirect()->route('dashboard');
});


//dashboard
Route::prefix('/dashboard')
    ->middleware(['auth:sanctum', 'admin'])
    ->group(function(){
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::resource('foods', FoodController::class);
    Route::get('transactions/{id}/status/{status}', [TransactionController::class, 'changeStatus'])
    ->name('transactions.changeStatus');
    Route::resource('transactions', 'TransactionController');
});



//midtrans page
Route::get('midtrans/success', 'API\MidtransController@success');
Route::get('midtrans/unfinish', 'API\MidtransController@unfinish');
Route::get('midtrans/error', 'API\MidtransController@error');
