<?php
use Illuminate\Support\Facades\Route;
use Sisukma\V2\Contracts\IkmCounter;
use Sisukma\V2\Controllers\WebController;
Route::match(['get','post'],'survei/{skpd}/{layanan?}', [WebController::class, 'survei'])->name('survei.form');
Route::match(['get','post'],'/', [WebController::class, 'index']);
Route::get('gallery/{slug?}', [WebController::class, 'gallery']);
Route::get('ikmskpd/{skpd}', [WebController::class, 'dataikm'])->name('ikm.skpd');
Route::get('ikmkab', [WebController::class, 'dataikmkab'])->name('ikm.kab');
Route::get('ikm16', function (): mixed {
    return (new IkmCounter)->getDataIKM16(352, null, 'bulan', 2026, 01);
});
Route::get('ikm16statistik', function (): mixed {
    return (new IkmCounter)->getStatistik16( 352, null, 'bulan', 2026, 01);
});
