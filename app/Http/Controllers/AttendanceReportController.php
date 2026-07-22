<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;

class AttendanceReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month ?? now()->format('Y-m');

        $start = Carbon::parse($month . '-01')->startOfMonth();
        $end = Carbon::parse($month . '-01')->endOfMonth();

        $employees = Employee::with('branch')
            ->orderBy('first_name')
            ->get();

        $report = [];

        foreach ($employees as $employee) {

            $attendance = Attendance::where('employee_id', $employee->id)
                ->whereBetween('attendance_date', [$start, $end]);

            $report[] = [
                'employee' => $employee,
                'present' => (clone $attendance)->where('status', 'Present')->count(),
                'late' => (clone $attendance)->where('status', 'Late')->count(),
                'absent' => (clone $attendance)->where('status', 'Absent')->count(),
                'leave' => (clone $attendance)->where('status', 'Leave')->count(),
            ];
        }

        return view('reports.attendance', compact(
            'report',
            'month'
        ));
    }
}