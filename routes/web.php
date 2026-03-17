<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/cso/dashboard', function () {
    return view('cso-dashboard');
})->middleware(['auth'])->name('cso.dashboard');

Route::get('/sao-admin/dashboard', function () {
    return view('sao-dashboard');
})->middleware(['auth'])->name('sao.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/students/search', function (Request $request) {
    return User::where('role', 1)
        ->where('name', 'like', '%' . $request->q . '%')
        ->limit(10)
        ->get(['id', 'name', 'rfid_number', 'id_number']);
});

require __DIR__.'/auth.php';
