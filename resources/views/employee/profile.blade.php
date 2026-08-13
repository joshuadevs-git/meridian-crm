@extends('layouts.crm')

@section('title', '')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            My Profile
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            View your employee information.
        </p>
    </div>

    {{-- Employee Information --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

        <div class="px-6 py-5 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-800">
                Employee Information
            </h2>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Employee Number --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Employee Number
                </p>

                <p class="mt-1 text-sm font-semibold text-gray-800">
                    {{ $employee->employee_no }}
                </p>
            </div>

            {{-- Full Name --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Full Name
                </p>

                <p class="mt-1 text-sm font-semibold text-gray-800">
                    {{ $employee->first_name }}
                    {{ $employee->last_name }}
                </p>
            </div>

            {{-- Email --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Email
                </p>

                <p class="mt-1 text-sm text-gray-700">
                    {{ $employee->email }}
                </p>
            </div>

            {{-- Phone --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Phone
                </p>

                <p class="mt-1 text-sm text-gray-700">
                    {{ $employee->phone ?? 'Not provided' }}
                </p>
            </div>

            {{-- Branch --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Branch
                </p>

                <p class="mt-1 text-sm text-gray-700">
                    {{ $employee->branch?->name ?? 'Not assigned' }}
                </p>
            </div>

            {{-- Position --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Position
                </p>

                <p class="mt-1 text-sm text-gray-700">
                    {{ $employee->position }}
                </p>
            </div>

            {{-- Department --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Department
                </p>

                <p class="mt-1 text-sm text-gray-700">
                    {{ $employee->department }}
                </p>
            </div>

            {{-- Hire Date --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Hire Date
                </p>

                <p class="mt-1 text-sm text-gray-700">
                    {{ $employee->hire_date?->format('M d, Y') }}
                </p>
            </div>

            {{-- Status --}}
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-gray-400">
                    Employment Status
                </p>

                <span class="inline-flex mt-2 px-3 py-1 rounded-full text-xs font-medium
                    {{ $employee->is_active
                        ? 'bg-green-100 text-green-700'
                        : 'bg-red-100 text-red-700' }}">
                    {{ $employee->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>

        </div>

    </div>

</div>

@endsection