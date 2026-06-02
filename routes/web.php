<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\admin\MblPrefixController;
use App\Http\Controllers\admin\TemplateController;
use App\Http\Controllers\freight\ShipmentController;
use App\Http\Controllers\freight\ContainerController;
use App\Http\Controllers\freight\VgmController;
use App\Http\Controllers\freight\PackinglistController;
use App\Http\Controllers\freight\UspackingListController;
use App\Http\Controllers\freight\IsfController;
Route::get('/',[AuthController::class,'index'])->name('login.page');

Route::post('/common-login', [AuthController::class, 'login'])
    ->name('login.submit');
Route::post('/common-logout',[AuthController::class,'logout'])->name('commonlogout');
//Route::get('/dashboard', function () {
//    return view('dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['admin'])->group(function () {

    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    //feright user
    Route::get('/add-feright',[\App\Http\Controllers\admin\AddFerightController::class,'index'])->name('addferight');
    Route::post('/create-feright',[\App\Http\Controllers\admin\AddFerightController::class,'store'])->name('create.feright');
    Route::get('/manage-feright',[\App\Http\Controllers\admin\AddFerightController::class,'manageferight'])->name('manage.feright');
    Route::get('/delete-feright/{id}',[\App\Http\Controllers\admin\AddFerightController::class,'delete'])->name('delete.feright');
    Route::get('/edit-feright/{id}',[\App\Http\Controllers\admin\AddFerightController::class,'edit'])->name('edit.feright');
    Route::post('/update-feright/{id}',[\App\Http\Controllers\admin\AddFerightController::class,'update'])->name('update.feright');

    //Accountant user
    Route::get('/add-account',[\App\Http\Controllers\admin\AddaccountantController::class,'index'])->name('addaccount');
    Route::post('/create-accountant',[\App\Http\Controllers\admin\AddaccountantController::class,'store'])->name('create.accountant');
    Route::get('/manage-accountant',[\App\Http\Controllers\admin\AddaccountantController::class,'manageaccountant'])->name('manage.accountant');
    Route::get('/delete-accountant/{id}',[\App\Http\Controllers\admin\AddaccountantController::class,'delete'])->name('delete.accountant');
    Route::get('/edit-accountant/{id}',[\App\Http\Controllers\admin\AddaccountantController::class,'edit'])->name('edit.accounant');
    Route::post('/update-accountant/{id}',[\App\Http\Controllers\admin\AddaccountantController::class,'update'])->name('update.accountant');

    //MBl prefix
    Route::get('/add-prefix',[MblPrefixController::class,'index'])->name('addmblprefix');
    Route::post('/create=prefix',[MblPrefixController::class,'store'])->name('create.prefix');
    Route::get('/manage-prefix',[MblPrefixController::class,'manageprefix'])->name('manage.prefix');
    Route::get('/delete-prefix/{id}',[MblPrefixController::class,'delete'])->name('delete.prefix');
    Route::get('/edit-prefix/{id}',[MblPrefixController::class,'edit'])->name('edit.prefix');
    Route::post('/update-prefix/{id}',[MblPrefixController::class,'update'])->name('update.prefix');

    //upload template
    Route::get('/add-template',[TemplateController::class,'index'])->name('addtemplate');
    Route::post('/upload-template',[TemplateController::class,'store'])->name('upload.templates');
    Route::get('/manage-template',[TemplateController::class,'manage'])->name('manage.templates');
    Route::get('/delete-template/{id}',[TemplateController::class,'delete'])->name('delete.templates');
    Route::get('/download-template/{id}',[TemplateController::class,'download'])->name('download.templates');

});

