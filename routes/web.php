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
use App\Http\Controllers\freight\ProductController;
use App\Http\Controllers\admin\plController;
use App\Http\Controllers\admin\UsplController;
use App\Http\Controllers\admin\IsfController as adminisf;
use App\Http\Controllers\admin\VgmController as adminvgm;
use App\Http\Controllers\admin\ContainerController as adminContainer;
use App\Http\Controllers\admin\ShipmentController as adminShipment;
use App\Http\Controllers\account\CalculationController;
use App\Http\Controllers\account\CommercialInvoiceController;
use App\Http\Controllers\admin\CalculationController as adminCalculation;
use App\Http\Controllers\admin\CommercialInvoiceController as adminCommercial;



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


    //Tr pl admin
    Route::get('/admin/pl',[plController::class,'index'])->name('trpl.index.admin');
    Route::get('/admin/tr-packing-list/create/{id}',
        [plController::class,'create'])
        ->name('trpl.admin.create');
    Route::post('/admin/store',
        [plController::class,'store'])
        ->name('trpl.admin.store');

    Route::get('/admin/edit/{id}',
        [plController::class,'edit'])
        ->name('trpl.admin.edit');

    Route::post('/admin/update/{id}',
        [plController::class,'update'])
        ->name('trpl.admin.update');

    Route::get('/admin/preview/{id}',
        [plController::class,'preview'])
        ->name('trpl.admin.preview');
    Route::get('/admin/pl/containers/{bookingNumber}',
        [plController::class,'getContainers'])
        ->name('trpl.admin.containers');

    Route::get('/admin/delete/{id}',
        [plController::class,'delete'])
        ->name('trpl.admin.delete');
    Route::get(
        '/admin/export-excel/{id}',
        [plController::class,'exportExcel']
    )->name('trpl.admin.export.excel');

    Route::get(
        'admin/export-pdf/{id}',
        [plController::class,'exportPdf']
    )->name('trpl.admin.export.pdf');

    //Us Pl Admin
    Route::get('/admin/us-pl', [UsplController::class,'index'])->name('admin.us.pl');

    Route::get('/admin/us-pl/containers/{bookingNumber}', [UsplController::class,'getContainers']);

    Route::get('/admin/us-pl/create/{container_number}', [UsplController::class,'create'])
        ->name('admin.us.pl.create');

    Route::get('/admin/us-pl/edit/{id}', [UsplController::class,'edit'])->name('admin.uspl.edit');

    Route::get('/admin/us-pl/preview/{id}', [UsplController::class,'preview'])->name('admin.uspl.preview');
    Route::post('/admin/us-pl/store',
        [UsplController::class,'store'])
        ->name('admin.uspl.store');
    Route::post(
        '/admin/us-pl/update/{id}',
        [UsplController::class, 'update']
    )->name('admin.uspl.update');
    Route::get(
        '/admin/us-export-excel/{id}',
        [UsplController::class,'exportExcel']
    )->name('admin.uspl.export.excel');
    Route::get(
        '/admin/us-export-pdf/{id}',
        [UsplController::class,'exportPdf']
    )->name('admin.uspl.export.pdf');
    Route::get('/admin/uspl-delete/{id}',[UsplController::class,'delete'])->name('admin.us.delete');

    //adminisf

    Route::get('/admin/create-isf',[adminisf::class,'create'])->name('admin.isf.index');
    Route::get('/admin/isf/get-shipment-data/{id}', [adminisf::class, 'getShipmentData'])
        ->name('admin.isf.get-shipment-data');
    Route::post('/admin/isf/store', [adminisf::class, 'store'])
        ->name('admin.isf.store');
    Route::get('/admin/isf/{id}/preview',
        [adminisf::class,'preview'])
        ->name('admin.isf.preview');
    Route::get('/admin/isf-manage',[adminisf::class,'manage'])->name('admin.isf.manage');
    Route::get(
        '/admin/isf-export-excel/{id}',
        [adminisf::class,'exportExcel']
    )->name('admin.isf.export.excel');

    Route::get('/admin/isf/edit/{id}', [adminisf::class, 'edit'])
        ->name('admin.isf.edit');

    Route::post('/admin/isf/update/{id}', [adminisf::class, 'update'])
        ->name('admin.isf.update');
    Route::get('/admin/isf/delete/{id}', [adminisf::class, 'delete'])
        ->name('admin.isf.delete');
    Route::get('/admin/isf/{id}/pdf',
        [IsfController::class, 'exportPdf'])
        ->name('admin.isf.pdf');

