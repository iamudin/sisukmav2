<?php
use Sisukma\V2\Models\Layanan;
use Illuminate\Support\Facades\Route;
use Sisukma\V2\Controllers\WebController;
Route::get('/d', function(){
    return Layanan::with('skpd')->get();
});
Route::match(['get','post'],'/', [WebController::class, 'index']);
Route::post('/get_data_stats', [WebController::class, 'get_data_stats'])->name('ajax.web.data');
Route::get('gallery/{slug?}', [WebController::class, 'gallery']);
