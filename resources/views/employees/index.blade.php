@extends('layouts.crm')

@section('title', 'Employee Management')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold">
            Employee Management
        </h2>

        <a href="{{ route('employees.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
            + Add Employee
        </a>

    </div>

    <table class="w-full">

        <thead>

            <tr class="border-b">

                <th class="text-left py-3">Employee No.</th>
                <th class="text-left py-3">Name</th>
                <th class="text-left py-3">Branch</th>
                <th class="text-left py-3">Department</th>
                <th class="text-left py-3">Status</th>
                <th class="text-left py-3">Actions</th>

            </tr>

        </thead>

        <tbody>

        @forelse($employees as $employee)

            <tr class="border-b">

                <td class="py-3">{{ $employee->employee_no }}</td>

                <td>
                    {{ $employee->first_name }} {{ $employee->last_name }}
                </td>

                <td>
                    {{ $employee->branch->name }}
                </td>

                <td>
                    {{ $employee->department }}
                </td>

                <td>

                    @if($employee->is_active)

                        <span class="text-green-600 font-semibold">
                            Active
                        </span>

                    @else

                        <span class="text-red-600 font-semibold">
                            Inactive
                        </span>

                    @endif

                </td>

                <td>

    <td class="space-x-2">

    <a href="{{ route('employees.edit', $employee) }}"
       class="inline-flex items-center px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
        Edit
    </a>

    <form action="{{ route('employees.destroy', $employee) }}"
          method="POST"
          class="inline"
          onsubmit="return confirm('Are you sure you want to delete this employee?');">

        @csrf
        @method('DELETE')

        <button
            type="submit"
            class="inline-flex items-center px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">

            Delete

        </button>

    </form>

</td>

</td>

            </tr>

        @empty

            <tr>

                <td colspan="5" class="text-center py-10 text-gray-500">
                    No employees found.
                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

@endsection