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

        return view('leaves.create', compact('employees'));
    }

    public function store(StoreLeaveRequest $request)
    {
        Leave::create($request->validated());

        return redirect()
            ->route('leaves.index')
            ->with('success', 'Leave request created successfully.');
    }

    public function edit(Leave $leaf)
    {
        $employees = Employee::orderBy('first_name')->get();

        return view('leaves.edit', [
            'leave' => $leaf,
            'employees' => $employees,
        ]);
    }

    public function update(Request $request, Leave $leaf)
    {
        $request->validate([
            'employee_id' => 'required',
            'leave_type' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'status' => 'required',
            'reason' => 'nullable',
        ]);

        $leaf->update($request->all());

        return redirect()
            ->route('leaves.index')
            ->with('success', 'Leave updated successfully.');
    }

    public function destroy(Leave $leaf)
    {
        $leaf->delete();

        return redirect()
            ->route('leaves.index')
            ->with('success', 'Leave deleted successfully.');
    }
}