<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\WorksController;
use App\Http\Controllers\ClinicsController;
use App\Http\Controllers\MechanicsController;
use App\Http\Controllers\WorksTypesController;


Route::get('/', [WorksController::class, 'index'])->name('works.index');
Route::post('/', [WorksController::class, 'index'])->name('works.index');


Route::prefix('works')->group(function ()
{
	Route::get('/create', [WorksController::class, 'create'])->name('works.create');
	Route::get('/edit/{id}', [WorksController::class, 'edit'])->name('works.edit')->where('id', '[0-9]+');
	Route::get('/destroy/{id}', [WorksController::class, 'destroy'])->name('works.destroy')->where('id', '[0-9]+');

	Route::post('/store', [WorksController::class, 'store'])->name('works.store');
	Route::post('/update/{id}', [WorksController::class, 'update'])->name('works.update')->where('id', '[0-9]+');
});

Route::prefix('clinics')->group(function ()
{
	Route::get('/', [ClinicsController::class, 'index'])->name('clinics.index');
	Route::get('/create', [ClinicsController::class, 'create'])->name('clinics.create');
	Route::get('/edit/{id}', [ClinicsController::class, 'edit'])->name('clinics.edit')->where('id', '[0-9]+');
	Route::get('/destroy/{id}', [ClinicsController::class, 'destroy'])->name('clinics.destroy')->where('id', '[0-9]+');
	Route::get('/invoice/{id}/{date}', [ClinicsController::class, 'invoiceGet'])->name('clinics.invoice.get');

	Route::post('/', [ClinicsController::class, 'index'])->name('clinics.index');
	Route::post('/data', [ClinicsController::class, 'indexData'])->name('clinics.index.data');
	Route::post('/store', [ClinicsController::class, 'store'])->name('clinics.store');
	Route::post('/update/{id}', [ClinicsController::class, 'update'])->name('clinics.update')->where('id', '[0-9]+');
	Route::post('/invoice', [ClinicsController::class, 'invoice'])->name('clinics.invoice');
});

Route::prefix('mechanics')->group(function ()
{
	Route::get('/', [MechanicsController::class, 'index'])->name('mechanics.index');
	Route::get('/create', [MechanicsController::class, 'create'])->name('mechanics.create');
	Route::get('/edit/{id}', [MechanicsController::class, 'edit'])->name('mechanics.edit')->where('id', '[0-9]+');
	Route::get('/destroy/{id}', [MechanicsController::class, 'destroy'])->name('mechanics.destroy')->where('id', '[0-9]+');
	Route::get('/invoice/{id}/{date}', [MechanicsController::class, 'invoiceGet'])->name('mechanics.invoice.get');

	Route::post('/', [MechanicsController::class, 'index'])->name('mechanics.index');
	Route::post('/data', [MechanicsController::class, 'indexData'])->name('mechanics.index.data');
	Route::post('/store', [MechanicsController::class, 'store'])->name('mechanics.store');
	Route::post('/update/{id}', [MechanicsController::class, 'update'])->name('mechanics.update')->where('id', '[0-9]+');
	Route::post('/invoice', [MechanicsController::class, 'invoice'])->name('mechanics.invoice');
});

Route::prefix('works_types')->group(function ()
{
	Route::get('/', [WorksTypesController::class, 'index'])->name('works_types.index');
	Route::get('/create', [WorksTypesController::class, 'create'])->name('works_types.create');
	Route::get('/import_preview', [WorksTypesController::class, 'preview'])->name('works_types.import_preview');
	Route::get('/edit/{id}', [WorksTypesController::class, 'edit'])->name('works_types.edit')->where('id', '[0-9]+');
	Route::get('/destroy/{id}', [WorksTypesController::class, 'destroy'])->name('works_types.destroy')->where('id', '[0-9]+');

	Route::post('/store', [WorksTypesController::class, 'store'])->name('works_types.store');
	Route::post('/import', [WorksTypesController::class, 'import'])->name('works_types.import');
	Route::post('/update/{id}', [WorksTypesController::class, 'update'])->name('works_types.update')->where('id', '[0-9]+');
});
