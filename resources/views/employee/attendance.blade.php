@extends('layouts.crm')

@section('title', 'My Attendance')

@section('content')

    {{-- Header --}}

<div class="space-y-6">

{{-- Today's Attendance --}}
<div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">

    <div class="flex items-center justify-between">

        <div>
            <p class="text-xs uppercase tracking-wide text-gray-400">
                Today's Attendance
            </p>

            <h2 class="text-lg font-semibold text-gray-800 mt-1">
                {{ now()->format('F d, Y') }}
            </h2>
        </div>

        <div class="flex gap-3">

            @if(!$todayAttendance)

                <form action="{{ route('my-attendance.check-in') }}" method="POST">
                    @csrf

                    <button
                        class="px-5 py-2.5 rounded-xl bg-green-600 hover:bg-green-700 text-white text-sm font-medium">
                        Check In
                    </button>
                </form>

            @elseif(!$todayAttendance->time_out)

                <form action="{{ route('my-attendance.check-out') }}" method="POST">
                    @csrf

                    <button
                        class="px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-medium">
                        Check Out
                    </button>
                </form>

            @else

                <span class="px-4 py-2 rounded-xl bg-green-100 text-green-700 text-sm">
                    Attendance Completed
                </span>

            @endif

        </div>

    </div>

    <div class="grid grid-cols-3 gap-4 mt-5">

        <div>
            <p class="text-xs text-gray-400">Time In</p>

            <p class="font-semibold text-gray-800">
                {{ $todayAttendance?->time_in
                    ? \Carbon\Carbon::parse($todayAttendance->time_in)->format('h:i A')
                    : '—' }}
            </p>
        </div>

        <div>
            <p class="text-xs text-gray-400">Time Out</p>

            <p class="font-semibold text-gray-800">
                {{ $todayAttendance?->time_out
                    ? \Carbon\Carbon::parse($todayAttendance->time_out)->format('h:i A')
                    : '—' }}
            </p>
        </div>

        <div>
            <p class="text-xs text-gray-400">Status</p>

            <p class="font-semibold text-gray-800">
                {{ $todayAttendance?->status ?? 'Not Checked In' }}
            </p>
        </div>

    </div>

</div>

    {{-- Employee Information --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <div class="flex items-center justify-between">

            <div>
                <p class="text-xs uppercase tracking-wide text-gray-400">
                    Employee
                </p>

                <h2 class="text-lg font-semibold text-gray-800 mt-1">
                    {{ $employee->first_name }} {{ $employee->last_name }}
                </h2>

                <p class="text-sm text-gray-500">
                    Employee No: {{ $employee->employee_no }}
                </p>
            </div>

            <div class="text-right">
                <p class="text-xs text-gray-400">
                    Department
                </p>

                <p class="text-sm font-medium text-gray-700">
                    {{ $employee->department }}
                </p>
            </div>

        </div>
    </div>

    {{-- Attendance Table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">
                Attendance History
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-5 py-3 text-left font-medium">
                            Date
                        </th>

                        <th class="px-5 py-3 text-left font-medium">
                            Time In
                        </th>

                        <th class="px-5 py-3 text-left font-medium">
                            Time Out
                        </th>

                        <th class="px-5 py-3 text-left font-medium">
                            Status
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($attendances as $attendance)

                        <tr class="hover:bg-gray-50">

                            <td class="px-5 py-4">
                                {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('M d, Y') }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $attendance->time_in ?? '—' }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $attendance->time_out ?? '—' }}
                            </td>

                            <td class="px-5 py-4">

                                @php
                                    $statusClass = match($attendance->status) {
                                        'Present' => 'bg-green-100 text-green-700',
                                        'Late' => 'bg-yellow-100 text-yellow-700',
                                        'Absent' => 'bg-red-100 text-red-700',
                                        'Leave' => 'bg-blue-100 text-blue-700',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp

                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                    {{ $attendance->status }}
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="px-5 py-10 text-center text-gray-400">
                                No attendance records found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($attendances->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $attendances->links() }}
            </div>
        @endif

    </div>

</div>

@endsection