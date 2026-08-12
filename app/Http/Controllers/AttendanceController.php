<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
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
     * Show the form for creating a new resource.
     */
    public function create()
{
    $employees = Employee::where('is_active', true)
        ->orderBy('first_name')
        ->get();

    return view('attendances.create', compact('employees'));
}

    /**
     * Store a newly created resource in storage.
     */ 
    public function store(StoreAttendanceRequest $request)
{
    Attendance::create($request->validated());

    return redirect()
        ->route('attendances.index')
        ->with('success', 'Attendance created successfully.');
}

    /**
     * Display the specified resource.
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
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
     * Update the specified resource in storage.
     */
    public function update(UpdateAttendanceRequest $request, Attendance $attendance)
{
    logger()->info($request->validated());

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
     * Remove the specified resource from storage.
     */
    public function destroy(Attendance $attendance)
{
    $attendance->delete();

    return redirect()
        ->route('attendances.index')
        ->with('success', 'Attendance deleted successfully.');
}

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

    $attendances = $employee->attendances()
        ->latest('attendance_date')
        ->latest('id')
        ->paginate(10);

    return view('employee.attendance', compact(
        'employee',
        'todayAttendance',
        'attendances'
    ));
}


public function checkIn()
{
    $employee = auth()->user()->employee;

    if (!$employee) {
        abort(403, 'No employee profile is linked to this account.');
    }

    $today = now()->toDateString();

    // Prevent duplicate check-in
    $attendance = $employee->attendances()
        ->whereDate('attendance_date', $today)
        ->first();

    if ($attendance) {
        return redirect()
            ->route('my-attendance')
            ->with('error', 'You have already checked in today.');
    }

    // Determine status based on 8:00 AM
    $status = now()->format('H:i') > '08:00'
        ? 'Late'
        : 'Present';

    $employee->attendances()->create([
        'attendance_date' => $today,
        'time_in' => now(),
        'status' => $status,
    ]);

    return redirect()
        ->route('my-attendance')
        ->with('success', 'You have successfully checked in.');
}

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
            ->with('error', 'You have not checked in today.');
    }

    if ($attendance->time_out) {
        return redirect()
            ->route('my-attendance')
            ->with('error', 'You have already checked out today.');
    }

    $attendance->update([
        'time_out' => now(),
    ]);

    return redirect()
        ->route('my-attendance')
        ->with('success', 'You have successfully checked out.');
}



}



