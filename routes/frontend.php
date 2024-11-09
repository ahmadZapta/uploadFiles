<?php

use App\Http\Controllers\Frontend\TempController;
use Illuminate\Support\Facades\Route;
    
Route::view('/', 'frontend.create')->name('create');

Route::post('/', [TempController::class, 'create'])->name('creatFiles');
Route::get('edit', [TempController::class, 'edit'])->name('edit');
Route::post('edit', [TempController::class, 'update'])->name('update');