//Admin vgm
    Route::get('/admin/vgm',
        [adminvgm::class,'index'])
        ->name('admin.vgm');

    Route::get('/admin/vgm/search',
        [adminvgm::class,'search'])
        ->name('admin.vgm.search');
    Route::get('/vgm/create/{id}', [adminvgm::class,'create'])->name('admin.vgm.create');

    Route::post('/admin/vgm/store', [adminvgm::class,'store'])->name('admin.vgm.store');
    Route::post('/admin/vgm/extract-pdf',
        [adminvgm::class,'extractPdf'])
        ->name('admin.vgm.extract');
    Route::get('/admin/vgm/delete/{id}', [adminvgm::class,'delete'])
        ->name('admin.vgm.delete');
    Route::get('/admin/vgm/download/{id}',
        [adminvgm::class,'download'])
        ->name('admin.vgm.download');

    //admin Container

    Route::get('/admin/add-container',[adminContainer::class,'index'])->name('admin.add.container');
    Route::post('/admin/extract-ocr',
        [adminContainer::class,'extractOcr']
    )->name('admin.extract.ocr');

    // Final Save
    Route::post('/admin/container-upload/store',
        [adminContainer::class,'store']
    )->name('admin.container.store');

    Route::get('/admin/container/manage',
        [adminContainer::class,'manage'])
        ->name('admin.container.manage');

    Route::get('/admin/container/search',
        [adminContainer::class,'search'])
        ->name('admin.container.search');

    Route::get('/admin/container/edit/{id}',
        [ContainerController::class,'edit'])
        ->name('admin.container.edit');

    Route::post('/admin/container/update/{id}',
        [adminContainer::class,'update'])
        ->name('admin.container.update');
    Route::get('/admin/container/delete/{id}',
        [adminContainer::class,'delete']
    )->name('admin.container.delete');

//admin Shipment
    Route::get('/admin/add-shipment',[adminShipment::class,'index'])->name('admin.add.shipment');
    Route::post('/admin/create-shipment',[adminShipment::class,'store'])->name('admin.create.shipment');
    Route::get('/admin/manage-shipment',[adminShipment::class,'manage'])->name('admin.manage.shipment');
    Route::get('/admin/delete-shipment/{id}',[adminShipment::class,'delete'])->name('admin.delete.shipment');
    Route::get('/admin/see-shipment/{id}',[adminShipment::class,'seeDetails'])->name('admin.see.shipment');
    Route::get('/admin/edit-shipment/{id}',[adminShipment::class,'edit'])->name('admin.edit.shipment');
    Route::post('/admin/update-shipment/{id}',[adminShipment::class,'update'])->name('admin.update.shipment');

    //Admin Account
    Route::prefix('/admin/account/calculation')
        ->name('account.calculation.admin.')
        ->group(function () {

            Route::get('/',
                [adminCalculation::class, 'index'])
                ->name('index');

            Route::get('/create',
                [adminCalculation::class, 'create'])
                ->name('create');

            Route::get('/load-products/{shipment}',
                [adminCalculation::class, 'loadProducts'])
                ->name('loadProducts');

            Route::post('/store',
                [adminCalculation::class, 'store'])
                ->name('store');

            Route::get('/edit/{id}',
                [adminCalculation::class, 'edit'])
                ->name('edit');

            Route::post('/update/{id}',
                [adminCalculation::class, 'update'])
                ->name('update');

            Route::get('/show/{id}',
                [adminCalculation::class, 'show'])
                ->name('show');

            Route::get('/export-excel/{id}',
                [adminCalculation::class, 'exportExcel'])
                ->name('exportExcel');

            Route::delete('/delete/{id}',
                [adminCalculation::class, 'destroy'])
                ->name('delete');
        });

    //adminCommercial
    Route::prefix('/admin/account/commercial')
        ->name('account.commercial.admin.')
        ->group(function () {

            Route::get(
                '/',
                [adminCommercial::class,'index']
            )->name('index');

            Route::get(
                '/create',
                [adminCommercial::class,'create']
            )->name('create');

            Route::get(
                '/load/{shipment}',
                [adminCommercial::class,'loadCalculation']
            )->name('load');

            Route::post(
                '/store',
                [adminCommercial::class,'store']
            )->name('store');

            Route::get(
                '/show/{id}',
                [adminCommercial::class,'show']
            )->name('show');

            Route::get(
                '/delete/{id}',
                [adminCommercial::class,'destroy']
            )->name('delete');
            Route::get(
                '/edit/{id}',
                [adminCommercial::class,'edit']
            )->name('edit');

            Route::post(
                '/update/{id}',
                [adminCommercial::class,'update']
            )->name('update');
            Route::get(
                '/export-excel/{id}',
                [adminCommercial::class,'exportExcel']
            )->name('exportExcel');

            Route::get('/export-pdf/{id}',[adminCommercial::class,'exportPdf'])->name('exportPdf');
        });




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

