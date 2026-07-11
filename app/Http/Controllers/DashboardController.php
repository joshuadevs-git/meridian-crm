<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Models\Employee;
use App\Models\Branch;
use App\Models\Attendance;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
{
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
));
}
}