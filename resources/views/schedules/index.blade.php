@extends('layouts.crm')

@section('title', '')

@section('content')

<div class="max-w-7xl">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">

        <div>
            <h2 class="text-xl font-bold text-gray-800">
                Employee Schedule
            </h2>

            <p class="text-sm text-gray-400 mt-1">
                Manage weekly employee working hours, breaks, off days, and leave schedules.
            </p>
        </div>

    </div>


    {{-- Employee / Week Selection --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">

        <form
            method="GET"
            action="{{ route('schedules.index') }}"
            class="grid grid-cols-1 md:grid-cols-3 gap-4">

            {{-- Employee --}}
            <div>

                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Employee
                </label>

                <select
                    name="employee_id"
                    required
                    class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

                    <option value="">
                        Select Employee
                    </option>

                    @foreach($employees as $item)

                        <option
                            value="{{ $item->id }}"
                            {{ $employeeId == $item->id ? 'selected' : '' }}>

                            {{ $item->first_name }}
                            {{ $item->last_name }}

                            @if($item->branch)
                                — {{ $item->branch->name }}
                            @endif

                        </option>

                    @endforeach

                </select>

            </div>


            {{-- Week --}}
            <div>

                <label class="block text-sm font-medium text-gray-600 mb-1">
                    Week Starting
                </label>

                <input
                    type="date"
                    name="week"
                    value="{{ $weekStart->format('Y-m-d') }}"
                    class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

            </div>


            {{-- Button --}}
            <div class="flex items-end">

                <button
                    type="submit"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-5 py-2.5 rounded-xl transition">

                    Load Schedule

                </button>

            </div>

        </form>

    </div>


    @if($employee)

        {{-- Employee Header --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-6">

            <div class="flex items-center justify-between gap-4">

                <div>

                    <h3 class="text-lg font-bold text-gray-800">

                        {{ $employee->first_name }}
                        {{ $employee->last_name }}

                    </h3>

                    <p class="text-sm text-gray-400">

                        {{ $employee->employee_no }}

                        @if($employee->branch)
                            · {{ $employee->branch->name }}
                        @endif

                    </p>

                </div>

                <div class="text-right">

                    <p class="text-sm font-medium text-gray-700">

                        {{ $weekStart->format('M d, Y') }}

                        —

                        {{ $weekEnd->format('M d, Y') }}

                    </p>

                    <p class="text-xs text-gray-400">
                        Weekly Schedule
                    </p>

                </div>

            </div>

        </div>


        {{-- Schedule Form --}}
        <form
            method="POST"
            action="{{ route('schedules.store') }}">

            @csrf

            <input
                type="hidden"
                name="employee_id"
                value="{{ $employee->id }}">

            <input
                type="hidden"
                name="week_start"
                value="{{ $weekStart->format('Y-m-d') }}">


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
                                    Start
                                </th>

                                <th class="text-left px-6 py-3">
                                    End
                                </th>

                                <th class="text-left px-6 py-3">
                                    Break Start
                                </th>

                                <th class="text-left px-6 py-3">
                                    Break End
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

                                $startTime = $schedule?->start_time
                                    ? \Carbon\Carbon::parse($schedule->start_time)->format('H:i')
                                    : '08:00';

                                $endTime = $schedule?->end_time
                                    ? \Carbon\Carbon::parse($schedule->end_time)->format('H:i')
                                    : '17:00';

                                $breakStart = $schedule?->break_start
                                    ? \Carbon\Carbon::parse($schedule->break_start)->format('H:i')
                                    : '12:00';

                                $breakEnd = $schedule?->break_end
                                    ? \Carbon\Carbon::parse($schedule->break_end)->format('H:i')
                                    : '13:00';

                            @endphp


                            <tr class="border-b border-gray-50 hover:bg-gray-50">

                                {{-- Day --}}
                                <td class="px-6 py-4 font-medium text-gray-800">

                                    {{ $date->format('l') }}

                                </td>


                                {{-- Date --}}
                                <td class="px-6 py-4 text-gray-500">

                                    {{ $date->format('M d, Y') }}

                                </td>


                                {{-- Status --}}
                                <td class="px-6 py-4">

                                    <select
                                        name="schedule[{{ $dateKey }}][status]"
                                        class="schedule-status rounded-lg border border-gray-200 px-3 py-2 text-sm">

                                        <option
                                            value="Working"
                                            {{ $status === 'Working' ? 'selected' : '' }}>
                                            Working
                                        </option>

                                        <option
                                            value="Off"
                                            {{ $status === 'Off' ? 'selected' : '' }}>
                                            Off
                                        </option>

                                        <option
                                            value="Leave"
                                            {{ $status === 'Leave' ? 'selected' : '' }}>
                                            Leave
                                        </option>

                                    </select>

                                </td>


                                {{-- Start --}}
                                <td class="px-6 py-4">

                                    <input
                                        type="time"
                                        name="schedule[{{ $dateKey }}][start_time]"
                                        value="{{ $startTime }}"
                                        class="schedule-time rounded-lg border border-gray-200 px-3 py-2 text-sm">

                                </td>


                                {{-- End --}}
                                <td class="px-6 py-4">

                                    <input
                                        type="time"
                                        name="schedule[{{ $dateKey }}][end_time]"
                                        value="{{ $endTime }}"
                                        class="schedule-time rounded-lg border border-gray-200 px-3 py-2 text-sm">

                                </td>


                                {{-- Break Start --}}
                                <td class="px-6 py-4">

                                    <input
                                        type="time"
                                        name="schedule[{{ $dateKey }}][break_start]"
                                        value="{{ $breakStart }}"
                                        class="schedule-time rounded-lg border border-gray-200 px-3 py-2 text-sm">

                                </td>


                                {{-- Break End --}}
                                <td class="px-6 py-4">

                                    <input
                                        type="time"
                                        name="schedule[{{ $dateKey }}][break_end]"
                                        value="{{ $breakEnd }}"
                                        class="schedule-time rounded-lg border border-gray-200 px-3 py-2 text-sm">

                                </td>


                                {{-- Notes --}}
                                <td class="px-6 py-4">

                                    <input
                                        type="text"
                                        name="schedule[{{ $dateKey }}][notes]"
                                        value="{{ $schedule?->notes }}"
                                        placeholder="Optional"
                                        class="w-36 rounded-lg border border-gray-200 px-3 py-2 text-sm">

                                </td>

                            </tr>

                        @endfor

                        </tbody>

                    </table>

                </div>


                {{-- Footer --}}
                <div class="flex items-center justify-between gap-4 p-6 border-t border-gray-100">

                    <p class="text-xs text-gray-400">
                        Working days automatically use a
                        <strong>1-hour break</strong>
                        by default.
                    </p>

                    <button
                        type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-6 py-2.5 rounded-xl shadow-sm transition">

                        Save Weekly Schedule

                    </button>

                </div>

            </div>

        </form>

    @else

        {{-- Empty State --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">

            <div class="w-14 h-14 mx-auto mb-4 rounded-2xl bg-emerald-50 flex items-center justify-center">

                <svg
                    class="w-7 h-7 text-emerald-600"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">

                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>

                </svg>

            </div>

            <h3 class="text-lg font-semibold text-gray-800">
                Select an Employee
            </h3>

            <p class="text-sm text-gray-400 mt-1">
                Select an employee above to view and manage their weekly schedule.
            </p>

        </div>

    @endif

</div>

@endsection