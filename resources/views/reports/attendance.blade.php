@extends('layouts.crm')


@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-6 border-b border-gray-100">

        <div>
            <h2 class="text-xl font-bold text-gray-800">Attendance Report</h2>
            <p class="text-sm text-gray-400 mt-1">Monthly attendance summary per employee</p>
        </div>

        <div class="flex flex-wrap gap-2">

            <a
                href="{{ route('reports.attendance.excel', request()->query()) }}"
                class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                </svg>
                Export Excel
            </a>

            <a
                href="{{ route('reports.attendance.csv', request()->query()) }}"
                class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                </svg>
                Export CSV
            </a>

            <a
                href="{{ route('reports.attendance.pdf', request()->query()) }}"
                class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                </svg>
                Export PDF
            </a>

        </div>

    </div>

    {{-- Filters --}}
    <div class="p-6 border-b border-gray-100 bg-gray-50/50">

        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-3">

            <input
                type="month"
                name="month"
                value="{{ $month }}"
                class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

            <select
                name="branch"
                class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

                <option value="">All Branches</option>

                @foreach($branches as $b)
                    <option value="{{ $b->id }}" @selected($branch == $b->id)>
                        {{ $b->name }}
                    </option>
                @endforeach

            </select>

            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Search employee..."
                class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

            <button
                type="submit"
                class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition">
                Search
            </button>

        </form>

    </div>

    <div class="overflow-x-auto">

        <table class="min-w-full text-sm">

            <thead class="bg-gray-50 text-gray-400 uppercase text-xs">
                <tr>
                    <th class="text-left px-6 py-3">Employee</th>
                    <th class="text-left px-6 py-3">Branch</th>
                    <th class="text-center px-6 py-3">Present</th>
                    <th class="text-center px-6 py-3">Late</th>
                    <th class="text-center px-6 py-3">Absent</th>
                    <th class="text-center px-6 py-3">Leave</th>
                    <th class="text-center px-6 py-3">Total</th>
                </tr>
            </thead>

            <tbody>

            @forelse($attendanceReport as $row)

                <tr class="border-b border-gray-50 hover:bg-gray-50">

                    <td class="px-6 py-4 font-medium text-gray-800">
                        {{ $row->employee->employee_no }} - {{ $row->employee->first_name }} {{ $row->employee->last_name }}
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        {{ $row->employee->branch->name }}
                    </td>

                    <td class="px-6 py-4 text-center text-emerald-600 font-medium">{{ $row->present }}</td>
                    <td class="px-6 py-4 text-center text-amber-600 font-medium">{{ $row->late }}</td>
                    <td class="px-6 py-4 text-center text-red-500 font-medium">{{ $row->absent }}</td>
                    <td class="px-6 py-4 text-center text-indigo-500 font-medium">{{ $row->leave_count }}</td>

                    <td class="px-6 py-4 text-center font-semibold text-gray-800">
                        {{ $row->present + $row->late + $row->absent + $row->leave_count }}
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="7" class="text-center py-12 text-gray-400">
                        No attendance records found.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    @if(method_exists($attendanceReport, 'links'))
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $attendanceReport->links() }}
        </div>
    @endif

</div>

@endsection