<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\admin\MblPrefixController;
use App\Http\Controllers\admin\TemplateController;
use App\Http\Controllers\freight\ShipmentController;
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
Route::get('/add-shipment',[ShipmentController::class,'index'])->name('add.shipment');
Route::post('/create-shipment',[ShipmentController::class,'store'])->name('create.shipment');
Route::get('/manage-shipment',[ShipmentController::class,'manage'])->name('manage.shipment');
Route::get('/delete-shipment/{id}',[ShipmentController::class,'delete'])->name('delete.shipment');
Route::get('/see-shipment/{id}',[ShipmentController::class,'seeDetails'])->name('see.shipment');
Route::get('/edit-shipment/{id}',[ShipmentController::class,'edit'])->name('edit.shipment');
Route::post('/update-shipment/{id}',[ShipmentController::class,'update'])->name('update.shipment');

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
