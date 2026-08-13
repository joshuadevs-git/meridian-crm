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

    /*
    |--------------------------------------------------------------------------
    | Check if email already exists in users table
    |--------------------------------------------------------------------------
    */

    if (User::where('email', $data['email'])->exists()) {
        return back()
            ->withInput()
            ->withErrors([
                'email' => 'A user account with this email already exists.'
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Find Employee Role
    |--------------------------------------------------------------------------
    */

    $employeeRole = Role::where('name', 'Employee')->first();

    if (!$employeeRole) {
        return back()
            ->withInput()
            ->withErrors([
                'email' => 'Employee role does not exist.'
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Temporary Password
    |--------------------------------------------------------------------------
    */

    $temporaryPassword = Str::random(16);

    /*
    |--------------------------------------------------------------------------
    | Create User + Employee Together
    |--------------------------------------------------------------------------
    */

    try {

        DB::beginTransaction();

        /*
        | Create login account
        */

        $user = User::create([
            'name' => $data['first_name'] . ' ' . $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($temporaryPassword),
            'role_id' => $employeeRole->id,
            'must_change_password' => true,
        ]);

        /*
        | Connect Employee to User
        */

        $data['user_id'] = $user->id;

        /*
        | Create Employee
        */

        $employee = Employee::create($data);

        DB::commit();

    } catch (\Throwable $e) {

        DB::rollBack();

        return back()
            ->withInput()
            ->withErrors([
                'email' => 'Employee could not be created. ' . $e->getMessage()
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Redirect to Employee List
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('employees.index')
        ->with('success', 'Employee and login account created successfully.')
        ->with('temporary_password', $temporaryPassword)
        ->with(
            'created_employee_name',
            $employee->first_name . ' ' . $employee->last_name
        )
        ->with(
            'created_employee_email',
            $employee->email
        );
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


public function rules(): array
{
    return [
        'employee_no' => [
            'required',
            'string',
            'max:50',
            'unique:employees,employee_no',
        ],

        'first_name' => [
            'required',
            'string',
            'max:255',
        ],

        'last_name' => [
            'required',
            'string',
            'max:255',
        ],

        'email' => [
            'required',
            'email',
            'max:255',
            'unique:employees,email',
            'unique:users,email',
        ],

        'phone' => [
            'nullable',
            'string',
            'max:20',
        ],

        'branch_id' => [
            'required',
            'exists:branches,id',
        ],

        'position' => [
            'required',
            'string',
            'max:255',
        ],

        'department' => [
            'required',
            'string',
            'max:255',
        ],

        'hire_date' => [
            'required',
            'date',
        ],

        'salary' => [
            'nullable',
            'numeric',
            'min:0',
        ],

        'is_active' => [
            'boolean',
        ],
    ];
}

}
