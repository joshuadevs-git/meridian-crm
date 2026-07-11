<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     */

public function index()
{
    $attendances = Attendance::with('employee.branch')
        ->latest()
        ->paginate(10);

    return view('attendances.index', compact('attendances'));
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
}
