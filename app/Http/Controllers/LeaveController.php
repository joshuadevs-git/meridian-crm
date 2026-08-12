<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\Employee;
use App\Http\Requests\StoreLeaveRequest;
use Illuminate\Http\Request;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::with('employee')
            ->latest()
            ->paginate(10);

        $totalLeaves = Leave::count();
        $pendingLeaves = Leave::where('status', 'Pending')->count();
        $approvedLeaves = Leave::where('status', 'Approved')->count();
        $rejectedLeaves = Leave::where('status', 'Rejected')->count();

        return view('leaves.index', compact(
            'leaves',
            'totalLeaves',
            'pendingLeaves',
            'approvedLeaves',
            'rejectedLeaves'
        ));
    }

    public function create()
    {
        $employees = Employee::orderBy('first_name')->get();

        return view('leaves.create', [
            'employees' => $employees,
            'leave' => new Leave(),
        ]);
    }

    public function store(StoreLeaveRequest $request)
    {
        Leave::create($request->validated());

        return redirect()
            ->route('leaves.index')
            ->with('success', 'Leave request created successfully.');
    }

    public function edit($leaf)
{
    $leave = Leave::findOrFail($leaf);

    $employees = Employee::orderBy('first_name')->get();

    return view('leaves.edit', compact(
        'leave',
        'employees'
    ));
}

    public function update(StoreLeaveRequest $request, $leaf)
{
    $leave = Leave::findOrFail($leaf);

    $leave->update($request->validated());

    return redirect()
        ->route('leaves.index')
        ->with('success', 'Leave updated successfully.');
}

public function destroy($leaf)
{
    $leave = Leave::findOrFail($leaf);

    $leave->delete();

    return redirect()
        ->route('leaves.index')
        ->with('success', 'Leave deleted successfully.');
}




public function myLeaves()
{
    $employee = auth()->user()->employee;

    if (!$employee) {
        abort(403, 'No employee profile is linked to this account.');
    }

    $leaves = $employee->leaves()
        ->latest('start_date')
        ->latest('id')
        ->paginate(10);

    return view('employee.leaves', compact('employee', 'leaves'));
}

public function createMyLeave()
{
    $employee = auth()->user()->employee;

    if (!$employee) {
        abort(403, 'No employee profile is linked to this account.');
    }

    return view('employee.leave-create', compact('employee'));
}

public function storeMyLeave(Request $request)
{
    $employee = auth()->user()->employee;

    if (!$employee) {
        abort(403, 'No employee profile is linked to this account.');
    }

    $validated = $request->validate([
        'start_date' => ['required', 'date'],
        'end_date' => ['required', 'date', 'after_or_equal:start_date'],
        'leave_type' => ['required', 'string', 'max:255'],
        'reason' => ['nullable', 'string', 'max:1000'],
    ]);

    $employee->leaves()->create([
        'start_date' => $validated['start_date'],
        'end_date' => $validated['end_date'],
        'leave_type' => $validated['leave_type'],
        'reason' => $validated['reason'] ?? null,
        'status' => 'Pending',
    ]);

    return redirect()
        ->route('my-leaves')
        ->with('success', 'Leave request submitted successfully.');
}


}