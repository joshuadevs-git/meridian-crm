@extends('layouts.crm')

@section('title', '')

@section('content')

<hr class="my-4">

<div class="min-h-screen bg-gray-50 p-6">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Dashboard</h1>
            <p class="text-sm text-gray-400 mt-1">Track, manage and forecast your employees with ease</p>
        </div>

        
    </div>

    {{-- Top Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">

        <div class="bg-emerald-600 rounded-2xl shadow-sm p-6 text-white">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-wide text-emerald-100">Total Employees</span>
                <span class="bg-white/20 rounded-lg p-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4"/>
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-bold mt-4">{{ $totalEmployees }}</p>
            <p class="text-xs text-emerald-100 mt-1">All registered staff</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-wide text-gray-400">Active Employees</span>
                <span class="bg-emerald-50 text-emerald-600 rounded-lg p-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-bold mt-4 text-gray-800">{{ $activeEmployees }}</p>
            <p class="text-xs text-gray-400 mt-1">Currently working</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-wide text-gray-400">Inactive Employees</span>
                <span class="bg-red-50 text-red-500 rounded-lg p-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-bold mt-4 text-gray-800">{{ $inactiveEmployees }}</p>
            <p class="text-xs text-gray-400 mt-1">Not currently active</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <span class="text-xs uppercase tracking-wide text-gray-400">Total Branches</span>
                <span class="bg-indigo-50 text-indigo-500 rounded-lg p-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21h18M5 21V7l8-4v18M13 21V11l6 3v7"/>
                    </svg>
                </span>
            </div>
            <p class="text-3xl font-bold mt-4 text-gray-800">{{ $totalBranches }}</p>
            <p class="text-xs text-gray-400 mt-1">Branch locations</p>
        </div>

    </div>

    {{-- Payroll Summary --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mt-6">

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-xs uppercase tracking-wide text-gray-400">Payroll Records</h3>
            <p class="text-2xl font-bold mt-2 text-gray-800">{{ $totalPayrolls }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-xs uppercase tracking-wide text-gray-400">Total Payroll Amount</h3>
            <p class="text-2xl font-bold mt-2 text-gray-800">₱{{ number_format($totalPayrollAmount, 2) }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-xs uppercase tracking-wide text-gray-400">Highest Payroll</h3>
            <p class="text-2xl font-bold mt-2 text-emerald-600">₱{{ number_format($highestPayroll, 2) }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border border-gray-100">
            <h3 class="text-xs uppercase tracking-wide text-gray-400">Lowest Payroll</h3>
            <p class="text-2xl font-bold mt-2 text-gray-800">₱{{ number_format($lowestPayroll, 2) }}</p>
        </div>

    </div>

    {{-- Middle Section: Analytics + Team + Progress --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mt-8">

        {{-- Attendance Analytics (bar-style) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 xl:col-span-1">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-gray-800">Attendance Overview</h2>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-400 uppercase">Present Today</p>
                    <p class="text-2xl font-bold text-emerald-600 mt-1">{{ $presentToday }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-400 uppercase">Late Today</p>
                    <p class="text-2xl font-bold text-amber-500 mt-1">{{ $lateToday }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-400 uppercase">Absent Today</p>
                    <p class="text-2xl font-bold text-red-500 mt-1">{{ $absentToday }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-400 uppercase">Total Records</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalAttendance }}</p>
                </div>
            </div>

            <div class="mt-6">
                <canvas id="attendanceTrendChart"
                        data-labels='@json($dailyLabels)'
                        data-values='@json($dailyCounts)'
                        height="140"></canvas>
                <p class="text-xs text-gray-400 text-center mt-2">Last 7 days attendance</p>
            </div>
        </div>

        {{-- Recent Employees (Team Collaboration style list) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 xl:col-span-1">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-gray-800">Recent Employees</h2>
                <a href="#" class="text-xs font-medium text-emerald-600 hover:underline">View all</a>
            </div>

            <ul class="divide-y divide-gray-100">
                @forelse($recentEmployees as $employee)
                    <li class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-emerald-100 text-emerald-700 flex items-center justify-center text-xs font-bold uppercase">
                                {{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-800">{{ $employee->first_name }} {{ $employee->last_name }}</p>
                                <p class="text-xs text-gray-400">{{ $employee->position }} &middot; {{ $employee->branch->name }}</p>
                            </div>
                        </div>

                        @if($employee->is_active)
                            <span class="text-xs px-2 py-1 rounded-full bg-emerald-50 text-emerald-600">Active</span>
                        @else
                            <span class="text-xs px-2 py-1 rounded-full bg-red-50 text-red-500">Inactive</span>
                        @endif
                    </li>
                @empty
                    <li class="text-center text-gray-400 text-sm py-8">No employees found.</li>
                @endforelse
            </ul>
        </div>

        {{-- Attendance Status Donut ("Project Progress" style) --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 xl:col-span-1 flex flex-col">
            <h2 class="font-bold text-gray-800 mb-4">Attendance Status</h2>

            <div class="flex-1 flex items-center justify-center">
                <canvas id="attendanceStatusChart"
                        data-labels='@json($statusLabels)'
                        data-values='@json($statusCounts)'
                        width="220" height="220"></canvas>
            </div>

            <p class="text-xs text-gray-400 text-center mt-4">Distribution of attendance statuses</p>
        </div>

    </div>

    {{-- Employees Per Branch --}}
    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mt-8">

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <h2 class="font-bold text-gray-800">Employees Per Branch</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50 text-gray-400 uppercase text-xs">
                        <tr>
                            <th class="text-left px-6 py-3">Branch</th>
                            <th class="text-center px-6 py-3">Employees</th>
                            <th class="text-center px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($branches as $branch)
                            <tr class="border-b border-gray-50 hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-800">{{ $branch->name }}</td>
                                <td class="px-6 py-4 text-center text-gray-600">{{ $branch->employees_count }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($branch->is_active)
                                        <span class="px-2 py-1 rounded-full bg-emerald-50 text-emerald-600 text-xs">Active</span>
                                    @else
                                        <span class="px-2 py-1 rounded-full bg-red-50 text-red-500 text-xs">Inactive</span>
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

        {{-- Employees per Branch Chart --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h2 class="font-bold text-gray-800 mb-4">Employees Per Branch Chart</h2>
            <canvas id="branchChart"
                    data-labels='@json($branchLabels)'
                    data-values='@json($branchEmployeeCounts)'
                    height="220"></canvas>
        </div>

    </div>

</div>

@endsection


@push('scripts')
<script>
document.querySelectorAll('.report-filter').forEach(function (input) {
    input.addEventListener('input', function () {
        document.getElementById('reportFilterForm')?.submit();
    });
    input.addEventListener('change', function () {
        document.getElementById('reportFilterForm')?.submit();
    });
});

// Chart.js rendering using data attributes set above
document.addEventListener('DOMContentLoaded', function () {
    const emerald = '#059669';
    const palette = ['#059669', '#34d399', '#a7f3d0', '#fbbf24', '#f87171', '#818cf8', '#c084fc'];

    function parseData(canvas) {
        return {
            labels: JSON.parse(canvas.dataset.labels || '[]'),
            values: JSON.parse(canvas.dataset.values || '[]'),
        };
    }

    const branchCanvas = document.getElementById('branchChart');
    if (branchCanvas && window.Chart) {
        const { labels, values } = parseData(branchCanvas);
        new Chart(branchCanvas, {
            type: 'bar',
            data: {
                labels,
                datasets: [{ label: 'Employees', data: values, backgroundColor: emerald, borderRadius: 6 }]
            },
            options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
        });
    }

    const statusCanvas = document.getElementById('attendanceStatusChart');
    if (statusCanvas && window.Chart) {
        const { labels, values } = parseData(statusCanvas);
        new Chart(statusCanvas, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{ data: values, backgroundColor: palette, borderWidth: 0 }]
            },
            options: { plugins: { legend: { position: 'bottom' } }, cutout: '70%' }
        });
    }

    const trendCanvas = document.getElementById('attendanceTrendChart');
    if (trendCanvas && window.Chart) {
        const { labels, values } = parseData(trendCanvas);
        new Chart(trendCanvas, {
            type: 'line',
            data: {
                labels,
                datasets: [{
                    label: 'Attendance',
                    data: values,
                    borderColor: emerald,
                    backgroundColor: 'rgba(5,150,105,0.1)',
                    fill: true,
                    tension: 0.4,
                }]
            },
            options: { plugins: { legend: { display: false } } }
        });
    }
});
</script>
@endpush