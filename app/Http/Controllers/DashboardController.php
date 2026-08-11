<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Employee;
use App\Models\Branch;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Payroll;

class DashboardController extends Controller
{
public function index(Request $request)
{
    $user = auth()->user();

    if (!$user->role) {
        abort(403, 'No role assigned.');
    }

    /*
    |--------------------------------------------------------------------------
    | Admin Dashboard
    |--------------------------------------------------------------------------
    */

    if ($user->isAdmin()) {

        $totalEmployees = Employee::count();

        $activeEmployees = Employee::where('is_active', true)->count();

        $inactiveEmployees = Employee::where('is_active', false)->count();

        $totalBranches = Branch::count();

        $recentEmployees = Employee::with('branch')
            ->latest()
            ->take(5)
            ->get();

        $branches = Branch::withCount('employees')
            ->orderBy('name')
            ->get();

        $branchLabels = $branches->pluck('name');

        $branchEmployeeCounts = $branches->pluck('employees_count');

        $totalAttendance = Attendance::count();

        $presentToday = Attendance::whereDate('attendance_date', Carbon::today())
            ->where('status', 'Present')
            ->count();

        $lateToday = Attendance::whereDate('attendance_date', Carbon::today())
            ->where('status', 'Late')
            ->count();

        $absentToday = Attendance::whereDate('attendance_date', Carbon::today())
            ->where('status', 'Absent')
            ->count();

        $statusLabels = [
            'Present',
            'Late',
            'Absent',
            'Leave',
        ];

        $statusCounts = [
            Attendance::where('status', 'Present')->count(),
            Attendance::where('status', 'Late')->count(),
            Attendance::where('status', 'Absent')->count(),
            Attendance::where('status', 'Leave')->count(),
        ];

        $dailyLabels = [];
        $dailyCounts = [];

        for ($i = 6; $i >= 0; $i--) {

            $date = Carbon::today()->subDays($i);

            $dailyLabels[] = $date->format('M d');

            $dailyCounts[] = Attendance::whereDate(
                'attendance_date',
                $date
            )->count();
        }

        $totalPayrolls = Payroll::count();

        $totalPayrollAmount = Payroll::sum('net_salary');

        $highestPayroll = Payroll::max('net_salary') ?? 0;

        $lowestPayroll = Payroll::min('net_salary') ?? 0;

        return view('dashboard.index', compact(
            'totalEmployees',
            'activeEmployees',
            'inactiveEmployees',
            'totalBranches',
            'recentEmployees',
            'branches',
            'branchLabels',
            'branchEmployeeCounts',
            'totalAttendance',
            'presentToday',
            'lateToday',
            'absentToday',
            'statusLabels',
            'statusCounts',
            'dailyLabels',
            'dailyCounts',
            'totalPayrolls',
            'totalPayrollAmount',
            'highestPayroll',
            'lowestPayroll'
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | HR Dashboard
    |--------------------------------------------------------------------------
    */

    if ($user->isHR()) {
        return view('dashboard.hr');
    }

    /*
    |--------------------------------------------------------------------------
    | Employee Dashboard
    |--------------------------------------------------------------------------
    */

    if ($user->isEmployee()) {
        return view('dashboard.employee');
    }

    abort(403, 'No role assigned.');
}
}