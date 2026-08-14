@extends('layouts.crm')

@section('title', '')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Live Employee Monitor
            </h1>

            <p class="text-sm text-gray-400 mt-1">
                Monitor today's employee attendance and break status in real time.
            </p>
        </div>

        <div class="flex items-center gap-3">

            <div class="flex items-center gap-2 text-xs font-medium text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-full">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                Live
            </div>

            <button
                onclick="window.location.reload()"
                class="inline-flex items-center gap-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-medium px-4 py-2.5 rounded-xl transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Refresh
            </button>

        </div>

    </div>


    {{-- Summary --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">

        {{-- Working --}}
        <div class="group relative bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl shadow-lg shadow-emerald-600/25 p-5 overflow-hidden transition-transform hover:-translate-y-1">
            <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full bg-white/10"></div>
            <div class="absolute -right-1 -bottom-6 w-16 h-16 rounded-full bg-white/10"></div>
            <div class="relative flex items-center justify-between">
                <p class="text-xs uppercase tracking-wide text-emerald-50 font-medium">Working</p>
                <span class="w-9 h-9 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center shadow-inner">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
            </div>
            <p class="relative text-3xl font-extrabold text-white mt-3 drop-shadow-sm">{{ $summary['working'] }}</p>
        </div>

        {{-- On Break --}}
        <div class="group relative bg-gradient-to-br from-amber-400 to-amber-500 rounded-2xl shadow-lg shadow-amber-500/25 p-5 overflow-hidden transition-transform hover:-translate-y-1">
            <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full bg-white/10"></div>
            <div class="absolute -right-1 -bottom-6 w-16 h-16 rounded-full bg-white/10"></div>
            <div class="relative flex items-center justify-between">
                <p class="text-xs uppercase tracking-wide text-amber-50 font-medium">On Break</p>
                <span class="w-9 h-9 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center shadow-inner">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M18 8h1a4 4 0 010 8h-1M2 8h16v9a4 4 0 01-4 4H6a4 4 0 01-4-4V8zM6 1v3M10 1v3M14 1v3"/>
                    </svg>
                </span>
            </div>
            <p class="relative text-3xl font-extrabold text-white mt-3 drop-shadow-sm">{{ $summary['break'] }}</p>
        </div>

        {{-- Not Checked In --}}
        <div class="group relative bg-gradient-to-br from-red-400 to-red-500 rounded-2xl shadow-lg shadow-red-500/25 p-5 overflow-hidden transition-transform hover:-translate-y-1">
            <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full bg-white/10"></div>
            <div class="absolute -right-1 -bottom-6 w-16 h-16 rounded-full bg-white/10"></div>
            <div class="relative flex items-center justify-between">
                <p class="text-xs uppercase tracking-wide text-red-50 font-medium">Not Checked In</p>
                <span class="w-9 h-9 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center shadow-inner">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </span>
            </div>
            <p class="relative text-3xl font-extrabold text-white mt-3 drop-shadow-sm">{{ $summary['not_checked_in'] }}</p>
        </div>

        {{-- Checked Out --}}
        <div class="group relative bg-gradient-to-br from-gray-400 to-gray-500 rounded-2xl shadow-lg shadow-gray-500/25 p-5 overflow-hidden transition-transform hover:-translate-y-1">
            <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full bg-white/10"></div>
            <div class="absolute -right-1 -bottom-6 w-16 h-16 rounded-full bg-white/10"></div>
            <div class="relative flex items-center justify-between">
                <p class="text-xs uppercase tracking-wide text-gray-50 font-medium">Checked Out</p>
                <span class="w-9 h-9 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center shadow-inner">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 5v1a3 3 0 01-3 3H6a3 3 0 01-3-3V6a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </span>
            </div>
            <p class="relative text-3xl font-extrabold text-white mt-3 drop-shadow-sm">{{ $summary['checked_out'] }}</p>
        </div>

        {{-- On Leave --}}
        <div class="group relative bg-gradient-to-br from-blue-400 to-blue-500 rounded-2xl shadow-lg shadow-blue-500/25 p-5 overflow-hidden transition-transform hover:-translate-y-1">
            <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full bg-white/10"></div>
            <div class="absolute -right-1 -bottom-6 w-16 h-16 rounded-full bg-white/10"></div>
            <div class="relative flex items-center justify-between">
                <p class="text-xs uppercase tracking-wide text-blue-50 font-medium">On Leave</p>
                <span class="w-9 h-9 rounded-xl bg-white/20 backdrop-blur flex items-center justify-center shadow-inner">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </span>
            </div>
            <p class="relative text-3xl font-extrabold text-white mt-3 drop-shadow-sm">{{ $summary['leave'] }}</p>
        </div>

        {{-- Day Off --}}
        <div class="group relative bg-gradient-to-br from-gray-700 to-gray-800 rounded-2xl shadow-lg shadow-gray-800/25 p-5 overflow-hidden transition-transform hover:-translate-y-1">
            <div class="absolute -right-4 -top-4 w-20 h-20 rounded-full bg-white/10"></div>
            <div class="absolute -right-1 -bottom-6 w-16 h-16 rounded-full bg-white/10"></div>
            <div class="relative flex items-center justify-between">
                <p class="text-xs uppercase tracking-wide text-gray-300 font-medium">Day Off</p>
                <span class="w-9 h-9 rounded-xl bg-white/10 backdrop-blur flex items-center justify-center shadow-inner">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </span>
            </div>
            <p class="relative text-3xl font-extrabold text-white mt-3 drop-shadow-sm">{{ $summary['off'] }}</p>
        </div>

    </div>


    {{-- Filters --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">

        <div class="flex flex-col lg:flex-row lg:items-end gap-4">

            {{-- Branch Filter --}}
            <div class="flex-1">
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">
                    Branch
                </label>

                <select
                    id="branchFilter"
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400"
                >
                    <option value="all">All Branches</option>

                    @php
                        $branches = collect($employees)
                            ->map(fn ($item) => $item['employee']->branch?->name)
                            ->filter()
                            ->unique()
                            ->sort();
                    @endphp

                    @foreach($branches as $branch)
                        <option value="{{ strtolower($branch) }}">
                            {{ $branch }}
                        </option>
                    @endforeach
                </select>
            </div>


            {{-- Status Filter --}}
            <div class="flex-1">
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">
                    Employee Status
                </label>

                <select
                    id="statusFilter"
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400"
                >
                    <option value="all">All Status</option>
                    <option value="working">Working / Checked In</option>
                    <option value="on break">On Break</option>
                    <option value="late">Late</option>
                    <option value="checked out">Checked Out</option>
                    <option value="absent">Absent</option>
                    <option value="day off">Day Off</option>
                    <option value="on leave">On Leave</option>
                    <option value="not checked in">Not Checked In</option>
                </select>
            </div>


            {{-- Search --}}
            <div class="flex-1">
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-400 mb-2">
                    Search Employee
                </label>

                <input
                    type="text"
                    id="employeeSearch"
                    placeholder="Search name or employee no..."
                    class="w-full rounded-xl border border-gray-200 bg-white px-4 py-2.5 text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-emerald-200 focus:border-emerald-400"
                >
            </div>


            {{-- Reset --}}
            <div>
                <button
                    type="button"
                    id="resetFilters"
                    class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-600 text-sm font-medium px-4 py-2.5 rounded-xl transition"
                >
                    Reset
                </button>
            </div>

        </div>

    </div>


    {{-- Employee Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <div>
                <h2 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Today's Employees</h2>
            </div>
        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-gray-50 text-gray-400 uppercase text-[11px] tracking-wide">
                    <tr>
                        <th class="text-left px-6 py-3 font-semibold">Employee</th>
                        <th class="text-left px-6 py-3 font-semibold">Branch</th>
                        <th class="text-left px-6 py-3 font-semibold">Schedule</th>
                        <th class="text-left px-6 py-3 font-semibold">Time In</th>
                        <th class="text-left px-6 py-3 font-semibold">Status</th>
                        <th class="text-left px-6 py-3 font-semibold">Break</th>
                        <th class="text-left px-6 py-3 font-semibold">Time Out</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50">

                @forelse($employees as $item)

                    @php
                        $employee = $item['employee'];
                        $attendance = $item['attendance'];
                        $schedule = $item['schedule'];
                        $status = $item['status'];
                        $initials = strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1));

                        $statusStyles = [
                            'Working'         => ['bg-emerald-50 text-emerald-700', 'bg-emerald-500', false],
                            'On Break'        => ['bg-amber-50 text-amber-700', 'bg-amber-500', true],
                            'On Leave'        => ['bg-blue-50 text-blue-700', 'bg-blue-500', false],
                            'Day Off'         => ['bg-gray-100 text-gray-600', 'bg-gray-500', false],
                            'Checked Out'     => ['bg-gray-100 text-gray-600', 'bg-gray-400', false],
                        ];
                        [$badgeClass, $dotClass, $pulse] = $statusStyles[$status] ?? ['bg-red-50 text-red-700', 'bg-red-500', false];
                        $statusLabel = in_array($status, array_keys($statusStyles)) ? $status : 'Not Checked In';
                    @endphp

                    <tr
                        class="employee-row hover:bg-gray-50/70 transition-colors"
                        data-branch="{{ strtolower($employee->branch->name ?? '') }}"
                        data-status="{{ strtolower($statusLabel) }}"
                        data-name="{{ strtolower($employee->first_name . ' ' . $employee->last_name) }}"
                        data-employee-no="{{ strtolower($employee->employee_no) }}"
                    >

                        {{-- Employee --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold flex items-center justify-center shrink-0">
                                    {{ $initials }}
                                </span>
                                <div>
                                    <div class="font-medium text-gray-800">
                                        {{ $employee->first_name }} {{ $employee->last_name }}
                                    </div>
                                    <div class="text-xs text-gray-400 mt-0.5">
                                        {{ $employee->employee_no }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Branch --}}
                        <td class="px-6 py-4 text-gray-500">
                            {{ $employee->branch->name ?? '—' }}
                        </td>

                        {{-- Schedule --}}
                        <td class="px-6 py-4">
                            @if($schedule && $schedule->status === 'Working')
                                <div class="font-medium text-gray-700">
                                    {{ $schedule->start_time ? \Carbon\Carbon::parse($schedule->start_time)->format('g:i A') : '—' }}
                                    &ndash;
                                    {{ $schedule->end_time ? \Carbon\Carbon::parse($schedule->end_time)->format('g:i A') : '—' }}
                                </div>
                            @elseif($schedule)
                                <span class="text-gray-500">{{ $schedule->status }}</span>
                            @else
                                <span class="text-gray-400">No schedule</span>
                            @endif
                        </td>

                        {{-- Time In --}}
                        <td class="px-6 py-4 text-gray-500">
                            {{ $attendance?->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('g:i A') : '—' }}
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                <span class="w-1.5 h-1.5 rounded-full {{ $dotClass }} {{ $pulse ? 'animate-pulse' : '' }}"></span>
                                {{ $statusLabel }}
                            </span>
                        </td>

                        {{-- Break --}}
                        <td class="px-6 py-4">
                            @if($status === 'On Break')
                                <div class="text-amber-700 font-medium text-xs">
                                    {{ $item['break_elapsed'] }} min elapsed
                                </div>
                                <div class="text-[11px] text-gray-400">
                                    {{ $item['break_remaining'] }} min remaining
                                </div>
                                @if(isset($item['break_elapsed'], $item['break_remaining']) && ($item['break_elapsed'] + $item['break_remaining']) > 0)
                                    @php
                                        $pct = min(100, round(($item['break_elapsed'] / ($item['break_elapsed'] + $item['break_remaining'])) * 100));
                                    @endphp
                                    <div class="w-24 h-1.5 rounded-full bg-amber-100 mt-1.5 overflow-hidden">
                                        <div class="h-full bg-amber-500 rounded-full" style="width: {{ $pct }}%"></div>
                                    </div>
                                @endif
                            @elseif($attendance?->break_end)
                                <div class="text-emerald-600 font-medium text-xs">Break completed</div>
                                @if($attendance->break_minutes)
                                    <div class="text-[11px] text-gray-400">{{ $attendance->break_minutes }} minutes</div>
                                @endif
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>

                        {{-- Time Out --}}
                        <td class="px-6 py-4 text-gray-500">
                            {{ $attendance?->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('g:i A') : '—' }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="text-center py-16">
                            <div class="flex flex-col items-center gap-2 text-gray-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4"/>
                                </svg>
                                <p class="text-sm">No active employees found.</p>
                            </div>
                        </td>
                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- Employee Filters --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const branchFilter = document.getElementById('branchFilter');
    const statusFilter = document.getElementById('statusFilter');
    const employeeSearch = document.getElementById('employeeSearch');
    const resetFilters = document.getElementById('resetFilters');

    const rows = document.querySelectorAll('.employee-row');


    function filterEmployees() {

        const selectedBranch = branchFilter.value.toLowerCase();
        const selectedStatus = statusFilter.value.toLowerCase();
        const search = employeeSearch.value.toLowerCase().trim();


        rows.forEach(row => {

            const branch = row.dataset.branch;
            const status = row.dataset.status;
            const name = row.dataset.name;
            const employeeNo = row.dataset.employeeNo;


            // Branch match
            const branchMatch =
                selectedBranch === 'all' ||
                branch === selectedBranch;


            // Status match
            let statusMatch = true;

            if (selectedStatus !== 'all') {

                if (selectedStatus === 'working') {

                    statusMatch =
                        status === 'working';

                } else if (selectedStatus === 'late') {

                    // Late employees are still working
                    statusMatch =
                        status === 'late' ||
                        status === 'working';

                } else {

                    statusMatch =
                        status === selectedStatus;
                }
            }


            // Search match
            const searchMatch =
                search === '' ||
                name.includes(search) ||
                employeeNo.includes(search);


            // Final result
            if (
                branchMatch &&
                statusMatch &&
                searchMatch
            ) {

                row.style.display = '';

            } else {

                row.style.display = 'none';

            }

        });

    }


    branchFilter.addEventListener('change', filterEmployees);

    statusFilter.addEventListener('change', filterEmployees);

    employeeSearch.addEventListener('input', filterEmployees);


    resetFilters.addEventListener('click', function () {

        branchFilter.value = 'all';

        statusFilter.value = 'all';

        employeeSearch.value = '';

        filterEmployees();

    });

});


// Auto refresh every 30 seconds
setTimeout(function () {
    window.location.reload();
}, 30000);

</script>

@endsection