@extends('layouts.crm')

@section('title', '')

@section('content')

<div class="min-h-screen bg-gray-50 p-6 relative overflow-hidden">

    {{-- Soft decorative glow blobs behind the header — this is what the glass
         cards refract/blur against. Purely cosmetic, sits behind everything. --}}
    <div class="pointer-events-none absolute -top-24 -left-24 w-96 h-96 bg-emerald-300/40 rounded-full blur-3xl"></div>
    <div class="pointer-events-none absolute -top-10 right-0 w-[28rem] h-[28rem] bg-emerald-200/50 rounded-full blur-3xl"></div>
    <div class="pointer-events-none absolute top-40 left-1/3 w-72 h-72 bg-teal-200/30 rounded-full blur-3xl"></div>

    <div class="relative">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-sm text-gray-400 mt-1">Track, manage and forecast your employees with ease</p>
        </div>
    </div>

    {{-- Top Stat Cards — glassmorphic --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        {{-- Hero card: solid gradient glass, stays the visual anchor --}}
        <div class="relative rounded-3xl p-6 text-white overflow-hidden
                    bg-gradient-to-br from-emerald-500/90 via-emerald-600/90 to-emerald-700/90
                    backdrop-blur-xl border border-white/20 shadow-[0_8px_32px_rgba(5,150,105,0.35)]
                    transition-transform duration-300 hover:-translate-y-1">
            {{-- top glass sheen --}}
            <div class="absolute inset-x-0 top-0 h-1/2 bg-gradient-to-b from-white/25 to-transparent pointer-events-none"></div>
            {{-- inner top hairline highlight, like a beveled glass edge --}}
            <div class="absolute inset-x-4 top-0 h-px bg-white/40"></div>

            <div class="relative flex items-center justify-between">
                <span class="text-xs uppercase tracking-wide text-emerald-50/90">Total Employees</span>
                <span class="bg-white/20 backdrop-blur-sm rounded-lg p-1.5 border border-white/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4"/>
                    </svg>
                </span>
            </div>
            <p class="relative text-3xl font-bold mt-4 drop-shadow-sm">{{ $totalEmployees }}</p>
            <p class="relative text-xs text-emerald-50/80 mt-1">All registered staff</p>
        </div>

        <div class="group relative rounded-3xl p-6 overflow-hidden
                    bg-white/60 backdrop-blur-xl border border-white/60
                    shadow-[0_8px_32px_rgba(31,41,55,0.08)]
                    transition-all duration-300 hover:-translate-y-1 hover:bg-white/75">
            <div class="absolute inset-x-4 top-0 h-px bg-white/80"></div>
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-wide text-gray-400">Active Employees</span>
                <span class="bg-emerald-50/80 backdrop-blur-sm text-emerald-600 rounded-lg p-1.5 border border-emerald-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-bold mt-4 text-gray-800">{{ $activeEmployees }}</p>
            <p class="text-xs text-gray-400 mt-1">Currently working</p>
        </div>

        <div class="group relative rounded-3xl p-6 overflow-hidden
                    bg-white/60 backdrop-blur-xl border border-white/60
                    shadow-[0_8px_32px_rgba(31,41,55,0.08)]
                    transition-all duration-300 hover:-translate-y-1 hover:bg-white/75">
            <div class="absolute inset-x-4 top-0 h-px bg-white/80"></div>
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-wide text-gray-400">Inactive Employees</span>
                <span class="bg-red-50/80 backdrop-blur-sm text-red-500 rounded-lg p-1.5 border border-red-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-bold mt-4 text-gray-800">{{ $inactiveEmployees }}</p>
            <p class="text-xs text-gray-400 mt-1">Not currently active</p>
        </div>

        <div class="group relative rounded-3xl p-6 overflow-hidden
                    bg-white/60 backdrop-blur-xl border border-white/60
                    shadow-[0_8px_32px_rgba(31,41,55,0.08)]
                    transition-all duration-300 hover:-translate-y-1 hover:bg-white/75">
            <div class="absolute inset-x-4 top-0 h-px bg-white/80"></div>
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-wide text-gray-400">Total Branches</span>
                <span class="bg-indigo-50/80 backdrop-blur-sm text-indigo-500 rounded-lg p-1.5 border border-indigo-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M5 21V7l8-4v18M13 21V11l6 3v7"/>
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-bold mt-4 text-gray-800">{{ $totalBranches }}</p>
            <p class="text-xs text-gray-400 mt-1">Branch locations</p>
        </div>

    </div>

    {{-- Payroll Summary — glassmorphic, slightly quieter than the hero row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mt-6">

        <div class="relative rounded-2xl p-6 overflow-hidden
                    bg-white/50 backdrop-blur-lg border border-white/60
                    shadow-[0_6px_24px_rgba(31,41,55,0.06)]
                    transition-all duration-300 hover:-translate-y-0.5 hover:bg-white/65">
            <div class="absolute inset-x-4 top-0 h-px bg-white/70"></div>
            <h3 class="text-xs uppercase tracking-wide text-gray-400">Payroll Records</h3>
            <p class="text-2xl font-bold mt-2 text-gray-800">{{ $totalPayrolls }}</p>
        </div>

        <div class="relative rounded-2xl p-6 overflow-hidden
                    bg-white/50 backdrop-blur-lg border border-white/60
                    shadow-[0_6px_24px_rgba(31,41,55,0.06)]
                    transition-all duration-300 hover:-translate-y-0.5 hover:bg-white/65">
            <div class="absolute inset-x-4 top-0 h-px bg-white/70"></div>
            <h3 class="text-xs uppercase tracking-wide text-gray-400">Total Payroll Amount</h3>
            <p class="text-2xl font-bold mt-2 text-gray-800">₱{{ number_format($totalPayrollAmount, 2) }}</p>
        </div>

        <div class="relative rounded-2xl p-6 overflow-hidden
                    bg-white/50 backdrop-blur-lg border border-white/60
                    shadow-[0_6px_24px_rgba(16,185,129,0.10)]
                    transition-all duration-300 hover:-translate-y-0.5 hover:bg-white/65">
            <div class="absolute inset-x-4 top-0 h-px bg-white/70"></div>
            <h3 class="text-xs uppercase tracking-wide text-gray-400">Highest Payroll</h3>
            <p class="text-2xl font-bold mt-2 text-emerald-600">₱{{ number_format($highestPayroll, 2) }}</p>
        </div>

        <div class="relative rounded-2xl p-6 overflow-hidden
                    bg-white/50 backdrop-blur-lg border border-white/60
                    shadow-[0_6px_24px_rgba(31,41,55,0.06)]
                    transition-all duration-300 hover:-translate-y-0.5 hover:bg-white/65">
            <div class="absolute inset-x-4 top-0 h-px bg-white/70"></div>
            <h3 class="text-xs uppercase tracking-wide text-gray-400">Lowest Payroll</h3>
            <p class="text-2xl font-bold mt-2 text-gray-800">₱{{ number_format($lowestPayroll, 2) }}</p>
        </div>

    </div>

    {{-- Middle Section: Analytics + Team + Progress --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mt-8">

        {{-- Attendance Trend — dark "designed" panel so the glass/glow line pops --}}
        <div class="relative rounded-3xl p-6 xl:col-span-2 overflow-hidden
                    bg-gradient-to-br from-gray-900 via-gray-950 to-black
                    border border-white/10 shadow-[0_20px_50px_rgba(0,0,0,0.45)]">

            {{-- ambient glow blobs, purely decorative --}}
            <div class="pointer-events-none absolute -top-16 -right-10 w-64 h-64 bg-emerald-500/20 rounded-full blur-3xl"></div>
            <div class="pointer-events-none absolute -bottom-20 -left-10 w-72 h-72 bg-emerald-400/10 rounded-full blur-3xl"></div>

            {{-- subtle dot-grid texture --}}
            <div class="pointer-events-none absolute inset-0 opacity-[0.15]"
                 style="background-image: radial-gradient(rgba(255,255,255,0.6) 1px, transparent 1px);
                        background-size: 18px 18px;"></div>

            {{-- top glass hairline to match the rest of the card set --}}
            <div class="absolute inset-x-4 top-0 h-px bg-white/10"></div>

            <div class="relative flex items-center gap-2 mb-1">
                <span class="w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_8px_2px_rgba(52,211,153,0.6)]"></span>
                <h2 class="font-bold text-white text-sm uppercase tracking-wide">Attendance Trend</h2>
            </div>
            <p class="relative text-2 font-bold text-white">{{ $presentToday }} <span class="text-sm font-normal text-gray-400">present today</span></p>

            <div class="relative grid grid-cols-3 gap-3 mt-4 mb-2">
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-3">
                    <p class="text-[10px] text-gray-400 uppercase tracking-wide">Late</p>
                    <p class="text-lg font-bold text-amber-400 mt-0.5">{{ $lateToday }}</p>
                </div>
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-3">
                    <p class="text-[10px] text-gray-400 uppercase tracking-wide">Absent</p>
                    <p class="text-lg font-bold text-red-400 mt-0.5">{{ $absentToday }}</p>
                </div>
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 rounded-xl p-3">
                    <p class="text-[10px] text-gray-400 uppercase tracking-wide">Total</p>
                    <p class="text-lg font-bold text-white mt-0.5">{{ $totalAttendance }}</p>
                </div>
            </div>

            {{-- Fixed-height, relative wrapper is required so Chart.js has a stable
                 box to measure against (prevents the canvas from growing every resize tick). --}}
            <div class="relative mt-4 h-56 ">
                <canvas id="attendanceTrendChart"
                        data-labels='@json($dailyLabels)'
                        data-values='@json($dailyCounts)'></canvas>
            </div>
            <p class="relative text-xs text-gray-500 text-center mt-2">Last 7 days attendance</p>
        </div>


        {{-- Attendance Status Donut (segmented, side legend) --}}
        <div class="relative rounded-3xl p-6 xl:col-span-1 flex flex-col overflow-hidden
                    bg-white/60 backdrop-blur-xl border border-white/60
                    shadow-[0_8px_32px_rgba(31,41,55,0.08)]">
            <div class="absolute inset-x-4 top-0 h-px bg-white/80"></div>

            <div class="flex items-center gap-2 mb-4">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <h2 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Attendance Status</h2>
            </div>

            <div class="flex items-center justify-center relative h-64">
                <canvas id="attendanceStatusChart"
                        data-labels='@json($statusLabels)'
                        data-values='@json($statusCounts)'></canvas>

                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <p class="text-2xl font-bold text-gray-800">{{ array_sum($statusCounts ?? []) }}</p>
                    <p class="text-[10px] text-gray-400 uppercase tracking-wide">Total</p>
                </div>
            </div>

            @php
                $statusTotal = array_sum($statusCounts ?? []) ?: 1;
                // Tri-tone green scale (dark -> mint -> pale) matching the reference design,
                // with warm neutrals only if there are more than 3 segments.
                $statusPalette = ['#047857', '#34d399', '#a7f3d0', '#fbbf24', '#f87171', '#818cf8'];
            @endphp

            <div class="grid grid-cols-2 gap-2 mt-4">
                @foreach(($statusLabels ?? []) as $i => $label)
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $statusPalette[$i % count($statusPalette)] }}"></span>
                        <span class="text-xs text-gray-500">{{ $label }}</span>
                        <span class="text-xs font-semibold text-gray-800 ml-auto">
                            {{ round((($statusCounts[$i] ?? 0) / $statusTotal) * 100) }}%
                        </span>
                    </div>
                @endforeach
            </div>
        </div>

    </div>

    {{-- Employees Per Branch --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mt-8">

        <div class="relative rounded-3xl overflow-hidden
                    bg-white/60 backdrop-blur-xl border border-white/60
                    shadow-[0_8px_32px_rgba(31,41,55,0.08)]">
            <div class="absolute inset-x-4 top-0 h-px bg-white/80"></div>
            <div class="p-6 border-b border-white/60">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <h2 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Employees Per Branch</h2>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-white/40 backdrop-blur-sm text-gray-400 uppercase text-xs">
                        <tr>
                            <th class="text-left px-6 py-3">Branch</th>
                            <th class="text-center px-6 py-3">Employees</th>
                            <th class="text-center px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($branches as $branch)
                            <tr class="border-b border-white/50 hover:bg-white/50">
                                <td class="px-6 py-4 font-medium text-gray-800">{{ $branch->name }}</td>
                                <td class="px-6 py-4 text-center text-gray-600">{{ $branch->employees_count }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($branch->is_active)
                                        <span class="px-2 py-1 rounded-full bg-emerald-50/80 text-emerald-600 text-xs border border-emerald-100">Active</span>
                                    @else
                                        <span class="px-2 py-1 rounded-full bg-red-50/80 text-red-500 text-xs border border-red-100">Inactive</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center py-8 text-gray-400">No branches found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Employees per Branch Chart (layered track + gradient bar, like the reference) --}}
        <div class="relative rounded-3xl p-6 overflow-hidden
                    bg-white/60 backdrop-blur-xl border border-white/60
                    shadow-[0_8px_32px_rgba(31,41,55,0.08)]">
            <div class="absolute inset-x-4 top-0 h-px bg-white/80"></div>
            <div class="flex items-center gap-2 mb-4">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <h2 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Employees Per Branch</h2>
            </div>
            <div class="relative h-64">
                <canvas id="branchChart"
                        data-labels='@json($branchLabels)'
                        data-values='@json($branchEmployeeCounts)'></canvas>
            </div>
        </div>

    </div>

    </div>{{-- /relative wrapper --}}

</div>

@endsection