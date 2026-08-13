<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EmployeeScheduleController extends Controller
{
    /**
     * Display weekly schedules.
     */
    public function index(Request $request)
    {
        $employees = Employee::where('is_active', true)
            ->with('branch')
            ->orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        $employeeId = $request->employee_id;

        // Default to the current week's Monday
        $weekStart = $request->week
            ? Carbon::parse($request->week)->startOfWeek(Carbon::MONDAY)
            : Carbon::now()->startOfWeek(Carbon::MONDAY);

        $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);

        $employee = null;
        $schedules = collect();

        if ($employeeId) {

            $employee = Employee::with('branch')
                ->findOrFail($employeeId);

            $schedules = EmployeeSchedule::where('employee_id', $employeeId)
                ->whereBetween('schedule_date', [
                    $weekStart->toDateString(),
                    $weekEnd->toDateString(),
                ])
                ->orderBy('schedule_date')
                ->get()
                ->keyBy(function ($schedule) {
                    return $schedule->schedule_date->format('Y-m-d');
                });
        }

        return view('schedules.index', compact(
            'employees',
            'employee',
            'employeeId',
            'weekStart',
            'weekEnd',
            'schedules'
        ));
    }

    /**
     * Store or update a weekly schedule.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => [
                'required',
                'exists:employees,id',
            ],

            'week_start' => [
                'required',
                'date',
            ],

            'schedule' => [
                'required',
                'array',
            ],

            'schedule.*.status' => [
                'required',
                'in:Working,Off,Leave',
            ],

            'schedule.*.start_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'schedule.*.end_time' => [
                'nullable',
                'date_format:H:i',
            ],

            'schedule.*.break_start' => [
                'nullable',
                'date_format:H:i',
            ],

            'schedule.*.break_end' => [
                'nullable',
                'date_format:H:i',
            ],

            'schedule.*.notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        $weekStart = Carbon::parse($validated['week_start'])
            ->startOfWeek(Carbon::MONDAY);

        foreach ($validated['schedule'] as $date => $data) {

            $scheduleDate = Carbon::parse($date);

            // Only allow dates belonging to the selected week
            if (
                $scheduleDate->lt($weekStart) ||
                $scheduleDate->gt($weekStart->copy()->endOfWeek(Carbon::SUNDAY))
            ) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | Off / Leave
            |--------------------------------------------------------------------------
            |
            | These do not need working hours or break times.
            |
            */

            if ($data['status'] !== 'Working') {
                $data['start_time'] = null;
                $data['end_time'] = null;
                $data['break_start'] = null;
                $data['break_end'] = null;
            }

            /*
            |--------------------------------------------------------------------------
            | Working
            |--------------------------------------------------------------------------
            |
            | Default break is 12:00 PM - 1:00 PM.
            |
            */

            if ($data['status'] === 'Working') {

                if (
                    empty($data['break_start']) &&
                    empty($data['break_end'])
                ) {
                    $data['break_start'] = '12:00';
                    $data['break_end'] = '13:00';
                }
            }

            EmployeeSchedule::updateOrCreate(
                [
                    'employee_id' => $validated['employee_id'],
                    'schedule_date' => $scheduleDate->toDateString(),
                ],
                [
                    'status' => $data['status'],
                    'start_time' => $data['start_time'] ?? null,
                    'end_time' => $data['end_time'] ?? null,
                    'break_start' => $data['break_start'] ?? null,
                    'break_end' => $data['break_end'] ?? null,
                    'notes' => $data['notes'] ?? null,
                ]
            );
        }

        return redirect()
            ->route('schedules.index', [
                'employee_id' => $validated['employee_id'],
                'week' => $weekStart->toDateString(),
            ])
            ->with('success', 'Employee schedule saved successfully.');
    }

    /**
     * Delete a schedule for a specific day.
     */
    public function destroy(EmployeeSchedule $schedule)
    {
        $schedule->delete();

        return back()
            ->with('success', 'Schedule deleted successfully.');
    }


    /**
 * Display the logged-in employee's schedule.
 */
public function mySchedule(Request $request)
{
    $employee = auth()->user()->employee;

    if (!$employee) {
        abort(403, 'No employee profile is linked to this account.');
    }

    // Determine the selected week
    $weekStart = $request->week
        ? Carbon::parse($request->week)->startOfWeek(Carbon::MONDAY)
        : Carbon::now()->startOfWeek(Carbon::MONDAY);

    $weekEnd = $weekStart->copy()->endOfWeek(Carbon::SUNDAY);

    $schedules = EmployeeSchedule::where('employee_id', $employee->id)
        ->whereBetween('schedule_date', [
            $weekStart->toDateString(),
            $weekEnd->toDateString(),
        ])
        ->orderBy('schedule_date')
        ->get()
        ->keyBy(function ($schedule) {
            return $schedule->schedule_date->format('Y-m-d');
        });

    return view('employee.schedule', compact(
        'employee',
        'weekStart',
        'weekEnd',
        'schedules'
    ));
}


}