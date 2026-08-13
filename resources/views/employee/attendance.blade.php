@extends('layouts.crm')

@section('title', '')

@section('content')

<div class="space-y-6">

    {{-- Today's Attendance --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

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
                            type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Check In
                        </button>
                    </form>

                @elseif(!$todayAttendance->time_out)

                    <form action="{{ route('my-attendance.check-out') }}" method="POST">
                        @csrf

                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-red-600 hover:bg-red-700 text-white text-sm font-medium shadow-sm transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Check Out
                        </button>
                    </form>

                @else

                    <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-50 text-emerald-700 text-sm font-medium">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Attendance Completed
                    </span>

                @endif

            </div>

        </div>

        <div class="grid grid-cols-3 gap-4 mt-6">

            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-[10px] text-gray-400 uppercase tracking-wide">Time In</p>
                <p class="font-semibold text-gray-800 mt-1">
                    {{ $todayAttendance?->time_in
                        ? \Carbon\Carbon::parse($todayAttendance->time_in)->format('h:i A')
                        : '—' }}
                </p>
            </div>

            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-[10px] text-gray-400 uppercase tracking-wide">Time Out</p>
                <p class="font-semibold text-gray-800 mt-1">
                    {{ $todayAttendance?->time_out
                        ? \Carbon\Carbon::parse($todayAttendance->time_out)->format('h:i A')
                        : '—' }}
                </p>
            </div>

            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-[10px] text-gray-400 uppercase tracking-wide">Status</p>
                <p class="font-semibold text-gray-800 mt-1">
                    {{ $todayAttendance?->status ?? 'Not Checked In' }}
                </p>
            </div>

        </div>

    </div>

    {{-- Attendance Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">

        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-bold text-gray-800">
                Attendance History
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 text-gray-400 uppercase text-xs">
                    <tr>
                        <th class="text-left px-6 py-3">Date</th>
                        <th class="text-left px-6 py-3">Time In</th>
                        <th class="text-left px-6 py-3">Time Out</th>
                        <th class="text-left px-6 py-3">Status</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($attendances as $attendance)

                        <tr class="border-b border-gray-50 hover:bg-gray-50">

                            <td class="px-6 py-4 text-gray-600">
                                {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('M d, Y') }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $attendance->time_in ?? '—' }}
                            </td>

                            <td class="px-6 py-4 text-gray-600">
                                {{ $attendance->time_out ?? '—' }}
                            </td>

                            <td class="px-6 py-4">

                                @php
                                    $statusStyles = [
                                        'Present' => 'bg-emerald-50 text-emerald-600',
                                        'Late' => 'bg-amber-50 text-amber-600',
                                        'Absent' => 'bg-red-50 text-red-500',
                                        'Leave' => 'bg-indigo-50 text-indigo-500',
                                    ];
                                    $style = $statusStyles[$attendance->status] ?? 'bg-gray-100 text-gray-600';
                                @endphp

                                <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $style }}">
                                    {{ $attendance->status }}
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="text-center py-12 text-gray-400">
                                No attendance records found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($attendances->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $attendances->links() }}
            </div>
        @endif

    </div>

</div>

@endsection