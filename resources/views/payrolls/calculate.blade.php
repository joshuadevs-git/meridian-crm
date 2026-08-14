@extends('layouts.crm')

@section('title', 'Payroll Calculation')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <div>
            <h2 class="text-xl font-bold text-gray-800">
                Payroll Calculation
            </h2>

            <p class="text-sm text-gray-400 mt-1">
                Automatically calculate payroll based on salary, attendance,
                schedules, late time, absences, breaks, and approved leave.
            </p>
        </div>

        <a href="{{ route('payrolls.index') }}"
           class="inline-flex items-center justify-center gap-2
                  bg-white border border-gray-200
                  hover:bg-gray-50
                  text-gray-600 text-sm font-medium
                  px-4 py-2.5 rounded-xl transition">

            <svg class="w-4 h-4"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">

                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M10 19l-7-7m0 0l7-7m-7 7h18"/>

            </svg>

            Back to Payroll
        </a>

    </div>


    {{-- Month Selector --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <form method="GET"
              action="{{ route('payrolls.calculate') }}">

            <div class="flex flex-col sm:flex-row sm:items-end gap-4">

                <div class="flex-1 max-w-xs">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Payroll Month
                    </label>

                    <input
                        type="month"
                        name="month"
                        value="{{ $month }}"
                        class="w-full rounded-xl border border-gray-200
                               bg-white px-4 py-2.5 text-sm
                               focus:outline-none
                               focus:ring-2 focus:ring-emerald-200
                               focus:border-emerald-400">

                </div>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2
                           bg-emerald-600 hover:bg-emerald-700
                           text-white text-sm font-medium
                           px-5 py-2.5 rounded-xl
                           shadow-sm transition">

                    <svg class="w-4 h-4"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 4v5h.582m15.356 2A8.001
                                 8.001 0 004.582 9m0 0H9m11 11v-5h-.581
                                 a8.003 8.003 0 01-15.357-2m15.357 2H15"/>

                    </svg>

                    Calculate Payroll
                </button>

            </div>

        </form>

        <div class="mt-4 text-sm text-gray-400">

            Period:

            <span class="font-medium text-gray-600">
                {{ $startDate->format('M d, Y') }}
                -
                {{ $endDate->format('M d, Y') }}
            </span>

        </div>

    </div>


    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">

        {{-- Employees --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">

            <p class="text-xs uppercase tracking-wide text-gray-400">
                Employees
            </p>

            <p class="text-2xl font-bold text-gray-800 mt-1">
                {{ $calculations->count() }}
            </p>

        </div>


        {{-- Basic Salary --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">

            <p class="text-xs uppercase tracking-wide text-gray-400">
                Basic Salary
            </p>

            <p class="text-2xl font-bold text-gray-800 mt-1">
                ₱{{ number_format($totalBasicSalary, 2) }}
            </p>

        </div>


        {{-- Deductions --}}
        <div class="bg-red-50 rounded-2xl border border-red-100 shadow-sm p-5">

            <p class="text-xs uppercase tracking-wide text-red-500">
                Total Deductions
            </p>

            <p class="text-2xl font-bold text-red-600 mt-1">
                ₱{{ number_format($totalDeductions, 2) }}
            </p>

        </div>


        {{-- Late --}}
        <div class="bg-amber-50 rounded-2xl border border-amber-100 shadow-sm p-5">

            <p class="text-xs uppercase tracking-wide text-amber-600">
                Late Minutes
            </p>

            <p class="text-2xl font-bold text-amber-700 mt-1">
                {{ number_format($totalLateMinutes) }}
            </p>

        </div>


        {{-- Net --}}
        <div class="bg-emerald-50 rounded-2xl border border-emerald-100 shadow-sm p-5">

            <p class="text-xs uppercase tracking-wide text-emerald-600">
                Total Net Salary
            </p>

            <p class="text-2xl font-bold text-emerald-700 mt-1">
                ₱{{ number_format($totalNetSalary, 2) }}
            </p>

        </div>

    </div>


    {{-- Payroll Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100">

        <div class="p-6 border-b border-gray-100">

            <h3 class="text-lg font-bold text-gray-800">
                Payroll Preview
            </h3>

            <p class="text-sm text-gray-400 mt-1">
                Review the automatic calculations before generating payroll.
            </p>

        </div>


        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-gray-50 text-gray-400 uppercase text-xs">

                    <tr>

                        <th class="text-left px-5 py-3">
                            Employee
                        </th>

                        <th class="text-left px-5 py-3">
                            Basic Salary
                        </th>

                        <th class="text-left px-5 py-3">
                            Working Days
                        </th>

                        <th class="text-left px-5 py-3">
                            Late
                        </th>

                        <th class="text-left px-5 py-3">
                            Absent
                        </th>

                        <th class="text-left px-5 py-3">
                            Leave
                        </th>

                        <th class="text-left px-5 py-3">
                            Break
                        </th>

                        <th class="text-left px-5 py-3">
                            Deduction
                        </th>

                        <th class="text-right px-5 py-3">
                            Net Salary
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse($calculations as $item)

                    @php
                        $employee = $item['employee'];
                        $calc = $item['calculation'];
                    @endphp

                    <tr class="border-b border-gray-50 hover:bg-gray-50">

                        {{-- Employee --}}
                        <td class="px-5 py-4">

                            <div class="font-medium text-gray-800">

                                {{ $employee->first_name }}
                                {{ $employee->last_name }}

                            </div>

                            <div class="text-xs text-gray-400 mt-0.5">

                                {{ $employee->employee_no }}

                            </div>

                        </td>


                        {{-- Basic --}}
                        <td class="px-5 py-4 text-gray-600">

                            ₱{{ number_format($calc['basic_salary'], 2) }}

                        </td>


                        {{-- Working Days --}}
                        <td class="px-5 py-4 text-gray-600">

                            {{ $calc['working_days'] }}

                        </td>


                        {{-- Late --}}
                        <td class="px-5 py-4">

                            @if($calc['late_minutes'] > 0)

                                <span class="text-amber-600 font-medium">

                                    {{ $calc['late_minutes'] }} min

                                </span>

                                <div class="text-xs text-gray-400">

                                    -₱{{ number_format($calc['late_deduction'], 2) }}

                                </div>

                            @else

                                <span class="text-gray-400">
                                    —
                                </span>

                            @endif

                        </td>


                        {{-- Absent --}}
                        <td class="px-5 py-4">

                            @if($calc['absent_days'] > 0)

                                <span class="text-red-600 font-medium">

                                    {{ $calc['absent_days'] }}
                                    {{ $calc['absent_days'] == 1 ? 'day' : 'days' }}

                                </span>

                                <div class="text-xs text-gray-400">

                                    -₱{{ number_format($calc['absence_deduction'], 2) }}

                                </div>

                            @else

                                <span class="text-gray-400">
                                    —
                                </span>

                            @endif

                        </td>


                        {{-- Leave --}}
                        <td class="px-5 py-4">

                            <span class="text-emerald-600 font-medium">

                                Approved

                            </span>

                        </td>


                        {{-- Break --}}
                        <td class="px-5 py-4">

                            @if($calc['excess_break_minutes'] > 0)

                                <span class="text-red-600 font-medium">

                                    {{ $calc['excess_break_minutes'] }} min

                                </span>

                                <div class="text-xs text-gray-400">

                                    -₱{{ number_format($calc['break_deduction'], 2) }}

                                </div>

                            @else

                                <span class="text-gray-400">
                                    0 min
                                </span>

                            @endif

                        </td>


                        {{-- Total Deduction --}}
                        <td class="px-5 py-4">

                            <span class="font-medium text-red-600">

                                ₱{{ number_format($calc['total_deduction'], 2) }}

                            </span>

                        </td>


                        {{-- Net Salary --}}
                        <td class="px-5 py-4 text-right">

                            <span class="font-bold text-emerald-700">

                                ₱{{ number_format($calc['net_salary'], 2) }}

                            </span>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="9"
                            class="text-center py-12 text-gray-400">

                            No active employees found.

                        </td>

                    </tr>

                @endforelse

                </tbody>


                {{-- Totals --}}
                @if($calculations->count())

                <tfoot class="bg-gray-50">

                    <tr>

                        <td class="px-5 py-4 font-bold text-gray-800">
                            TOTAL
                        </td>

                        <td class="px-5 py-4 font-bold text-gray-800">
                            ₱{{ number_format($totalBasicSalary, 2) }}
                        </td>

                        <td></td>

                        <td></td>

                        <td class="px-5 py-4 font-medium text-red-600">
                            {{ $totalAbsentDays }} days
                        </td>

                        <td></td>

                        <td></td>

                        <td class="px-5 py-4 font-bold text-red-600">
                            ₱{{ number_format($totalDeductions, 2) }}
                        </td>

                        <td class="px-5 py-4 text-right font-bold text-emerald-700">
                            ₱{{ number_format($totalNetSalary, 2) }}
                        </td>

                    </tr>

                </tfoot>

                @endif

            </table>


            @if($calculations->count() > 0)

    <form method="POST" action="{{ route('payrolls.calculate.save') }}">
        @csrf

        <input
            type="hidden"
            name="period_start"
            value="{{ $startDate->toDateString() }}"
        >

        <input
            type="hidden"
            name="period_end"
            value="{{ $endDate->toDateString() }}"
        >

        <div class="mt-6 bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                <div>
                    <h3 class="text-sm font-semibold text-gray-800">
                        Payroll Review
                    </h3>

                    <p class="text-xs text-gray-400 mt-1">
                        Review the calculated payroll before saving.
                    </p>
                </div>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2
                           bg-emerald-600 hover:bg-emerald-700
                           text-white text-sm font-semibold
                           px-6 py-2.5 rounded-xl
                           shadow-sm transition"
                >
                    <svg
                        class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>

                    Save Payroll
                </button>

            </div>

        </div>

    </form>

@endif


        </div>


        {{-- Footer --}}
        @if($calculations->count())

        <div class="p-6 border-t border-gray-100 flex justify-end">

            <button
                type="button"
                disabled
                class="inline-flex items-center gap-2
                       bg-gray-300 text-white
                       text-sm font-medium
                       px-5 py-2.5 rounded-xl
                       cursor-not-allowed">

                Generate Payroll

            </button>

        </div>

        <div class="px-6 pb-6 text-right">

            <p class="text-xs text-gray-400">
                Payroll generation will be enabled after reviewing the calculation.
            </p>

        </div>

        @endif

    </div>

</div>

@endsection