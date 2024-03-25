<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/products', 'RatingController@index');

Route::post('/login', [AuthController::class, 'login']);
