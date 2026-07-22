@extends('layouts.crm')

@section('title', 'Payroll Report')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-6 border-b border-gray-100">

        <div>
            <h2 class="text-xl font-bold text-gray-800">Payroll Report</h2>
            <p class="text-sm text-gray-400 mt-1">Monthly payroll summary per employee</p>
        </div>

        <div class="flex flex-wrap gap-2">

            <a href="{{ route('reports.payroll.excel', request()->query()) }}"
               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                </svg>
                Excel
            </a>

            <a href="{{ route('reports.payroll.csv', request()->query()) }}"
               class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                </svg>
                CSV
            </a>

            <a href="{{ route('reports.payroll.pdf', request()->query()) }}"
               class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                </svg>
                PDF
            </a>

        </div>

    </div>

    {{-- Filters --}}
    <div class="p-6 border-b border-gray-100 bg-gray-50/50">

        <form method="GET" action="{{ route('reports.payroll') }}" class="grid grid-cols-1 md:grid-cols-3 gap-3">

            <input
                type="month"
                name="month"
                value="{{ $month }}"
                class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

            <select
                name="employee"
                class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

                <option value="">All Employees</option>

                @foreach($employees as $emp)
                    <option value="{{ $emp->id }}" @selected($employee == $emp->id)>
                        {{ $emp->employee_no }} - {{ $emp->first_name }} {{ $emp->last_name }}
                    </option>
                @endforeach

            </select>

            <button
                type="submit"
                class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg transition">
                Filter
            </button>

        </form>

    </div>

    <div class="overflow-x-auto">

        <table class="min-w-full text-sm">

            <thead class="bg-gray-50 text-gray-400 uppercase text-xs">
                <tr>
                    <th class="text-left px-6 py-3">Employee No</th>
                    <th class="text-left px-6 py-3">Employee Name</th>
                    <th class="text-left px-6 py-3">Branch</th>
                    <th class="text-left px-6 py-3">Payroll Date</th>
                    <th class="text-left px-6 py-3">Basic Salary</th>
                    <th class="text-left px-6 py-3">Allowance</th>
                    <th class="text-left px-6 py-3">Deduction</th>
                    <th class="text-left px-6 py-3">Net Salary</th>
                </tr>
            </thead>

            <tbody>

            @forelse($payrolls as $payroll)

                <tr class="border-b border-gray-50 hover:bg-gray-50">

                    <td class="px-6 py-4 text-gray-600 font-mono text-xs">{{ $payroll->employee->employee_no }}</td>

                    <td class="px-6 py-4 font-medium text-gray-800">
                        {{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}
                    </td>

                    <td class="px-6 py-4 text-gray-600">{{ $payroll->employee->branch->name ?? '-' }}</td>

                    <td class="px-6 py-4 text-gray-600">{{ $payroll->payroll_date }}</td>

                    <td class="px-6 py-4 text-gray-600">₱{{ number_format($payroll->basic_salary, 2) }}</td>

                    <td class="px-6 py-4 text-emerald-600">₱{{ number_format($payroll->allowance, 2) }}</td>

                    <td class="px-6 py-4 text-red-500">₱{{ number_format($payroll->deduction, 2) }}</td>

                    <td class="px-6 py-4 font-semibold text-gray-800">₱{{ number_format($payroll->net_salary, 2) }}</td>

                </tr>

            @empty

                <tr>
                    <td colspan="8" class="text-center py-12 text-gray-400">
                        No payroll records found.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    @if(method_exists($payrolls, 'links'))
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $payrolls->links() }}
        </div>
    @endif

</div>

@endsection