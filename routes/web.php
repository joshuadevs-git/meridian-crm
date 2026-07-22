<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Employees
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/employees/export',
        [EmployeeController::class, 'export']
    )->name('employees.export');

    Route::post(
        '/employees/import',
        [EmployeeController::class, 'import']
    )->name('employees.import');

    Route::resource('employees', EmployeeController::class);

    /*
    |--------------------------------------------------------------------------
    | Branches
    |--------------------------------------------------------------------------
    */

    Route::resource('branches', BranchController::class);

    /*
    |--------------------------------------------------------------------------
    | Attendance
    |--------------------------------------------------------------------------
    */

    Route::resource('attendances', AttendanceController::class);

    /*
    |--------------------------------------------------------------------------
    | Reports
    |--------------------------------------------------------------------------
    */

    Route::prefix('reports')->group(function () {

    Route::get('/attendance', [ReportController::class, 'attendance'])
        ->name('reports.attendance');

    Route::get('/attendance/excel', [ReportController::class, 'exportExcel'])
        ->name('reports.attendance.excel');

    Route::get('/attendance/csv', [ReportController::class, 'exportCsv'])
        ->name('reports.attendance.csv');

    Route::get('/attendance/pdf', [ReportController::class, 'exportPdf'])
        ->name('reports.attendance.pdf');

});

    /*
    |--------------------------------------------------------------------------
    | Profile
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';