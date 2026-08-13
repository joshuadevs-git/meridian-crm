@extends('layouts.crm')

@section('title', '')

@section('content')

<div class="space-y-6">

    {{-- Attendance Actions --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header strip --}}
        <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">

            <div class="flex items-center gap-3">
                <span class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </span>
                <div>
                    <h3 class="text-base font-bold text-gray-800">Today's Attendance</h3>
                    <p class="text-xs text-gray-400 mt-0.5">{{ now()->format('l, F j, Y') }}</p>
                </div>
            </div>

            @if($todaySchedule && $todaySchedule->status === 'Working')
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Working
                </span>
            @elseif($todaySchedule && $todaySchedule->status === 'Off')
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span> Off
                </span>
            @elseif($todaySchedule && $todaySchedule->status === 'Leave')
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-600 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Leave
                </span>
            @endif

        </div>

        <div class="p-6">

            @if($todaySchedule)
                <p class="text-sm text-gray-400 -mt-1 mb-5">
                    Schedule:
                    <span class="text-gray-600 font-medium">
                        @if($todaySchedule->status === 'Working')
                            {{ $todaySchedule->start_time?->format('h:i A') }} – {{ $todaySchedule->end_time?->format('h:i A') }}
                        @else
                            {{ $todaySchedule->status }}
                        @endif
                    </span>
                </p>
            @else
                <div class="flex items-center gap-2 text-sm text-red-500 mb-5">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                    No schedule assigned for today.
                </div>
            @endif

            {{-- No attendance yet --}}
            @if(!$todayAttendance)

                @if($todaySchedule && $todaySchedule->status === 'Working')

                    <form action="{{ route('my-attendance.check-in') }}" method="POST">
                        @csrf
                        <button
                            type="submit"
                            class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-sm font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2 shadow-sm shadow-emerald-600/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4M10 17l5-5-5-5M15 12H3"/>
                            </svg>
                            Check In
                        </button>
                    </form>

                @elseif($todaySchedule && $todaySchedule->status === 'Off')

                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center text-sm font-medium text-gray-600">
                        You are scheduled OFF today.
                    </div>

                @elseif($todaySchedule && $todaySchedule->status === 'Leave')

                    <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 text-center text-sm font-medium text-amber-700">
                        You are on leave today.
                    </div>

                @else

                    <div class="bg-red-50 border border-red-100 rounded-xl p-4 text-center text-sm font-medium text-red-600">
                        No schedule assigned for today.
                    </div>

                @endif

            {{-- Already checked in --}}
            @else

                {{-- Time In / Time Out cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                    <div class="bg-emerald-50 rounded-xl p-4 flex items-center gap-3">
                        <span class="w-9 h-9 rounded-lg bg-emerald-600 text-white flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-[10px] font-semibold text-emerald-600 uppercase tracking-wide">Time In</p>
                            <p class="text-lg font-bold text-emerald-700 leading-tight">
                                {{ $todayAttendance->time_in?->format('h:i A') ?? '—' }}
                            </p>
                            <p class="text-[11px] text-emerald-600/70 mt-0.5">{{ $todayAttendance->status }}</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4 flex items-center gap-3">
                        <span class="w-9 h-9 rounded-lg bg-gray-200 text-gray-500 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M20 12H4"/>
                            </svg>
                        </span>
                        <div>
                            <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-wide">Time Out</p>
                            <p class="text-lg font-bold text-gray-700 leading-tight">
                                {{ $todayAttendance->time_out?->format('h:i A') ?? 'Not yet' }}
                            </p>
                        </div>
                    </div>

                </div>

                {{-- BREAK SECTION --}}
                @if(!$todayAttendance->time_out)

                    <div class="mt-5 border-t border-gray-100 pt-5">

                        <div class="flex items-center justify-between mb-3">

                            <div>
                                <h4 class="font-semibold text-gray-800 text-sm flex items-center gap-1.5">
                                    <span>☕</span> Break
                                </h4>
                                @if($todaySchedule && $todaySchedule->break_start)
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        Scheduled: {{ $todaySchedule->break_start->format('h:i A') }} – {{ $todaySchedule->break_end?->format('h:i A') ?? '1 hour' }}
                                    </p>
                                @else
                                    <p class="text-xs text-gray-400 mt-0.5">1-hour break</p>
                                @endif
                            </div>

                            @if($todayAttendance->break_start && !$todayAttendance->break_end)
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-600">On Break</span>
                            @elseif($todayAttendance->break_end)
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-600">Completed</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500">Not Started</span>
                            @endif

                        </div>

                        @if(!$todayAttendance->break_start)

                            <form action="{{ route('my-attendance.start-break') }}" method="POST">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-sm font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                                    <span>☕</span> Start Break
                                </button>
                            </form>

                        @elseif(!$todayAttendance->break_end)

                            <form action="{{ route('my-attendance.end-break') }}" method="POST">
                                @csrf
                                <button
                                    type="submit"
                                    class="w-full sm:w-auto bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-sm font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 3l14 9-14 9V3z"/>
                                    </svg>
                                    Resume Work
                                </button>
                            </form>

                        @else

                            <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 text-center">
                                <p class="text-sm font-medium text-emerald-700">Break completed</p>
                                <p class="text-xs text-emerald-600 mt-0.5">{{ $todayAttendance->break_minutes ?? 0 }} minutes</p>
                            </div>

                        @endif

                    </div>

                    {{-- CHECK OUT --}}
                    <div class="mt-4">
                        <form action="{{ route('my-attendance.check-out') }}" method="POST">
                            @csrf
                            <button
                                type="submit"
                                class="w-full sm:w-auto bg-white hover:bg-red-50 text-red-500 text-sm font-semibold py-2 px-4 rounded-lg transition flex items-center justify-center gap-2 border border-red-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 5v1a3 3 0 01-3 3H6a3 3 0 01-3-3V6a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Check Out
                            </button>
                        </form>
                    </div>

                @endif

            @endif

        </div>

    </div>


    {{-- Attendance Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <h2 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Attendance History</h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 text-gray-400 uppercase text-[11px] tracking-wide">
                    <tr>
                        <th class="text-left px-6 py-3 font-semibold">Date</th>
                        <th class="text-left px-6 py-3 font-semibold">Time In</th>
                        <th class="text-left px-6 py-3 font-semibold">Time Out</th>
                        <th class="text-left px-6 py-3 font-semibold">Status</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50">

                    @forelse($attendances as $attendance)

                        <tr class="hover:bg-gray-50/70 transition-colors">

                            <td class="px-6 py-4 text-gray-700 font-medium">
                                {{ \Carbon\Carbon::parse($attendance->attendance_date)->format('M d, Y') }}
                            </td>

                            <td class="px-6 py-4 text-gray-500">
                                {{ $attendance->time_in ?? '—' }}
                            </td>

                            <td class="px-6 py-4 text-gray-500">
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

                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $style }}">
                                    {{ $attendance->status }}
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="text-center py-14">
                                <div class="flex flex-col items-center gap-2 text-gray-400">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <p class="text-sm">No attendance records found.</p>
                                </div>
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