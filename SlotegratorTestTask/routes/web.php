<?php

use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/prize', [App\Http\Controllers\HomeController::class, 'getPrize'])->name('home');
Route::get('/convert_money_to_points/{operation_id}', [App\Http\Controllers\MoneyOperationController::class, 'convertToPoints'])->name('home');
Route::get('/make_transaction_to_bank/{operation_id}', [App\Http\Controllers\MoneyOperationController::class, 'makeTransactionToBank'])->name('home');

Route::get('/refuse_object/{operation_id}', [App\Http\Controllers\ObjectOperationController::class, 'refuseObject'])->name('home');
Route::get('/send_object_to_user', [App\Http\Controllers\ObjectOperationController::class, 'sendObjectToUser'])->name('home');