//product
    Route::get('/add-product',[ProductController::class,'index'])->name('add.product');
    Route::post('/create-product',[ProductController::class,'store'])->name('create.product');

    Route::get('/manage-product',[ProductController::class,'manage'])->name('manage.product');

    Route::get('/edit-product/{id}',[ProductController::class,'edit'])->name('edit.product');
    Route::post('/update-product/{id}',[ProductController::class,'update'])->name('update.product');

    Route::get('/delete-product/{id}',[ProductController::class,'delete'])->name('delete.product');

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
    Route::post('/vgm/extract-pdf',
        [VgmController::class,'extractPdf'])
        ->name('vgm.extract');
    Route::get('/vgm/delete/{id}', [VgmController::class,'delete'])
        ->name('vgm.delete');
    Route::get('/vgm/download/{id}',
        [VgmController::class,'download'])
        ->name('vgm.download');
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

    Route::get('/us-pl/create/{container_number}', [UsPackingListController::class,'create'])
        ->name('us.pl.create');

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
    Route::get(
        '/us-export-pdf/{id}',
        [UspackingListController::class,'exportPdf']
    )->name('uspl.export.pdf');
    Route::get('/uspl-delete/{id}',[UspackingListController::class,'delete'])->name('us.delete');

    //isf

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
    Route::get('/isf/{id}/pdf',
        [IsfController::class, 'exportPdf'])
        ->name('isf.pdf');


});


Route::middleware(['accountant'])->group(function () {
    Route::get('/account/dashboard', function () {
        return view('account.dashboard');
    })->name('account.dashboard');

    /*
        |--------------------------------------------------------------------------
        | Calculation Module
        |--------------------------------------------------------------------------
        */

    Route::prefix('account/calculation')
        ->name('account.calculation.')
        ->group(function () {

            Route::get('/',
                [CalculationController::class, 'index'])
                ->name('index');

            Route::get('/create',
                [CalculationController::class, 'create'])
                ->name('create');

            Route::get('/load-products/{shipment}',
                [CalculationController::class, 'loadProducts'])
                ->name('loadProducts');

            Route::post('/store',
                [CalculationController::class, 'store'])
                ->name('store');

            Route::get('/edit/{id}',
                [CalculationController::class, 'edit'])
                ->name('edit');

            Route::post('/update/{id}',
                [CalculationController::class, 'update'])
                ->name('update');

            Route::get('/show/{id}',
                [CalculationController::class, 'show'])
                ->name('show');

            Route::get('/export-excel/{id}',
                [CalculationController::class, 'exportExcel'])
                ->name('exportExcel');

            Route::delete('/delete/{id}',
                [CalculationController::class, 'destroy'])
                ->name('delete');
        });
    Route::prefix('account/commercial')
        ->name('account.commercial.')
        ->group(function () {

            Route::get(
                '/',
                [CommercialInvoiceController::class,'index']
            )->name('index');

            Route::get(
                '/create',
                [CommercialInvoiceController::class,'create']
            )->name('create');

            Route::get(
                '/load/{shipment}',
                [CommercialInvoiceController::class,'loadCalculation']
            )->name('load');

            Route::post(
                '/store',
                [CommercialInvoiceController::class,'store']
            )->name('store');

            Route::get(
                '/show/{id}',
                [CommercialInvoiceController::class,'show']
            )->name('show');

            Route::get(
                '/delete/{id}',
                [CommercialInvoiceController::class,'destroy']
            )->name('delete');
            Route::get(
                '/edit/{id}',
                [CommercialInvoiceController::class,'edit']
            )->name('edit');

            Route::post(
                '/update/{id}',
                [CommercialInvoiceController::class,'update']
            )->name('update');
            Route::get(
                '/export-excel/{id}',
                [CommercialInvoiceController::class,'exportExcel']
            )->name('exportExcel');

            Route::get('/export-pdf/{id}',[CommercialInvoiceController::class,'exportPdf'])->name('exportPdf');
        });


});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::get('/phpinfo', function () {
    phpinfo();
});
Route::get('/test-libreoffice', [PackinglistController::class, 'testLibreOffice']);
Route::get('/pl/containers/{bookingNumber}',
    [PackinglistController::class,'getContainers'])
    ->name('trpl.containers');

require __DIR__.'/auth.php';