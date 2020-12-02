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

Route::middleware(['jwt'])->get('/me', function (Request $request) {
    return auth()->user();
});

Route::post('/login', 'AuthController@login');
Route::get('/rented-books', 'RentedBooks');
Route::apiResource('books', 'BookController');
Route::apiResource('rents', 'RentController')->except(['update']);
