<?php
use Sisukma\V2\Controllers\AdminController;
use Sisukma\V2\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Sisukma\V2\Controllers\AjaxController;

$path = 'admin';
Route::prefix($path)->group(function()use($path){
    Route::match(['get','post'],'dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::controller(AjaxController::class)->group(function() {
        Route::post('ajax/dashboard', 'dashboard')->name('ajax.dashboard');
        Route::post('ajax/detailikm', 'detailikm')->name('ajax.detailikm');
        Route::post('ajax/cetak_rekap_kabupaten', 'cetak_rekap_kabupaten')->name('ajax.rekap_kabupaten_pdf');
        Route::post('ajax/cetak_rekap_skpd', 'cetak_rekap_skpd')->name('ajax.rekap_skpd_pdf');
        Route::post('ajax/cetak_ikm_skpd', 'cetak_ikm_skpd')->name('ajax.ikm_skpd_pdf');
    });
    Route::controller(AdminController::class)->group(function() {
        // SKPD Routes
        Route::match(['post','get'],'account', 'account')->name('user.account');
        Route::get('linkqr/{skpd}', 'linkQR')->name('skpd.linkqr');
        Route::get('cetakqr/{skpd}', 'cetakQR')->name('skpd.cetakqr');
        Route::match(['post','get'],'profile-skpd', 'profileSKPD')->name('skpd.profile');
        Route::get('skpd', 'indexSKPD')->name('skpd.index');
        Route::get('skpd/create', 'formSKPD')->name('skpd.create');
        Route::post('skpd/create', 'storeSKPD')->name('skpd.store');
        Route::get('skpd/{skpd}/edit', 'formSKPD')->name('skpd.edit');
        Route::put('skpd/{skpd}/edit', 'updateSKPD')->name('skpd.update');
        Route::delete('skpd/{skpd}/delete', 'destroySKPD')->name('skpd.destroy');

        // Layanan Routes
        Route::get('layanan', 'indexLayanan')->name('layanan.index');
        Route::get('layanan/create', 'formLayanan')->name('layanan.create');
        Route::post('layanan/create', 'storeLayanan')->name('layanan.store');
        Route::get('layanan/{layanan}/edit', 'formLayanan')->name('layanan.edit');
        Route::put('layanan/{layanan}/edit', 'updateLayanan')->name('layanan.update');
        Route::delete('layanan/{layanan}/delete', 'destroyLayanan')->name('layanan.destroy');

        // Unsur Routes
        Route::get('unsur', 'indexUnsur')->name('unsur.index');
        Route::get('unsur/create', 'formUnsur')->name('unsur.create');
        Route::post('unsur/create', 'storeUnsur')->name('unsur.store');
        Route::get('unsur/{unsur}/edit', 'formUnsur')->name('unsur.edit');
        Route::put('unsur/{unsur}/edit', 'updateUnsur')->name('unsur.update');
        Route::delete('unsur/{unsur}/delete', 'destroyUnsur')->name('unsur.destroy');

        Route::get('unit', 'indexUnit')->name('unit.index');
        Route::get('unit/create', 'formUnit')->name('unit.create');
        Route::post('unit/create', 'storeUnit')->name('unit.store');
        Route::get('unit/{unit}/edit', 'formUnit')->name('unit.edit');
        Route::put('unit/{unit}/edit', 'updateUnit')->name('unit.update');
        Route::delete('unit/{unit}/delete', 'destroyUnit')->name('unit.destroy');

        // User Routes
        Route::get('responden-layanan', 'indexResponden')->name('responden.index');
        Route::match(['get','post'],'responden-layanan/{layanan}/import', 'importResponden')->name('responden.import');
        Route::post('responden-layanan/{layanan}/destroy', 'destroyDateResponden')->name('responden.destroy.date');


        Route::get('gallery', 'indexGallery')->name('gallery.index');
        Route::get('gallery/create', 'formGallery')->name('gallery.create');
        Route::post('gallery/create', 'storeGallery')->name('gallery.store');
        Route::get('gallery/{gallery}/edit', 'formGallery')->name('gallery.edit');
        Route::put('gallery/{gallery}/edit', 'updateGallery')->name('gallery.update');
        Route::delete('imggallery/{imgGallery}/delete', 'destroyImgGallery')->name('imgGallery.destroy');
        Route::delete('gallery/{gallery}/delete', 'destroyGallery')->name('gallery.destroy');

    });


});
