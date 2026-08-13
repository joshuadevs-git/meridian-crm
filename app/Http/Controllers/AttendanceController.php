<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\EmployeeSchedule;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;

class AttendanceController extends Controller
{
    /**
     * Display attendance records.
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $branch = $request->branch;
        $status = $request->status;
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        $attendances = Attendance::with('employee.branch')

            ->when($search, function ($query) use ($search) {
                $query->whereHas('employee', function ($q) use ($search) {
                    $q->where('employee_no', 'like', "%{$search}%")
                        ->orWhere('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                });
            })

            ->when($branch, function ($query) use ($branch) {
                $query->whereHas('employee', function ($q) use ($branch) {
                    $q->where('branch_id', $branch);
                });
            })

            ->when($status, function ($query) use ($status) {
                $query->where('status', $status);
            })

            ->when($dateFrom, function ($query) use ($dateFrom) {
                $query->whereDate('attendance_date', '>=', $dateFrom);
            })

            ->when($dateTo, function ($query) use ($dateTo) {
                $query->whereDate('attendance_date', '<=', $dateTo);
            })

            ->latest()
            ->paginate(10)
            ->withQueryString();

        $branches = Branch::orderBy('name')->get();

        return view('attendances.index', compact(
            'attendances',
            'branches',
            'search',
            'branch',
            'status',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Show create attendance form.
     */
    public function create()
    {
        $employees = Employee::where('is_active', true)
            ->orderBy('first_name')
            ->get();

        return view('attendances.create', compact('employees'));
    }

    /**
     * Store attendance.
     */
    public function store(StoreAttendanceRequest $request)
    {
        Attendance::create($request->validated());

        return redirect()
            ->route('attendances.index')
            ->with('success', 'Attendance created successfully.');
    }

    /**
     * Display attendance.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show edit form.
     */
    public function edit(Attendance $attendance)
    {
        $employees = Employee::where('is_active', true)
            ->orderBy('first_name')
            ->get();

        return view('attendances.edit', compact(
            'attendance',
            'employees'
        ));
    }

    /**
     * Update attendance.
     */
    public function update(
        UpdateAttendanceRequest $request,
        Attendance $attendance
    ) {
        $attendance->employee_id = $request->employee_id;
        $attendance->attendance_date = $request->attendance_date;
        $attendance->time_in = $request->time_in;
        $attendance->time_out = $request->time_out;
        $attendance->status = $request->status;
        $attendance->remarks = $request->remarks;

        $attendance->save();

        return redirect()
            ->route('attendances.index')
            ->with('success', 'Attendance updated successfully.');
    }

