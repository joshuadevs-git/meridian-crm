@extends('layouts.crm')

@section('title', 'Payroll Management')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100">

    <div class="flex justify-between items-center p-6 border-b border-gray-100">

        <div>
            <h2 class="text-xl font-bold text-gray-800">Payroll Management</h2>
            <p class="text-sm text-gray-400 mt-1">Manage employee payroll records</p>
        </div>

        <a href="{{ route('payrolls.create') }}"
           class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Payroll
        </a>

    </div>

    <div class="overflow-x-auto">

        <table class="min-w-full text-sm">

            <thead class="bg-gray-50 text-gray-400 uppercase text-xs">
                <tr>
                    <th class="text-left px-6 py-3">Employee</th>
                    <th class="text-left px-6 py-3">Payroll Date</th>
                    <th class="text-left px-6 py-3">Basic Salary</th>
                    <th class="text-left px-6 py-3">Allowance</th>
                    <th class="text-left px-6 py-3">Deduction</th>
                    <th class="text-left px-6 py-3">Net Salary</th>
                    <th class="text-right px-6 py-3">Actions</th>
                </tr>
            </thead>

            <tbody>

            @forelse($payrolls as $payroll)

                <tr class="border-b border-gray-50 hover:bg-gray-50">

                    <td class="px-6 py-4 font-medium text-gray-800">
                        {{ $payroll->employee->first_name }} {{ $payroll->employee->last_name }}
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        {{ $payroll->payroll_date->format('Y-m-d') }}
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        ₱{{ number_format($payroll->basic_salary, 2) }}
                    </td>

                    <td class="px-6 py-4 text-emerald-600">
                        ₱{{ number_format($payroll->allowance, 2) }}
                    </td>

                    <td class="px-6 py-4 text-red-500">
                        ₱{{ number_format($payroll->deduction, 2) }}
                    </td>

                    <td class="px-6 py-4 font-semibold text-gray-800">
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
                    <td colspan="7" class="text-center py-12 text-gray-400">
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