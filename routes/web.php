<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmployeeMonitorController;
use App\Http\Controllers\EmployeeScheduleController;
use App\Http\Controllers\ForcePasswordChangeController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Force Password Change
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::get('/force-password-change', [ForcePasswordChangeController::class, 'edit'])
        ->name('password.force');

    Route::post('/force-password-change', [ForcePasswordChangeController::class, 'update'])
        ->name('password.force.update');
});



/*
|--------------------------------------------------------------------------
| Authenticated Users
|--------------------------------------------------------------------------
*/

    Route::middleware(['auth', 'force.password'])->group(function () {

Route::middleware(['auth', 'force.password', 'role:Admin,HR'])
    ->group(function () {

        Route::get(
            '/employee-monitor',
            [EmployeeMonitorController::class, 'index']
        )->name('employee-monitor.index');

    });
    


    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');


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


    /*
    |--------------------------------------------------------------------------
    | Employee - My Profile
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Employee')->group(function () {

        Route::get('/my-profile', function () {
            return view('employee.profile');
        })->name('employee.profile');

    });

        Route::get('/my-profile', [EmployeeController::class, 'myProfile'])
        ->name('my-profile');

        Route::get('/my-attendance', [AttendanceController::class, 'myAttendance'])
        ->name('my-attendance');

        Route::get('/my-payroll', [PayrollController::class, 'myPayroll'])
        ->name('my-payroll');

        Route::get('/my-leaves', [LeaveController::class, 'myLeaves'])
        ->name('my-leaves');

        Route::get('/my-leaves/create', [LeaveController::class, 'createMyLeave'])
        ->name('employee.leaves.create');

        Route::post('/my-leaves', [LeaveController::class, 'storeMyLeave'])
        ->name('employee.leaves.store');

        Route::post('/my-attendance/check-in', [AttendanceController::class, 'checkIn'])
        ->name('my-attendance.check-in');

        Route::post('/my-attendance/check-out', [AttendanceController::class, 'checkOut'])
        ->name('my-attendance.check-out');

        Route::get('/my-schedule', [EmployeeScheduleController::class, 'mySchedule'])
        ->name('my-schedule');

        Route::post('/my-attendance/start-break', [ AttendanceController::class,'startBreak'])
        ->name('my-attendance.start-break');

        Route::post('/my-attendance/end-break', [AttendanceController::class,'endBreak'])
        ->name('my-attendance.end-break');


    /*
    |--------------------------------------------------------------------------
    | Admin Only
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Admin')->group(function () {

        // User Management
        Route::resource('users', UserController::class);

        // Role Management
        Route::resource('roles', RoleController::class);

    });


    /*
    |--------------------------------------------------------------------------
    | Admin + HR
    |--------------------------------------------------------------------------
    */

    Route::middleware('role:Admin,HR')->group(function () {


        /*
        |--------------------------------------------------------------------------
        | Employees
        |--------------------------------------------------------------------------
        */

        Route::get('/employees/export', [EmployeeController::class, 'export'])
            ->name('employees.export');

        Route::post('/employees/import', [EmployeeController::class, 'import'])
            ->name('employees.import');

        Route::resource('employees', EmployeeController::class);
        
        /*
|--------------------------------------------------------------------------
| Employee Schedules
|--------------------------------------------------------------------------
*/

        Route::get('/schedules', [EmployeeScheduleController::class, 'index'])
        ->name('schedules.index');

        Route::post('/schedules', [EmployeeScheduleController::class, 'store'])
        ->name('schedules.store');
  
        Route::delete('/schedules/{schedule}', [EmployeeScheduleController::class, 'destroy'])
        ->name('schedules.destroy');

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
        | Leave Management
        |--------------------------------------------------------------------------
        */

        Route::resource('leaves', LeaveController::class);


        /*
        |--------------------------------------------------------------------------
        | Payroll Management
        |--------------------------------------------------------------------------
        */

        Route::post('/payrolls/generate', [PayrollController::class, 'generate'])
         ->name('payrolls.generate');

         Route::post('/payrolls/calculate/save', [PayrollController::class, 'saveCalculated'])
        ->name('payrolls.calculate.save');

        Route::post('/payrolls/calculate', [PayrollController::class, 'calculate'])
        ->name('payrolls.calculate.preview');

         Route::get('/payrolls/calculate', [PayrollController::class, 'calculate'])
         ->name('payrolls.calculate');

        Route::resource('payrolls', PayrollController::class);


        /*
        |--------------------------------------------------------------------------
        | Reports
        |--------------------------------------------------------------------------
        */

        Route::prefix('reports')->group(function () {

            // Attendance Reports

            Route::get('/attendance', [ReportController::class, 'attendance'])
                ->name('reports.attendance');

            Route::get('/attendance/excel', [ReportController::class, 'exportExcel'])
                ->name('reports.attendance.excel');

            Route::get('/attendance/csv', [ReportController::class, 'exportCsv'])
                ->name('reports.attendance.csv');

            Route::get('/attendance/pdf', [ReportController::class, 'exportPdf'])
                ->name('reports.attendance.pdf');


            // Leave Reports

            Route::get('/leaves', [ReportController::class, 'leaveReport'])
                ->name('reports.leaves');

            Route::get('/leaves/excel', [ReportController::class, 'leaveExcel'])
                ->name('reports.leaves.excel');

            Route::get('/leaves/csv', [ReportController::class, 'leaveCsv'])
                ->name('reports.leaves.csv');

            Route::get('/leaves/pdf', [ReportController::class, 'leavePdf'])
                ->name('reports.leaves.pdf');


            // Payroll Reports

            Route::get('/payroll', [PayrollController::class, 'report'])
                ->name('reports.payroll');

            Route::get('/payroll/excel', [PayrollController::class, 'exportExcel'])
                ->name('reports.payroll.excel');

            Route::get('/payroll/csv', [PayrollController::class, 'exportCsv'])
                ->name('reports.payroll.csv');

            Route::get('/payroll/pdf', [PayrollController::class, 'exportPdf'])
                ->name('reports.payroll.pdf');

        });

    });

});


require __DIR__.'/auth.php';