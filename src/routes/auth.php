<?php
use Illuminate\Support\Facades\Route;
use Sisukma\V2\Controllers\LoginController;
Route::controller(LoginController::class)->group(function ()  {
    Route::get('login', 'loginForm')->name('login');
    Route::get( 'captcha', 'generateCaptcha')->name('captcha');
    Route::post( 'login', 'loginSubmit')->name('login.submit');
    Route::match(['post', 'get'], 'logout',  'logout')->name('logout');
});
