<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['role', 'employee'])
            ->latest()
            ->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name')->get();

        $employees = Employee::where('is_active', true)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('users.create', compact('roles', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'role_id' => [
                'required',
                'exists:roles,id',
            ],

            'employee_id' => [
                'nullable',
                'exists:employees,id',
            ],

            'password' => [
                'required',
                'confirmed',
                'min:8',
            ],
        ]);

        // Employee accounts must be linked to an employee
        $role = Role::findOrFail($validated['role_id']);

        if ($role->name === 'Employee' && empty($validated['employee_id'])) {
            return back()
                ->withErrors([
                    'employee_id' => 'An Employee account must be linked to an employee record.',
                ])
                ->withInput();
        }

        // Prevent one employee from having multiple user accounts
        if (!empty($validated['employee_id'])) {
            $alreadyLinked = Employee::where('id', $validated['employee_id'])
                ->whereNotNull('user_id')
                ->exists();

            if ($alreadyLinked) {
                return back()
                    ->withErrors([
                        'employee_id' => 'This employee is already linked to a user account.',
                    ])
                    ->withInput();
            }
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role_id' => $validated['role_id'],
            'password' => Hash::make($validated['password']),
        ]);

        // Link employee record to the new user
        if (!empty($validated['employee_id'])) {
            Employee::where('id', $validated['employee_id'])
                ->update([
                    'user_id' => $user->id,
                ]);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $roles = Role::orderBy('name')->get();

        $employees = Employee::where('is_active', true)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $user->load('employee');

        return view('users.edit', compact(
            'user',
            'roles',
            'employees'
        ));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'role_id' => [
                'required',
                'exists:roles,id',
            ],

            'employee_id' => [
                'nullable',
                'exists:employees,id',
            ],

            'password' => [
                'nullable',
                'confirmed',
                'min:8',
            ],
        ]);

        $role = Role::findOrFail($validated['role_id']);

        if ($role->name === 'Employee' && empty($validated['employee_id'])) {
            return back()
                ->withErrors([
                    'employee_id' => 'An Employee account must be linked to an employee record.',
                ])
                ->withInput();
        }

        // Check whether selected employee belongs to another user
        if (!empty($validated['employee_id'])) {

            $alreadyLinked = Employee::where('id', $validated['employee_id'])
                ->whereNotNull('user_id')
                ->where('user_id', '!=', $user->id)
                ->exists();

            if ($alreadyLinked) {
                return back()
                    ->withErrors([
                        'employee_id' => 'This employee is already linked to another user account.',
                    ])
                    ->withInput();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Remove old employee relationship
        |--------------------------------------------------------------------------
        */

        Employee::where('user_id', $user->id)
            ->update([
                'user_id' => null,
            ]);

        /*
        |--------------------------------------------------------------------------
        | Update User
        |--------------------------------------------------------------------------
        */

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role_id = $validated['role_id'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        /*
        |--------------------------------------------------------------------------
        | Assign new employee relationship
        |--------------------------------------------------------------------------
        */

        if (!empty($validated['employee_id'])) {
            Employee::where('id', $validated['employee_id'])
                ->update([
                    'user_id' => $user->id,
                ]);
        }

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        // Remove employee relationship before deleting user
        Employee::where('user_id', $user->id)
            ->update([
                'user_id' => null,
            ]);

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully.');
    }
}