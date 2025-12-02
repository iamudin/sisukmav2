<?php
use Illuminate\Support\Facades\Route;
use Sisukma\V2\Controllers\WebController;
Route::match(['get','post'],'survei/{skpd}/{layanan?}', [WebController::class, 'survei'])->name('survei.form');
Route::match(['get','post'],'/', [WebController::class, 'index']);
Route::get('gallery/{slug?}', [WebController::class, 'gallery']);
Route::get('ikmskpd/{skpd}', [WebController::class, 'dataikm'])->name('ikm.skpd');
Route::get('ikmkab', [WebController::class, 'dataikmkab'])->name('ikm.kab');

