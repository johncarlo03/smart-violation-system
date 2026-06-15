<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SAO\SAOAdminController;
use App\Http\Controllers\Student\ChatbotController;
use App\Http\Controllers\CSO\ViolationController;
use App\Http\Controllers\Student\StudentController;
use App\Http\Controllers\Superadmin\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/violation', function () {
    return view('cso-dashboard');
})->middleware(['auth', 'role:2,3'])->name('cso.dashboard');

Route::get('/sao/dashboard', [SAOAdminController::class, 'index'])
    ->middleware(['auth', 'role:3'])
    ->name('sao.dashboard');

Route::get('/superadmin/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'role:4'])
    ->name('superadmin.dashboard');

Route::get('/dashboard', function () {
    if (Auth::user()->role === 4) return redirect()->route('superadmin.dashboard');
    if (Auth::user()->role === 3) return redirect()->route('sao.dashboard');
    if (Auth::user()->role === 2) return redirect()->route('cso.dashboard');
    
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/violations/records', [ViolationController::class, 'index'])
    ->middleware(['auth', 'role:2,3'])
    ->name('violations.index');


Route::get('/students/search', [StudentController::class, 'search'])->name('students.search');
// Route::get('/students/search', function (Request $request) {
//     return User::where('role', 1)
//         ->where('name', 'like', '%' . $request->q . '%')
//         ->limit(10)
//         ->get(['id', 'name', 'rfid_number', 'id_number']);
// });

Route::post('/violations', [ViolationController::class, 'store'])->name('violations.store');

Route::get('/chatbot/ask', [ChatbotController::class, 'ask'])->middleware(['auth']);


require __DIR__.'/auth.php';
