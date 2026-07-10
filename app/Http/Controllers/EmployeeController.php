<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\Branch;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $employees = Employee::with('branch')
        ->latest()
        ->paginate(10);

    return view('employees.index', compact('employees'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $branches = Branch::where('is_active', true)
        ->orderBy('name')
        ->get();

    return view('employees.create', compact('branches'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
{
    Employee::create($request->validated());

    return redirect()
        ->route('employees.index')
        ->with('success', 'Employee created successfully.');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
{
    $branches = Branch::where('is_active', true)
        ->orderBy('name')
        ->get();

    return view('employees.edit', compact('employee', 'branches'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
{
    $data = $request->validated();

    // Kapag hindi naka-check ang checkbox,
    // hindi ipinapasa ng browser ang is_active.
    $data['is_active'] = $request->boolean('is_active');

    $employee->update($data);

    return redirect()
        ->route('employees.index')
        ->with('success', 'Employee updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
{
    $employee->delete();

    return redirect()
        ->route('employees.index')
        ->with('success', 'Employee deleted successfully.');
}
}
