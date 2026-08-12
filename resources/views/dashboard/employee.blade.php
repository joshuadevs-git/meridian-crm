@extends('layouts.crm')

@section('title', 'Employee Dashboard')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            Welcome, {{ $employee->first_name }}!
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Here's an overview of your employee records.
        </p>
    </div>


    {{-- Employee Information --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">

        <div class="flex items-center justify-between">

            <div>
                <p class="text-xs uppercase tracking-wide text-gray-400">
                    Employee
                </p>

                <h2 class="text-lg font-semibold text-gray-800 mt-1">
                    {{ $employee->first_name }}
                    {{ $employee->last_name }}
                </h2>

                <p class="text-sm text-gray-500">
                    {{ $employee->employee_no }}
                    ·
                    {{ $employee->position }}
                </p>
            </div>

            <div class="text-right">
                <p class="text-xs text-gray-400">
                    Department
                </p>

                <p class="font-medium text-gray-700">
                    {{ $employee->department }}
                </p>
            </div>

        </div>

    </div>


    {{-- Attendance Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-sm text-gray-500">
                Present
            </p>

            <p class="text-3xl font-bold text-green-600 mt-2">
                {{ $presentCount }}
            </p>
        </div>


        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-sm text-gray-500">
                Late
            </p>

            <p class="text-3xl font-bold text-yellow-600 mt-2">
                {{ $lateCount }}
            </p>
        </div>


        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-sm text-gray-500">
                Absent
            </p>

            <p class="text-3xl font-bold text-red-600 mt-2">
                {{ $absentCount }}
            </p>
        </div>

    </div>


    {{-- Leave Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-sm text-gray-500">
                Pending Leaves
            </p>

            <p class="text-3xl font-bold text-yellow-600 mt-2">
                {{ $pendingLeaves }}
            </p>
        </div>


        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-sm text-gray-500">
                Approved Leaves
            </p>

            <p class="text-3xl font-bold text-green-600 mt-2">
                {{ $approvedLeaves }}
            </p>
        </div>


        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-sm text-gray-500">
                Rejected Leaves
            </p>

            <p class="text-3xl font-bold text-red-600 mt-2">
                {{ $rejectedLeaves }}
            </p>
        </div>

    </div>


    {{-- Latest Payroll --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm">

        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">
                Latest Payroll
            </h2>
        </div>

        @if($latestPayroll)

            <div class="grid grid-cols-2 md:grid-cols-4 gap-5 p-5">

                <div>
                    <p class="text-xs text-gray-400">
                        Payroll Date
                    </p>

                    <p class="font-medium text-gray-700 mt-1">
                        {{ $latestPayroll->payroll_date?->format('M d, Y') }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-400">
                        Basic Salary
                    </p>

                    <p class="font-medium text-gray-700 mt-1">
                        ₱{{ number_format($latestPayroll->basic_salary, 2) }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-400">
                        Deduction
                    </p>

                    <p class="font-medium text-red-600 mt-1">
                        ₱{{ number_format($latestPayroll->deduction, 2) }}
                    </p>
                </div>

                <div>
                    <p class="text-xs text-gray-400">
                        Net Salary
                    </p>

                    <p class="text-xl font-bold text-gray-800 mt-1">
                        ₱{{ number_format($latestPayroll->net_salary, 2) }}
                    </p>
                </div>

            </div>

        @else

            <div class="p-5 text-sm text-gray-400">
                No payroll records found.
            </div>

        @endif

    </div>


    {{-- Recent Attendance --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">
                Recent Attendance
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-5 py-3 text-left">
                            Date
                        </th>

                        <th class="px-5 py-3 text-left">
                            Status
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($recentAttendance as $attendance)

                        <tr>

                            <td class="px-5 py-3">
                                {{ $attendance->attendance_date?->format('M d, Y') }}
                            </td>

                            <td class="px-5 py-3">
                                {{ $attendance->status }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="2"
                                class="px-5 py-8 text-center text-gray-400">
                                No attendance records found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- Recent Leaves --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">
                Recent Leave Requests
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-5 py-3 text-left">
                            Leave Type
                        </th>

                        <th class="px-5 py-3 text-left">
                            Start
                        </th>

                        <th class="px-5 py-3 text-left">
                            End
                        </th>

                        <th class="px-5 py-3 text-left">
                            Status
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($recentLeaves as $leave)

                        <tr>

                            <td class="px-5 py-3">
                                {{ $leave->leave_type }}
                            </td>

                            <td class="px-5 py-3">
                                {{ $leave->start_date?->format('M d, Y') }}
                            </td>

                            <td class="px-5 py-3">
                                {{ $leave->end_date?->format('M d, Y') }}
                            </td>

                            <td class="px-5 py-3">
                                {{ $leave->status }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4"
                                class="px-5 py-8 text-center text-gray-400">
                                No leave records found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection