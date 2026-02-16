<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use App\Http\Controllers\CSFController;
use App\Http\Controllers\CSFPrintController;


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');




Route::get('/csf', [CSFController::class, 'index'])->name('csf');
Route::post('/csf', [CSFController::class, 'store'])->name('csf.store');
Route::put('/csf/{id}', [CSFController::class, 'update'])->name('csf.update');
Route::delete('/csf/{id}', [CSFController::class, 'destroy'])->name('csf.destroy');

// Dependent Specific
Route::post('/csf/{csf_id}/dependents', [CSFController::class, 'storeDependent'])->name('csf.dependent.store');
Route::put('/csf/dependents/{id}', [CSFController::class, 'updateDependent'])->name('csf.dependent.update');
Route::delete('/csf/dependents/{id}', [CSFController::class, 'destroyDependent'])->name('csf.dependent.destroy');

Route::get('/csf/print/member/{id}', [CSFPrintController::class, 'memberPdf']);
Route::get('/csf/print/dependents/{id}', [CSFPrintController::class, 'dependentsPdf']);

Route::get('/csf/print-member-third-page/{id}', [CSFPrintController::class, 'thirdPrint']);


require __DIR__.'/settings.php';
