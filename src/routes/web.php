<?php
use Sisukma\V2\Models\Layanan;
use Illuminate\Support\Facades\Route;
use Sisukma\V2\Controllers\WebController;
Route::get('/d', function(){
    return Layanan::with('skpd')->get();
});
Route::get('/', [WebController::class, 'index']);
Route::get('gallery/{slug?}', [WebController::class, 'gallery']);
