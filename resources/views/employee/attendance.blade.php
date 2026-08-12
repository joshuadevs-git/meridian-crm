@extends('layouts.crm')

@section('title', 'My Attendance')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-semibold text-gray-800">
            My Attendance
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            View your attendance records.
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