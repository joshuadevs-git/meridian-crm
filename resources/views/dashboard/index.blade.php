@extends('layouts.crm')

@section('title', 'Dashboard')

@section('content')

<h1 class="text-3xl font-bold mb-8">
    Dashboard
</h1>

{{-- Dashboard Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-blue-600">
        <h2 class="text-gray-500 text-sm uppercase">Total Employees</h2>

        <p class="text-4xl font-bold mt-2">
            {{ $totalEmployees }}
        </p>
    </div>

    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-600">
        <h2 class="text-gray-500 text-sm uppercase">Active Employees</h2>

        <p class="text-4xl font-bold mt-2">
            {{ $activeEmployees }}
        </p>
    </div>

    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-red-600">
        <h2 class="text-gray-500 text-sm uppercase">Inactive Employees</h2>

        <p class="text-4xl font-bold mt-2">
            {{ $inactiveEmployees }}
        </p>
    </div>

    <div class="bg-white rounded-xl shadow p-6 border-l-4 border-purple-600">
        <h2 class="text-gray-500 text-sm uppercase">Total Branches</h2>

        <p class="text-4xl font-bold mt-2">
            {{ $totalBranches }}
        </p>
    </div>

</div>

<div class="mt-10">
    <h2 class="text-2xl font-bold mb-6">
        Attendance Overview
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-indigo-600">
            <h3 class="text-gray-500 text-sm uppercase">
                Total Attendance
            </h3>

            <p class="text-4xl font-bold mt-2">
                {{ $totalAttendance }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-green-600">
            <h3 class="text-gray-500 text-sm uppercase">
                Present Today
            </h3>

            <p class="text-4xl font-bold mt-2">
                {{ $presentToday }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-yellow-500">
            <h3 class="text-gray-500 text-sm uppercase">
                Late Today
            </h3>

            <p class="text-4xl font-bold mt-2">
                {{ $lateToday }}
            </p>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border-l-4 border-red-600">
            <h3 class="text-gray-500 text-sm uppercase">
                Absent Today
            </h3>

            <p class="text-4xl font-bold mt-2">
                {{ $absentToday }}
            </p>
        </div>

    </div>
</div>

{{-- Recent Employees --}}
<div class="mt-10 bg-white rounded-xl shadow">

    <div class="p-6 border-b">
        <h2 class="text-xl font-bold">
            Recent Employees
        </h2>
    </div>

    <div class="overflow-x-auto">

        <table class="min-w-full">

            <thead class="bg-gray-50">

                <tr>

                    <th class="text-left px-6 py-3">Employee No.</th>
                    <th class="text-left px-6 py-3">Name</th>
                    <th class="text-left px-6 py-3">Branch</th>
                    <th class="text-left px-6 py-3">Position</th>
                    <th class="text-left px-6 py-3">Status</th>

                </tr>

            </thead>

            <tbody>

            @forelse($recentEmployees as $employee)

                <tr class="border-b hover:bg-gray-50">

                    <td class="px-6 py-4">
                        {{ $employee->employee_no }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $employee->first_name }} {{ $employee->last_name }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $employee->branch->name }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $employee->position }}
                    </td>

                    <td class="px-6 py-4">

                        @if($employee->is_active)

                            <span class="px-2 py-1 rounded bg-green-100 text-green-700 text-sm">
                                Active
                            </span>

                        @else

                            <span class="px-2 py-1 rounded bg-red-100 text-red-700 text-sm">
                                Inactive
                            </span>

                        @endif

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="5" class="text-center py-8 text-gray-500">
                        No employees found.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

{{-- Employees Per Branch --}}
<div class="mt-10 bg-white rounded-xl shadow">

    <div class="p-6 border-b">
        <h2 class="text-xl font-bold">
            Employees Per Branch
        </h2>
    </div>

    <div class="overflow-x-auto">

        <table class="min-w-full">

            <thead class="bg-gray-50">

                <tr>

                    <th class="text-left px-6 py-3">Branch</th>
                    <th class="text-center px-6 py-3">Employees</th>
                    <th class="text-center px-6 py-3">Status</th>

                </tr>

            </thead>

            <tbody>

            @forelse($branches as $branch)

                <tr class="border-b hover:bg-gray-50">

                    <td class="px-6 py-4 font-medium">
                        {{ $branch->name }}
                    </td>

                    <td class="px-6 py-4 text-center">
                        {{ $branch->employees_count }}
                    </td>

                    <td class="px-6 py-4 text-center">

                        @if($branch->is_active)

                            <span class="px-2 py-1 rounded bg-green-100 text-green-700 text-sm">
                                Active
                            </span>

                        @else

                            <span class="px-2 py-1 rounded bg-red-100 text-red-700 text-sm">
                                Inactive
                            </span>

                        @endif

                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="3" class="text-center py-8 text-gray-500">
                        No branches found.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

{{-- Employees Per Branch Chart --}}
<div class="mt-10 bg-white rounded-xl shadow">

    <div class="p-6 border-b">
        <h2 class="text-xl font-bold">
            Employees Per Branch Chart
        </h2>
    </div>

    <div class="p-6">

        <canvas
            id="branchChart"
            data-labels='@json($branchLabels)'
            data-values='@json($branchEmployeeCounts)'
            height="100">
        </canvas>

    </div>

</div>



<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-10">

    <div class="bg-white rounded-xl shadow p-6">

        <h2 class="text-xl font-bold mb-4">
            Attendance Status
        </h2>

        <canvas
            id="attendanceStatusChart"
            data-labels='@json($statusLabels)'
            data-values='@json($statusCounts)'>
        </canvas>

    </div>

    <div class="bg-white rounded-xl shadow p-6">

        <h2 class="text-xl font-bold mb-4">
            Last 7 Days Attendance
        </h2>

        <canvas
            id="attendanceTrendChart"
            data-labels='@json($dailyLabels)'
            data-values='@json($dailyCounts)'>
        </canvas>

    </div>

</div>

@endsection