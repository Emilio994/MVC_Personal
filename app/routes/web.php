<?php

use Frame\Routing\Route;
use Frame\Controllers\LoginController;
use Frame\Controllers\LogoutController;
use Frame\Controllers\HelpController;
use Frame\Controllers\ParkingController;
use Frame\Controllers\LicensePlateController;


// Login
Route::get('/login',LoginController::class,'main');
Route::post('/login',LoginController::class,'validate');

// Parkign List
Route::get('/',ParkingController::class,'index');
Route::get('/list',ParkingController::class,'list');

// Single Park
Route::get('/park',ParkingController::class,'show');
Route::post('/park',LicensePlateController::class,'validateSubscription');

// Logout
Route::get('/logout',LogoutController::class,'main');
Route::post('/logout',LogoutController::class,'execute');

// Help
Route::get('/help',HelpController::class,'index');


return Route::map();