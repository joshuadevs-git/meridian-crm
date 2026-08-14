<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeMonitorController extends Controller
{
    /**
     * Live Employee Monitor
     */
    public function index(Request $request)
    {
        $today = Carbon::today();

        $employees = Employee::with([
            'branch',
            'attendances' => function ($query) use ($today) {
                $query->whereDate('attendance_date', $today);
            },
            'schedules' => function ($query) use ($today) {
                $query->whereDate('schedule_date', $today);
            },
        ])
        ->where('is_active', true)
        ->orderBy('first_name')
        ->orderBy('last_name')
        ->get();

        $employees = $employees->map(function ($employee) use ($today) {

            $attendance = $employee->attendances->first();
            $schedule = $employee->schedules->first();

            /*
            |--------------------------------------------------------------------------
            | Default status
            |--------------------------------------------------------------------------
            */

            $status = 'Not Checked In';

            /*
            |--------------------------------------------------------------------------
            | Check approved leave
            |--------------------------------------------------------------------------
            */

            $onLeave = $employee->leaves()
                ->where('status', 'Approved')
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->exists();

            if ($onLeave) {

                $status = 'On Leave';

            } elseif ($schedule && $schedule->status === 'Off') {

                $status = 'Day Off';

            } elseif ($attendance) {

                /*
                |--------------------------------------------------------------------------
                | Already checked out
                |--------------------------------------------------------------------------
                */

                if ($attendance->time_out) {

                    $status = 'Checked Out';

                } else {

                    /*
                    |--------------------------------------------------------------------------
                    | Check if employee is currently on break
                    |--------------------------------------------------------------------------
                    */

                    $onBreak = false;

                    if (
                        $attendance->break_start &&
                        !$attendance->break_end
                    ) {
                        $onBreak = true;
                    }

                    if ($onBreak) {

                        $status = 'On Break';

                    } else {

                        $status = 'Working';
                    }
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Break information
            |--------------------------------------------------------------------------
            */

            $breakStart = $attendance?->break_start;
            $breakEnd = $attendance?->break_end;

            $breakElapsed = 0;
            $breakRemaining = 0;

            if ($breakStart && !$breakEnd) {

                $breakStartTime = Carbon::parse($breakStart);

                $breakElapsed = $breakStartTime->diffInMinutes(
                    now()
                );

                $breakRemaining = max(
                    60 - $breakElapsed,
                    0
                );
            }

            return [
                'employee' => $employee,
                'attendance' => $attendance,
                'schedule' => $schedule,
                'status' => $status,
                'break_start' => $breakStart,
                'break_end' => $breakEnd,
                'break_elapsed' => $breakElapsed,
                'break_remaining' => $breakRemaining,
            ];
        });

        /*
        |--------------------------------------------------------------------------
        | Summary
        |--------------------------------------------------------------------------
        */

        $summary = [
            'working' => $employees->where('status', 'Working')->count(),

            'break' => $employees->where('status', 'On Break')->count(),

            'not_checked_in' => $employees
                ->where('status', 'Not Checked In')
                ->count(),

            'checked_out' => $employees
                ->where('status', 'Checked Out')
                ->count(),

            'leave' => $employees
                ->where('status', 'On Leave')
                ->count(),

            'off' => $employees
                ->where('status', 'Day Off')
                ->count(),
        ];

        return view(
            'employee-monitor.index',
            compact(
                'employees',
                'summary'
            )
        );
    }
}