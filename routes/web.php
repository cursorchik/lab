<?php

    use App\Http\Controllers\AccountingController;
    use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WorksController;
use App\Http\Controllers\ClinicsController;
use App\Http\Controllers\MechanicsController;
use App\Http\Controllers\WorksTypesController;


Route::get('/', [WorksController::class, 'index'])->name('works.index');
Route::post('/', [WorksController::class, 'index'])->name('works.index');
Route::get('/works/create', [WorksController::class, 'create'])->name('works.create');
Route::post('/works/store', [WorksController::class, 'store'])->name('works.store');
Route::get('/works/edit/{id}', [WorksController::class, 'edit'])->name('works.edit')->where('id', '[0-9]+');
Route::post('/works/update/{id}', [WorksController::class, 'update'])->name('works.update')->where('id', '[0-9]+');
Route::get('/works/destroy/{id}', [WorksController::class, 'destroy'])->name('works.destroy')->where('id', '[0-9]+');

Route::get('/clinics', [ClinicsController::class, 'index'])->name('clinics.index');
Route::post('/clinics', [ClinicsController::class, 'index'])->name('clinics.index');
Route::post('/clinics/data', [ClinicsController::class, 'indexData'])->name('clinics.index.data');
Route::get('/clinics/create', [ClinicsController::class, 'create'])->name('clinics.create');
Route::post('/clinics/store', [ClinicsController::class, 'store'])->name('clinics.store');
Route::get('/clinics/edit/{id}', [ClinicsController::class, 'edit'])->name('clinics.edit')->where('id', '[0-9]+');
Route::post('/clinics/update/{id}', [ClinicsController::class, 'update'])->name('clinics.update')->where('id', '[0-9]+');
Route::get('/clinics/destroy/{id}', [ClinicsController::class, 'destroy'])->name('clinics.destroy')->where('id', '[0-9]+');
Route::post('/clinics/invoice', [ClinicsController::class, 'invoice'])->name('clinics.invoice');
Route::get('/clinics/invoice/{id}/{date}', [ClinicsController::class, 'invoiceGet'])->name('clinics.invoice.get');

Route::get('/mechanics', [MechanicsController::class, 'index'])->name('mechanics.index');
Route::post('/mechanics', [MechanicsController::class, 'index'])->name('mechanics.index');
Route::post('/mechanics/data', [MechanicsController::class, 'indexData'])->name('mechanics.index.data');
Route::get('/mechanics/create', [MechanicsController::class, 'create'])->name('mechanics.create');
Route::post('/mechanics/store', [MechanicsController::class, 'store'])->name('mechanics.store');
Route::get('/mechanics/edit/{id}', [MechanicsController::class, 'edit'])->name('mechanics.edit')->where('id', '[0-9]+');
Route::post('/mechanics/update/{id}', [MechanicsController::class, 'update'])->name('mechanics.update')->where('id', '[0-9]+');
Route::get('/mechanics/destroy/{id}', [MechanicsController::class, 'destroy'])->name('mechanics.destroy')->where('id', '[0-9]+');
Route::post('/mechanics/invoice', [MechanicsController::class, 'invoice'])->name('mechanics.invoice');
Route::get('/mechanics/invoice/{id}/{date}', [MechanicsController::class, 'invoiceGet'])->name('mechanics.invoice.get');

Route::get('/works_types', [WorksTypesController::class, 'index'])->name('works_types.index');
Route::get('/works_types/create', [WorksTypesController::class, 'create'])->name('works_types.create');
Route::post('/works_types/store', [WorksTypesController::class, 'store'])->name('works_types.store');

Route::get('/works_types/import_preview', [WorksTypesController::class, 'preview'])->name('works_types.import_preview');
Route::post('/works_types/import', [WorksTypesController::class, 'import'])->name('works_types.import');

Route::get('/works_types/edit/{id}', [WorksTypesController::class, 'edit'])->name('works_types.edit')->where('id', '[0-9]+');
Route::post('/works_types/update/{id}', [WorksTypesController::class, 'update'])->name('works_types.update')->where('id', '[0-9]+');
Route::get('/works_types/destroy/{id}', [WorksTypesController::class, 'destroy'])->name('works_types.destroy')->where('id', '[0-9]+');


//require __DIR__.'/auth.php';