    /**
     * Delete attendance.
     */
    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()
            ->route('attendances.index')
            ->with('success', 'Attendance deleted successfully.');
    }

    /**
     * Employee's attendance page.
     */
    public function myAttendance()
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            abort(403, 'No employee profile is linked to this account.');
        }

        $today = now()->toDateString();

        $todayAttendance = $employee->attendances()
            ->whereDate('attendance_date', $today)
            ->first();

        $todaySchedule = EmployeeSchedule::where('employee_id', $employee->id)
            ->whereDate('schedule_date', $today)
            ->first();

        $attendances = $employee->attendances()
            ->latest('attendance_date')
            ->latest('id')
            ->paginate(10);

        return view('employee.attendance', compact(
            'employee',
            'todayAttendance',
            'todaySchedule',
            'attendances'
        ));
    }

    /**
     * Employee check-in.
     */
    public function checkIn()
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            abort(403, 'No employee profile is linked to this account.');
        }

        $today = now()->toDateString();
        $now = now();

        /*
        |--------------------------------------------------------------------------
        | Get today's schedule
        |--------------------------------------------------------------------------
        */

        $schedule = EmployeeSchedule::where('employee_id', $employee->id)
            ->whereDate('schedule_date', $today)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | No schedule
        |--------------------------------------------------------------------------
        */

        if (!$schedule) {
            return redirect()
                ->route('my-attendance')
                ->with(
                    'error',
                    'You do not have a schedule for today. Please contact HR.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Off day
        |--------------------------------------------------------------------------
        */

        if ($schedule->status === 'Off') {
            return redirect()
                ->route('my-attendance')
                ->with(
                    'error',
                    'You are scheduled OFF today. Check-in is not allowed.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Leave
        |--------------------------------------------------------------------------
        */

        if ($schedule->status === 'Leave') {
            return redirect()
                ->route('my-attendance')
                ->with(
                    'error',
                    'You are scheduled on leave today. Check-in is not allowed.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate check-in
        |--------------------------------------------------------------------------
        */

        $attendance = $employee->attendances()
            ->whereDate('attendance_date', $today)
            ->first();

        if ($attendance) {
            return redirect()
                ->route('my-attendance')
                ->with(
                    'error',
                    'You have already checked in today.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Make sure working schedule has a start time
        |--------------------------------------------------------------------------
        */

        if (!$schedule->start_time) {
            return redirect()
                ->route('my-attendance')
                ->with(
                    'error',
                    'Your work schedule does not have a start time. Please contact HR.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Determine Present / Late
        |--------------------------------------------------------------------------
        */

        $scheduledStart = now()->copy()
            ->setTimeFromTimeString(
                $schedule->start_time->format('H:i:s')
            );

        $status = $now->greaterThan($scheduledStart)
            ? 'Late'
            : 'Present';

        /*
        |--------------------------------------------------------------------------
        | Create attendance
        |--------------------------------------------------------------------------
        */

        $employee->attendances()->create([
            'attendance_date' => $today,
            'time_in' => $now,
            'status' => $status,
        ]);

        return redirect()
            ->route('my-attendance')
            ->with(
                'success',
                $status === 'Late'
                    ? 'You have successfully checked in. You are marked Late.'
                    : 'You have successfully checked in.'
            );
    }

    /**
     * Employee check-out.
     */
    public function checkOut()
    {
        $employee = auth()->user()->employee;

        if (!$employee) {
            abort(403, 'No employee profile is linked to this account.');
        }

        $today = now()->toDateString();

        $attendance = $employee->attendances()
            ->whereDate('attendance_date', $today)
            ->first();

        if (!$attendance) {
            return redirect()
                ->route('my-attendance')
                ->with(
                    'error',
                    'You have not checked in today.'
                );
        }

        if ($attendance->time_out) {
            return redirect()
                ->route('my-attendance')
                ->with(
                    'error',
                    'You have already checked out today.'
                );
        }

        $attendance->update([
            'time_out' => now(),
        ]);

        return redirect()
            ->route('my-attendance')
            ->with(
                'success',
                'You have successfully checked out.'
            );
    }

    /**
 * Start employee break.
 */
public function startBreak()
{
    $employee = auth()->user()->employee;

    if (!$employee) {
        abort(403, 'No employee profile is linked to this account.');
    }

    $today = now()->toDateString();

    $attendance = $employee->attendances()
        ->whereDate('attendance_date', $today)
        ->first();

    if (!$attendance) {
        return redirect()
            ->route('my-attendance')
            ->with('error', 'You must check in before starting a break.');
    }

    if (!$attendance->time_in) {
        return redirect()
            ->route('my-attendance')
            ->with('error', 'You must check in before starting a break.');
    }

    if ($attendance->time_out) {
        return redirect()
            ->route('my-attendance')
            ->with('error', 'You have already checked out.');
    }

    if ($attendance->break_start && !$attendance->break_end) {
        return redirect()
            ->route('my-attendance')
            ->with('error', 'You are already on break.');
    }

    if ($attendance->break_end) {
        return redirect()
            ->route('my-attendance')
            ->with('error', 'You have already completed your break today.');
    }

    $schedule = EmployeeSchedule::where('employee_id', $employee->id)
        ->whereDate('schedule_date', $today)
        ->first();

    if (!$schedule) {
        return redirect()
            ->route('my-attendance')
            ->with('error', 'You do not have a schedule for today.');
    }

    if ($schedule->status !== 'Working') {
        return redirect()
            ->route('my-attendance')
            ->with('error', 'You are not scheduled to work today.');
    }

    $attendance->update([
        'break_start' => now(),
    ]);

    return redirect()
        ->route('my-attendance')
        ->with('success', 'Your break has started.');
}


/**
 * Resume work after break.
 */
public function endBreak()
{
    $employee = auth()->user()->employee;

    if (!$employee) {
        abort(403, 'No employee profile is linked to this account.');
    }

    $today = now()->toDateString();

    $attendance = $employee->attendances()
        ->whereDate('attendance_date', $today)
        ->first();

    if (!$attendance) {
        return redirect()
            ->route('my-attendance')
            ->with('error', 'No attendance record found for today.');
    }

    if (!$attendance->break_start) {
        return redirect()
            ->route('my-attendance')
            ->with('error', 'You have not started your break.');
    }

    if ($attendance->break_end) {
        return redirect()
            ->route('my-attendance')
            ->with('error', 'You have already ended your break.');
    }

    if ($attendance->time_out) {
        return redirect()
            ->route('my-attendance')
            ->with('error', 'You have already checked out.');
    }

    $breakStart = $attendance->break_start;

    $breakEnd = now();

    $breakMinutes = $breakStart->diffInMinutes($breakEnd);

    $attendance->update([
        'break_end' => $breakEnd,
        'break_minutes' => $breakMinutes,
    ]);

    return redirect()
        ->route('my-attendance')
        ->with(
            'success',
            "Break ended. Total break time: {$breakMinutes} minutes."
        );
}


}