@extends('layouts.crm')

@section('title', '')

@section('content')

<div class="max-w-6xl">

    {{-- Header --}}
    <div class="mb-6">

        <h2 class="text-xl font-bold text-gray-800">
            My Schedule
        </h2>

        <p class="text-sm text-gray-400 mt-1">
            View your weekly work schedule, break time, and days off.
        </p>

    </div>


    {{-- Employee Information --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

            <div>

                <h3 class="text-lg font-bold text-gray-800">
                    {{ $employee->first_name }}
                    {{ $employee->last_name }}
                </h3>

                <p class="text-sm text-gray-400 mt-1">

                    Employee No:
                    <span class="font-medium text-gray-600">
                        {{ $employee->employee_no }}
                    </span>

                    @if($employee->branch)
                        <span class="mx-1">•</span>
                        {{ $employee->branch->name }}
                    @endif

                </p>

            </div>

            <div class="text-left sm:text-right">

                <p class="text-sm font-semibold text-gray-700">
                    {{ $weekStart->format('M d, Y') }}
                    –
                    {{ $weekEnd->format('M d, Y') }}
                </p>

                <p class="text-xs text-gray-400 mt-1">
                    Weekly Schedule
                </p>

            </div>

        </div>

    </div>


    {{-- Week Navigation --}}
    <div class="flex flex-col sm:flex-row items-center justify-between gap-3 mb-6">

        {{-- Previous Week --}}
        <a
            href="{{ route('my-schedule', [
                'week' => $weekStart->copy()->subWeek()->format('Y-m-d')
            ]) }}"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-medium rounded-xl transition">

            <svg
                class="w-4 h-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M15 19l-7-7 7-7"/>

            </svg>

            Previous Week

        </a>


        {{-- Current Week --}}
        <a
            href="{{ route('my-schedule') }}"
            class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-xl transition">

            Current Week

        </a>


        {{-- Next Week --}}
        <a
            href="{{ route('my-schedule', [
                'week' => $weekStart->copy()->addWeek()->format('Y-m-d')
            ]) }}"
            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-medium rounded-xl transition">

            Next Week

            <svg
                class="w-4 h-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7"/>

            </svg>

        </a>

    </div>


    {{-- Schedule Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-gray-50 text-gray-400 uppercase text-xs">

                    <tr>

                        <th class="text-left px-6 py-3">
                            Day
                        </th>

                        <th class="text-left px-6 py-3">
                            Date
                        </th>

                        <th class="text-left px-6 py-3">
                            Status
                        </th>

                        <th class="text-left px-6 py-3">
                            Work Hours
                        </th>

                        <th class="text-left px-6 py-3">
                            Break
                        </th>

                        <th class="text-left px-6 py-3">
                            Notes
                        </th>

                    </tr>

                </thead>


                <tbody>

                @for($i = 0; $i < 7; $i++)

                    @php

                        $date = $weekStart->copy()->addDays($i);

                        $dateKey = $date->format('Y-m-d');

                        $schedule = $schedules->get($dateKey);

                        $status = $schedule?->status ?? 'Off';

                    @endphp


                    <tr
                        class="
                            border-b border-gray-50
                            hover:bg-gray-50
                            {{ $date->isToday() ? 'bg-emerald-50/40' : '' }}
                        ">

                        {{-- Day --}}
                        <td class="px-6 py-5">

                            <div class="font-semibold text-gray-800">
                                {{ $date->format('l') }}
                            </div>

                            @if($date->isToday())

                                <span class="text-xs text-emerald-600 font-medium">
                                    Today
                                </span>

                            @endif

                        </td>


                        {{-- Date --}}
                        <td class="px-6 py-5 text-gray-500">

                            {{ $date->format('M d, Y') }}

                        </td>


                        {{-- Status --}}
                        <td class="px-6 py-5">

                            @if($status === 'Working')

                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-50 text-emerald-600">
                                    Working
                                </span>

                            @elseif($status === 'Leave')

                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-600">
                                    Leave
                                </span>

                            @else

                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                    Off
                                </span>

                            @endif

                        </td>


                        {{-- Work Hours --}}
                        <td class="px-6 py-5 text-gray-600">

                            @if($status === 'Working' && $schedule)

                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') }}

                                <span class="text-gray-300 mx-1">
                                    –
                                </span>

                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') }}

                            @else

                                <span class="text-gray-300">
                                    —
                                </span>

                            @endif

                        </td>


                        {{-- Break --}}
                        <td class="px-6 py-5 text-gray-600">

                            @if($status === 'Working' && $schedule)

                                <div class="flex flex-col">

                                    <span>
                                        {{ \Carbon\Carbon::parse($schedule->break_start)->format('g:i A') }}
                                        –
                                        {{ \Carbon\Carbon::parse($schedule->break_end)->format('g:i A') }}
                                    </span>

                                    <span class="text-xs text-gray-400 mt-0.5">
                                        1-hour break
                                    </span>

                                </div>

                            @else

                                <span class="text-gray-300">
                                    —
                                </span>

                            @endif

                        </td>


                        {{-- Notes --}}
                        <td class="px-6 py-5 text-gray-500">

                            @if($schedule?->notes)

                                {{ $schedule->notes }}

                            @else

                                <span class="text-gray-300">
                                    —
                                </span>

                            @endif

                        </td>

                    </tr>

                @endfor

                </tbody>

            </table>

        </div>

    </div>


    {{-- Information --}}
    <div class="mt-6 bg-blue-50 border border-blue-100 rounded-xl px-5 py-4">

        <div class="flex gap-3">

            <svg
                class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13 16h-1v-4h-1m1-8a9 9 0 100 18 9 9 0 000-18z"/>

            </svg>

            <div>

                <p class="text-sm font-medium text-blue-700">
                    Schedule Information
                </p>

                <p class="text-xs text-blue-600 mt-1">
                    Your schedule is managed by HR or an administrator.
                    Please contact them if you believe your schedule is incorrect.
                </p>

            </div>

        </div>

    </div>

</div>

@endsection