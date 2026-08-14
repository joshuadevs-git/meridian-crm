@extends('layouts.crm')

@section('title', 'Payroll Management')

@section('content')

<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Payroll Management</h1>
            <p class="text-sm text-gray-400 mt-1">Manage employee payroll records</p>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('payrolls.calculate') }}"
               class="inline-flex items-center gap-2 bg-white hover:bg-gray-50 text-gray-700 text-sm font-medium px-4 py-2.5 rounded-xl border border-gray-200 shadow-sm transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 7h6m-6 4h6m-6 4h4m5 5H6a2 2 0 01-2-2V6a2 2 0 012-2h7l5 5v9a2 2 0 01-2 2z"/>
                </svg>
                Calculate Payroll
            </a>

            <a href="{{ route('payrolls.create') }}"
               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl shadow-sm shadow-emerald-600/20 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add Payroll
            </a>
        </div>
    </div>

    {{-- Summary Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <span class="w-11 h-11 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-3-3.87M9 20H4v-2a4 4 0 013-3.87m6-1.13a4 4 0 10-4-4 4 4 0 004 4zm6 0a4 4 0 10-4-4"/>
                </svg>
            </span>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-400">Total Records</p>
                <p class="text-xl font-bold text-gray-800 mt-0.5">
                    {{ method_exists($payrolls, 'total') ? $payrolls->total() : $payrolls->count() }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <span class="w-11 h-11 rounded-xl bg-indigo-50 text-indigo-500 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3v-6m-3 6v-1m12-9v9a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2z"/>
                </svg>
            </span>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-400">Total Net Salary (page)</p>
                <p class="text-xl font-bold text-gray-800 mt-0.5">
                    ₱{{ number_format(collect($payrolls->items())->sum('net_salary'), 2) }}
                </p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4">
            <span class="w-11 h-11 rounded-xl bg-red-50 text-red-500 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                </svg>
            </span>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-400">Total Deductions (page)</p>
                <p class="text-xl font-bold text-gray-800 mt-0.5">
                    ₱{{ number_format(collect($payrolls->items())->sum('deduction'), 2) }}
                </p>
            </div>
        </div>

    </div>

    {{-- Payroll Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <h2 class="font-bold text-gray-800 text-sm uppercase tracking-wide">Payroll Records</h2>
        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-gray-50 text-gray-400 uppercase text-[11px] tracking-wide">
                    <tr>
                        <th class="text-left px-6 py-3 font-semibold">Employee</th>
                        <th class="text-left px-6 py-3 font-semibold">Payroll Date</th>
                        <th class="text-left px-6 py-3 font-semibold">Basic Salary</th>
                        <th class="text-left px-6 py-3 font-semibold">Allowance</th>
                        <th class="text-left px-6 py-3 font-semibold">Deduction</th>
                        <th class="text-left px-6 py-3 font-semibold">Net Salary</th>
                        <th class="text-right px-6 py-3 font-semibold">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-50">

                @forelse($payrolls as $payroll)

                    @php
                        $initials = strtoupper(substr($payroll->employee->first_name, 0, 1) . substr($payroll->employee->last_name, 0, 1));
                    @endphp

                    <tr class="hover:bg-gray-50/70 transition-colors">

                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <span class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold flex items-center justify-center shrink-0">
                                    {{ $initials }}
                                </span>
                                <span class="font-medium text-gray-800">
                                    {{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-gray-500">
                            {{ $payroll->payroll_date->format('M d, Y') }}
                        </td>

                        <td class="px-6 py-4 text-gray-600">
                            ₱{{ number_format($payroll->basic_salary, 2) }}
                        </td>

                        <td class="px-6 py-4 text-emerald-600 font-medium">
                            +₱{{ number_format($payroll->allowance, 2) }}
                        </td>

                        <td class="px-6 py-4 text-red-500 font-medium">
                            −₱{{ number_format($payroll->deduction, 2) }}
                        </td>

                        <td class="px-6 py-4 font-bold text-gray-800">
                            ₱{{ number_format($payroll->net_salary, 2) }}
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end gap-2">

                                <a href="{{ route('payrolls.edit', $payroll) }}"
                                   class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-600 text-xs font-medium rounded-lg transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>

                                <form
                                    action="{{ route('payrolls.destroy', $payroll) }}"
                                    method="POST"
                                    onsubmit="return confirm('Delete this payroll?');">

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 text-xs font-medium rounded-lg transition">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" class="text-center py-16">
                            <div class="flex flex-col items-center gap-2 text-gray-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 7h6m-6 4h6m-6 4h4m5 5H6a2 2 0 01-2-2V6a2 2 0 012-2h7l5 5v9a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-sm">No payroll records found.</p>
                            </div>
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

</div>

@endsection