Route::middleware(['forwarder'])->group(function () {

    Route::get('/feright/dashboard', function () {
        return view('feright.dashboard');
    })->name('feright.dashboard');

//SHipment
Route::get('/add-shipment',[ShipmentController::class,'index'])->name('add.shipment');
Route::post('/create-shipment',[ShipmentController::class,'store'])->name('create.shipment');
Route::get('/manage-shipment',[ShipmentController::class,'manage'])->name('manage.shipment');
Route::get('/delete-shipment/{id}',[ShipmentController::class,'delete'])->name('delete.shipment');
Route::get('/see-shipment/{id}',[ShipmentController::class,'seeDetails'])->name('see.shipment');
Route::get('/edit-shipment/{id}',[ShipmentController::class,'edit'])->name('edit.shipment');
Route::post('/update-shipment/{id}',[ShipmentController::class,'update'])->name('update.shipment');

//container

Route::get('/add-container',[ContainerController::class,'index'])->name('add.container');
    Route::post('/extract-ocr',
        [ContainerController::class,'extractOcr']
    )->name('extract.ocr');

    // Final Save
    Route::post('/container-upload/store',
        [ContainerController::class,'store']
    )->name('container.store');

    Route::get('/container/manage',
        [ContainerController::class,'manage'])
        ->name('container.manage');

    Route::get('/container/search',
        [ContainerController::class,'search'])
        ->name('container.search');

    Route::get('/container/edit/{id}',
        [ContainerController::class,'edit'])
        ->name('container.edit');

    Route::post('/container/update/{id}',
        [ContainerController::class,'update'])
        ->name('container.update');
    Route::get('/container/delete/{id}',
        [ContainerController::class,'delete']
    )->name('container.delete');

    //VGM
    Route::get('/vgm',
        [VgmController::class,'index'])
        ->name('vgm');

    Route::get('/vgm/search',
        [VgmController::class,'search'])
        ->name('vgm.search');
    Route::get('/vgm/create/{id}', [VgmController::class,'create'])->name('vgm.create');

    Route::post('/vgm/store', [VgmController::class,'store'])->name('vgm.store');
    Route::get('/vgm/delete/{id}', [VgmController::class,'delete'])
        ->name('vgm.delete');
    //pl
    Route::get('/pl', [PackinglistController::class,'index'])
        ->name('trpl.index');
    Route::get('/pl/containers/{bookingNumber}',
        [PackinglistController::class,'getContainers'])
        ->name('trpl.containers');
    Route::get('tr-packing-list/create/{id}',
        [PackinglistController::class,'create'])
        ->name('trpl.create');
    Route::post('/store',
        [PackinglistController::class,'store'])
        ->name('trpl.store');

    Route::get('/edit/{id}',
        [PackinglistController::class,'edit'])
        ->name('trpl.edit');

    Route::post('/update/{id}',
        [PackinglistController::class,'update'])
        ->name('trpl.update');

    Route::get('/preview/{id}',
        [PackinglistController::class,'preview'])
        ->name('trpl.preview');

    Route::get('/delete/{id}',
        [PackinglistController::class,'delete'])
        ->name('trpl.delete');
    Route::get(
        '/export-excel/{id}',
        [PackinglistController::class,'exportExcel']
    )->name('trpl.export.excel');

    Route::get(
        '/export-pdf/{id}',
        [PackinglistController::class,'exportPdf']
    )->name('trpl.export.pdf');
    //us pl
    Route::get('/us-pl', [UsPackingListController::class,'index'])->name('us.pl');

    Route::get('/us-pl/containers/{bookingNumber}', [UsPackingListController::class,'getContainers']);

    Route::get('/us-pl/create/{id}',
        [UsPackingListController::class,'create'])
        ->name('uspl.create');

    Route::get('/us-pl/edit/{id}', [UsPackingListController::class,'edit'])->name('uspl.edit');

    Route::get('/us-pl/preview/{id}', [UsPackingListController::class,'preview']);
    Route::post('/us-pl/store',
        [UsPackingListController::class,'store'])
        ->name('uspl.store');
    Route::post(
        '/us-pl/update/{id}',
        [UsPackingListController::class, 'update']
    )->name('uspl.update');
    Route::get(
        '/us-export-excel/{id}',
        [UspackingListController::class,'exportExcel']
    )->name('uspl.export.excel');

    Route::get('/create-isf',[IsfController::class,'create'])->name('isf.index');
    Route::get('/isf/get-shipment-data/{id}', [IsfController::class, 'getShipmentData'])
        ->name('isf.get-shipment-data');
    Route::post('/isf/store', [IsfController::class, 'store'])
        ->name('isf.store');
    Route::get('/isf/{id}/preview',
        [IsfController::class,'preview'])
        ->name('isf.preview');
    Route::get('/isf-manage',[IsfController::class,'manage'])->name('isf.manage');
    Route::get(
        '/isf-export-excel/{id}',
        [IsfController::class,'exportExcel']
    )->name('isf.export.excel');

    Route::get('/isf/edit/{id}', [IsfController::class, 'edit'])
        ->name('isf.edit');

    Route::post('/isf/update/{id}', [IsfController::class, 'update'])
        ->name('isf.update');
    Route::get('/isf/delete/{id}', [IsfController::class, 'delete'])
        ->name('isf.delete');


});


Route::middleware(['accountant'])->group(function () {

    Route::get('/accountant/dashboard', function () {
        return view('account.dashboard');
    })->name('account.dashboard');

});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
