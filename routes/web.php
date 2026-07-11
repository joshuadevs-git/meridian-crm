<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('employees/export',[EmployeeController::class, 'export'])->name('employees.export');

    Route::post('employees/import',[EmployeeController::class, 'import'])->name('employees.import');

    Route::resource('employees', EmployeeController::class);

    Route::get('/branches', [BranchController::class, 'index'])->name('branches.index');

    Route::resource('branches', BranchController::class);

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

    Route::resource('attendances', AttendanceController::class);

    

        
});

require __DIR__.'/auth.php';