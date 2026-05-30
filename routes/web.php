<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
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
    Route::get('/add-feright',[\App\Http\Controllers\admin\AddFerightController::class,'index'])->name('addferight');
    Route::post('/create-feright',[\App\Http\Controllers\admin\AddFerightController::class,'store'])->name('create.feright');

});

Route::middleware(['forwarder'])->group(function () {

    Route::get('/feright/dashboard', function () {
        return view('feright.dashboard');
    })->name('feright.dashboard');

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
