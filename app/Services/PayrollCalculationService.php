<?php

namespace App\Services;

use App\Models\Employee;
use Carbon\Carbon;

class PayrollCalculationService
{
    public function calculate(
        Employee $employee,
        string $periodStart,
        string $periodEnd,
        float $allowance = 0,
        float $otherDeduction = 0
    ): array {

        /*
        |--------------------------------------------------------------------------
        | Monthly salary
        |--------------------------------------------------------------------------
        */

        $monthlySalary = (float) $employee->salary;

        /*
        |--------------------------------------------------------------------------
        | Semi-monthly basic salary
        |--------------------------------------------------------------------------
        |
        | 1-15  = 50%
        | 16-end = 50%
        |
        */

        $basicSalary = $monthlySalary / 2;

        /*
        |--------------------------------------------------------------------------
        | Schedules ONLY inside this payroll period
        |--------------------------------------------------------------------------
        */

        $schedules = $employee->schedules()
            ->whereBetween('schedule_date', [
                $periodStart,
                $periodEnd
            ])
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Attendance ONLY inside this payroll period
        |--------------------------------------------------------------------------
        */

        $attendances = $employee->attendances()
            ->whereBetween('attendance_date', [
                $periodStart,
                $periodEnd
            ])
            ->get()
            ->keyBy(function ($attendance) {

                return Carbon::parse(
                    $attendance->attendance_date
                )->toDateString();

            });

        /*
        |--------------------------------------------------------------------------
        | Approved leaves overlapping this payroll period
        |--------------------------------------------------------------------------
        */

        $approvedLeaves = $employee->leaves()
            ->where('status', 'Approved')
            ->where(function ($query) use (
                $periodStart,
                $periodEnd
            ) {

                $query->whereDate(
                    'start_date',
                    '<=',
                    $periodEnd
                )->whereDate(
                    'end_date',
                    '>=',
                    $periodStart
                );

            })
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Working days
        |--------------------------------------------------------------------------
        */

        $workingDays = $schedules
            ->where('status', 'Working')
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Daily rate
        |--------------------------------------------------------------------------
        */

        $dailyRate = $workingDays > 0
            ? $basicSalary / $workingDays
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Hourly rate
        |--------------------------------------------------------------------------
        */

        $hourlyRate = $dailyRate / 8;

        /*
        |--------------------------------------------------------------------------
        | Counters
        |--------------------------------------------------------------------------
        */

        $absentDays = 0;

        $lateMinutes = 0;

        $excessBreakMinutes = 0;

        $absenceDeduction = 0;

        $lateDeduction = 0;

        $breakDeduction = 0;

        /*
        |--------------------------------------------------------------------------
        | Process schedules
        |--------------------------------------------------------------------------
        */

        foreach ($schedules as $schedule) {

            /*
            |--------------------------------------------------------------------------
            | OFF
            |--------------------------------------------------------------------------
            */

            if ($schedule->status === 'Off') {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Schedule date
            |--------------------------------------------------------------------------
            */

            $date = Carbon::parse(
                $schedule->schedule_date
            )->toDateString();

            /*
            |--------------------------------------------------------------------------
            | Approved leave
            |--------------------------------------------------------------------------
            */

            $isOnApprovedLeave = $approvedLeaves->contains(
                function ($leave) use ($date) {

                    $leaveStart = Carbon::parse(
                        $leave->start_date
                    )->toDateString();

                    $leaveEnd = Carbon::parse(
                        $leave->end_date
                    )->toDateString();

                    return $date >= $leaveStart
                        && $date <= $leaveEnd;
                }
            );

            /*
            |--------------------------------------------------------------------------
            | Leave = do NOT deduct
            |--------------------------------------------------------------------------
            */

            if ($isOnApprovedLeave) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Attendance
            |--------------------------------------------------------------------------
            */

            $attendance = $attendances->get($date);

            /*
            |--------------------------------------------------------------------------
            | No attendance = ABSENT
            |--------------------------------------------------------------------------
            */

            if (!$attendance) {

                $absentDays++;

                $absenceDeduction += $dailyRate;

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Explicit ABSENT
            |--------------------------------------------------------------------------
            */

            if (
                strtolower(
                    (string) $attendance->status
                ) === 'absent'
            ) {

                $absentDays++;

                $absenceDeduction += $dailyRate;

                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | LATE
            |--------------------------------------------------------------------------
            */

            if (
                $attendance->time_in &&
                $schedule->start_time
            ) {

                /*
                | Only get HH:MM:SS from the datetime cast.
                */

                $scheduledStartTime = Carbon::parse(
                    $schedule->start_time
                )->format('H:i:s');

                $actualTimeInValue = Carbon::parse(
                    $attendance->time_in
                )->format('H:i:s');

                $scheduledStart = Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $date . ' ' . $scheduledStartTime
                );

                $actualTimeIn = Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $date . ' ' . $actualTimeInValue
                );

                if (
                    $actualTimeIn->greaterThan(
                        $scheduledStart
                    )
                ) {

                    $minutesLate =
                        $scheduledStart->diffInMinutes(
                            $actualTimeIn
                        );

                    $lateMinutes += $minutesLate;

                    $lateDeduction +=
                        ($minutesLate / 60)
                        * $hourlyRate;
                }
            }

            /*
            |--------------------------------------------------------------------------
            | BREAK
            |--------------------------------------------------------------------------
            |
            | Normal scheduled break is NOT deducted.
            | Only excess break time is deducted.
            |
            */

            if (
                $attendance->break_start &&
                $attendance->break_end &&
                $schedule->break_start &&
                $schedule->break_end
            ) {

                /*
                |--------------------------------------------------------------------------
                | Actual break
                |--------------------------------------------------------------------------
                */

                $actualBreakStartTime = Carbon::parse(
                    $attendance->break_start
                )->format('H:i:s');

                $actualBreakEndTime = Carbon::parse(
                    $attendance->break_end
                )->format('H:i:s');

                $actualBreakStart = Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $date . ' ' . $actualBreakStartTime
                );

                $actualBreakEnd = Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $date . ' ' . $actualBreakEndTime
                );

                $actualBreakMinutes =
                    $actualBreakStart->diffInMinutes(
                        $actualBreakEnd
                    );

                /*
                |--------------------------------------------------------------------------
                | Scheduled break
                |--------------------------------------------------------------------------
                */

                $scheduledBreakStartTime = Carbon::parse(
                    $schedule->break_start
                )->format('H:i:s');

                $scheduledBreakEndTime = Carbon::parse(
                    $schedule->break_end
                )->format('H:i:s');

                $scheduledBreakStart = Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $date . ' ' . $scheduledBreakStartTime
                );

                $scheduledBreakEnd = Carbon::createFromFormat(
                    'Y-m-d H:i:s',
                    $date . ' ' . $scheduledBreakEndTime
                );

                $scheduledBreakMinutes =
                    $scheduledBreakStart->diffInMinutes(
                        $scheduledBreakEnd
                    );

                /*
                |--------------------------------------------------------------------------
                | Excess break
                |--------------------------------------------------------------------------
                */

                if (
                    $actualBreakMinutes >
                    $scheduledBreakMinutes
                ) {

                    $excess =
                        $actualBreakMinutes
                        - $scheduledBreakMinutes;

                    $excessBreakMinutes += $excess;

                    $breakDeduction +=
                        ($excess / 60)
                        * $hourlyRate;
                }
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Attendance deductions
        |--------------------------------------------------------------------------
        */

        $attendanceDeduction =
            $absenceDeduction
            + $lateDeduction
            + $breakDeduction;

        /*
        |--------------------------------------------------------------------------
        | Total deductions
        |--------------------------------------------------------------------------
        */

        $totalDeduction =
            $attendanceDeduction
            + $otherDeduction;

        /*
        |--------------------------------------------------------------------------
        | Net salary
        |--------------------------------------------------------------------------
        */

        $netSalary =
            $basicSalary
            + $allowance
            - $totalDeduction;

        /*
        |--------------------------------------------------------------------------
        | Prevent negative payroll
        |--------------------------------------------------------------------------
        */

        $netSalary = max(
            0,
            $netSalary
        );

        /*
        |--------------------------------------------------------------------------
        | Return
        |--------------------------------------------------------------------------
        */

        return [

            'monthly_salary' => round(
                $monthlySalary,
                2
            ),

            'basic_salary' => round(
                $basicSalary,
                2
            ),

            'allowance' => round(
                $allowance,
                2
            ),

            'working_days' => $workingDays,

            'daily_rate' => round(
                $dailyRate,
                2
            ),

            'hourly_rate' => round(
                $hourlyRate,
                2
            ),

            'absent_days' => $absentDays,

            'absence_deduction' => round(
                $absenceDeduction,
                2
            ),

            'late_minutes' => $lateMinutes,

            'late_deduction' => round(
                $lateDeduction,
                2
            ),

            'excess_break_minutes' =>
                $excessBreakMinutes,

            'break_deduction' => round(
                $breakDeduction,
                2
            ),

            'attendance_deduction' => round(
                $attendanceDeduction,
                2
            ),

            'other_deduction' => round(
                $otherDeduction,
                2
            ),

            'total_deduction' => round(
                $totalDeduction,
                2
            ),

            'net_salary' => round(
                $netSalary,
                2
            ),
        ];
    }


    public function saveCalculated(Request $request)
{
    $request->validate([
        'period_start' => 'required|date',
        'period_end' => 'required|date|after_or_equal:period_start',
        'payrolls' => 'required|array|min:1',
    ]);

    foreach ($request->payrolls as $payroll) {

        Payroll::updateOrCreate(
            [
                'employee_id' => $payroll['employee_id'],
                'payroll_date' => $request->period_end,
            ],
            [
                'basic_salary' => $payroll['basic_salary'],
                'allowance' => $payroll['allowance'] ?? 0,
                'deduction' => $payroll['total_deduction'] ?? 0,
                'net_salary' => $payroll['net_salary'],
            ]
        );
    }

    return redirect()
        ->route('payrolls.index')
        ->with(
            'success',
            'Payroll for ' .
            $request->period_start .
            ' to ' .
            $request->period_end .
            ' saved successfully.'
        );
}

}