@extends('layouts.crm')

@section('title', 'Attendance Report')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold">
            Attendance Report
        </h2>

    </div>


    <div class="flex justify-between items-center mb-6">

    <h2 class="text-2xl font-bold">

        Attendance Report

    </h2>

    <div class="flex gap-3">

        <a
            href="{{ route('reports.attendance.excel', request()->query()) }}"
            class="bg-green-600 text-white px-4 py-2 rounded-lg">

            Export Excel

        </a>

        <a
            href="{{ route('reports.attendance.csv', request()->query()) }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg">

            Export CSV

        </a>

        <a
            href="{{ route('reports.attendance.pdf', request()->query()) }}"
            class="bg-red-600 text-white px-4 py-2 rounded-lg">

            Export PDF

        </a>

    </div>

</div>

    <form method="GET"
          class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

        <input
            type="month"
            name="month"
            value="{{ $month }}"
            class="border rounded-lg px-3 py-2">

        <select
            name="branch"
            class="border rounded-lg px-3 py-2">

            <option value="">All Branches</option>

            @foreach($branches as $b)

                <option
                    value="{{ $b->id }}"
                    @selected($branch==$b->id)>

                    {{ $b->name }}

                </option>

            @endforeach

        </select>

        <input
            type="text"
            name="search"
            value="{{ $search }}"
            placeholder="Search employee..."
            class="border rounded-lg px-3 py-2">

        <button
            class="bg-blue-600 text-white rounded-lg">

            Search

        </button>

    </form>

    <table class="min-w-full">

        <thead class="border-b bg-gray-100">

        <tr>

            <th class="py-3 text-left">Employee</th>
            <th class="text-left">Branch</th>
            <th class="text-center">Present</th>
            <th class="text-center">Late</th>
            <th class="text-center">Absent</th>
            <th class="text-center">Leave</th>
            <th class="text-center">Total</th>

        </tr>

        </thead>

        <tbody>

        @forelse($attendanceReport as $row)

            <tr class="border-b">

                <td>

                    {{ $row->employee->employee_no }}

                    -

                    {{ $row->employee->first_name }}

                    {{ $row->employee->last_name }}

                </td>

                <td>

                    {{ $row->employee->branch->name }}

                </td>

                <td class="text-center">

                    {{ $row->present }}

                </td>

                <td class="text-center">

                    {{ $row->late }}

                </td>

                <td class="text-center">

                    {{ $row->absent }}

                </td>

                <td class="text-center">

                    {{ $row->leave_count }}

                </td>

                <td class="text-center font-semibold">

                    {{ $row->present+$row->late+$row->absent+$row->leave_count }}

                </td>

            </tr>

        @empty

            <tr>

                <td colspan="7"
                    class="text-center py-8">

                    No attendance records found.

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

    <div class="mt-6">

        {{ $attendanceReport->links() }}

    </div>

</div>

@endsection