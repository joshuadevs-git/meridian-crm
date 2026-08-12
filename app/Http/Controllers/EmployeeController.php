<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Branch;
use App\Models\Employee;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\EmployeesExport;
use App\Imports\EmployeesImport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;


class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
  public function index(Request $request)
{
    $search = $request->search;
    $branch = $request->branch;
    $status = $request->status;
    $sort = $request->sort ?? 'latest';

    $employees = Employee::with('branch')

        ->when($search, function ($query) use ($search) {

            $query->where(function ($q) use ($search) {

                $q->where('employee_no', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");

            });

        })

        ->when($branch, function ($query) use ($branch) {

            $query->where('branch_id', $branch);

        })

        ->when($status !== null && $status !== '', function ($query) use ($status) {

            $query->where('is_active', $status);

        });

    // Sorting
    switch ($sort) {

        case 'oldest':
            $employees->oldest();
            break;

        case 'name_asc':
            $employees->orderBy('first_name');
            break;

        case 'name_desc':
            $employees->orderByDesc('first_name');
            break;

        case 'salary_high':
            $employees->orderByDesc('salary');
            break;

        case 'salary_low':
            $employees->orderBy('salary');
            break;

        case 'hire_new':
            $employees->orderByDesc('hire_date');
            break;

        case 'hire_old':
            $employees->orderBy('hire_date');
            break;

        default:
            $employees->latest();
            break;
    }

    $employees = $employees
        ->paginate(10)
        ->withQueryString();

    $branches = Branch::orderBy('name')->get();

    return view('employees.index', compact(
        'employees',
        'branches',
        'search',
        'branch',
        'status',
        'sort'
    ));
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
    $data = $request->validated();

    // Prevent duplicate user account
    if (User::where('email', $data['email'])->exists()) {
        return back()
            ->withInput()
            ->withErrors([
                'email' => 'A user account with this email already exists.'
            ]);
    }

    // Generate temporary password
    $temporaryPassword = Str::random(10);

    // Find Employee role
    $employeeRole = Role::where('name', 'Employee')->first();

    if (!$employeeRole) {
        return back()
            ->withInput()
            ->withErrors([
                'email' => 'Employee role does not exist. Please create the Employee role first.'
            ]);
    }

    // Create login account
    $user = User::create([
        'name' => $data['first_name'] . ' ' . $data['last_name'],
        'email' => $data['email'],
        'password' => Hash::make($temporaryPassword),
        'role_id' => $employeeRole->id,
        'must_change_password' => true,
    ]);

    // Automatically connect employee to account
    $data['user_id'] = $user->id;

    $employee = Employee::create($data);

    // Store temporary password in session so Admin/HR can see it once
    session()->flash('temporary_password', $temporaryPassword);
    session()->flash('created_employee_name', $employee->first_name . ' ' . $employee->last_name);
    session()->flash('created_employee_email', $employee->email);

    return redirect()
        ->route('employees.index')
        ->with('success', 'Employee and login account created successfully.');
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

public function export()
{
    return Excel::download(
        new EmployeesExport,
        'employees.xlsx'
    );
}

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:xlsx,xls,csv',
    ]);

    Excel::import(
        new EmployeesImport,
        $request->file('file')
    );

    return redirect()
        ->route('employees.index')
        ->with('success', 'Employees imported successfully.');
}



public function myProfile()
{
    $employee = auth()->user()->employee;

    if (!$employee) {
        abort(403, 'No employee profile is linked to this account.');
    }

    $employee->load('branch');

    return view('employee.profile', compact('employee'));
}

}
