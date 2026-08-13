@extends('layouts.crm')

@section('title', '')

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
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-sm font-bold uppercase shrink-0">
                    {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                </div>

                <div>
                    <p class="text-xs uppercase tracking-wide text-gray-400">Employee</p>
                    <h2 class="text-lg font-semibold text-gray-800">
                        {{ $employee->first_name }} {{ $employee->last_name }}
                    </h2>
                    <p class="text-sm text-gray-500">
                        {{ $employee->employee_no }} &middot; {{ $employee->position }}
                    </p>
                </div>
            </div>

            <div class="sm:text-right">
                <p class="text-xs text-gray-400 uppercase tracking-wide">Department</p>
                <p class="font-medium text-gray-700 mt-1">{{ $employee->department }}</p>
            </div>

        </div>

    </div>

    {{-- Attendance Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-wide text-gray-400">Present</span>
                <span class="bg-emerald-50 text-emerald-600 rounded-lg p-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-bold text-gray-800 mt-4">{{ $presentCount }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-wide text-gray-400">Late</span>
                <span class="bg-amber-50 text-amber-500 rounded-lg p-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-bold text-gray-800 mt-4">{{ $lateCount }}</p>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-wide text-gray-400">Absent</span>
                <span class="bg-red-50 text-red-500 rounded-lg p-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-bold text-gray-800 mt-4">{{ $absentCount }}</p>
        </div>

    </div>

    {{-- Leave Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        <div class="bg-amber-50 rounded-2xl p-6">
            <p class="text-xs uppercase tracking-wide text-amber-600">Pending Leaves</p>
            <p class="text-3xl font-bold text-amber-700 mt-2">{{ $pendingLeaves }}</p>
        </div>

        <div class="bg-emerald-50 rounded-2xl p-6">
            <p class="text-xs uppercase tracking-wide text-emerald-600">Approved Leaves</p>
            <p class="text-3xl font-bold text-emerald-700 mt-2">{{ $approvedLeaves }}</p>
        </div>

        <div class="bg-red-50 rounded-2xl p-6">
            <p class="text-xs uppercase tracking-wide text-red-500">Rejected Leaves</p>
            <p class="text-3xl font-bold text-red-600 mt-2">{{ $rejectedLeaves }}</p>
        </div>

    </div>

    </div>

    {{-- Recent Attendance --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">

        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-800">
                Recent Attendance
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 text-gray-400 uppercase text-xs">
                    <tr>
                        <th class="text-left px-6 py-3">Date</th>
                        <th class="text-left px-6 py-3">Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($recentAttendance as $attendance)

                        <tr class="border-b border-gray-50 hover:bg-gray-50">

                            <td class="px-6 py-4 text-gray-600">
                                {{ $attendance->attendance_date?->format('M d, Y') }}
                            </td>

                            <td class="px-6 py-4">
                                @php
                                    $attStyles = [
                                        'Present' => 'bg-emerald-50 text-emerald-600',
                                        'Late' => 'bg-amber-50 text-amber-600',
                                        'Absent' => 'bg-red-50 text-red-500',
                                        'Leave' => 'bg-indigo-50 text-indigo-500',
                                    ];
                                    $attStyle = $attStyles[$attendance->status] ?? 'bg-gray-100 text-gray-600';
                                @endphp

                                <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $attStyle }}">
                                    {{ $attendance->status }}
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="2" class="text-center py-12 text-gray-400">
                                No attendance records found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- Recent Leaves --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">

        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-800">
                Recent Leave Requests
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 text-gray-400 uppercase text-xs">
                    <tr>
                        <th class="text-left px-6 py-3">Leave Type</th>
                        <th class="text-left px-6 py-3">Start</th>
                        <th class="text-left px-6 py-3">End</th>
                        <th class="text-left px-6 py-3">Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($recentLeaves as $leave)

                        <tr class="border-b border-gray-50 hover:bg-gray-50">

                            <td class="px-6 py-4 text-gray-600">{{ $leave->leave_type }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $leave->start_date?->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-gray-600">{{ $leave->end_date?->format('M d, Y') }}</td>

                            <td class="px-6 py-4">
                                @php
                                    $leaveStyles = [
                                        'Pending' => 'bg-amber-50 text-amber-600',
                                        'Approved' => 'bg-emerald-50 text-emerald-600',
                                        'Rejected' => 'bg-red-50 text-red-500',
                                    ];
                                    $leaveStyle = $leaveStyles[$leave->status] ?? 'bg-gray-100 text-gray-600';
                                @endphp

                                <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $leaveStyle }}">
                                    {{ $leave->status }}
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="text-center py-12 text-gray-400">
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