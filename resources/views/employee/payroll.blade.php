@extends('layouts.crm')

@section('title', '')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div>
        <h1 class="text-2xl font-semibold text-gray-800">
            My Payroll
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            View your payroll history and salary details.
        </p>
    </div>

    {{-- Payroll Table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">
                Payroll History
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-5 py-3 text-left font-medium">
                            Payroll Date
                        </th>

                        <th class="px-5 py-3 text-right font-medium">
                            Basic Salary
                        </th>

                        <th class="px-5 py-3 text-right font-medium">
                            Allowance
                        </th>

                        <th class="px-5 py-3 text-right font-medium">
                            Deduction
                        </th>

                        <th class="px-5 py-3 text-right font-medium">
                            Net Salary
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($payrolls as $payroll)

                        <tr class="hover:bg-gray-50">

                            <td class="px-5 py-4 font-medium text-gray-700">
                                {{ $payroll->payroll_date?->format('M d, Y') }}
                            </td>

                            <td class="px-5 py-4 text-right">
                                ₱{{ number_format($payroll->basic_salary, 2) }}
                            </td>

                            <td class="px-5 py-4 text-right">
                                ₱{{ number_format($payroll->allowance, 2) }}
                            </td>

                            <td class="px-5 py-4 text-right text-red-600">
                                ₱{{ number_format($payroll->deduction, 2) }}
                            </td>

                            <td class="px-5 py-4 text-right font-semibold text-gray-800">
                                ₱{{ number_format($payroll->net_salary, 2) }}
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5"
                                class="px-5 py-10 text-center text-gray-400">
                                No payroll records found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($payrolls->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $payrolls->links() }}
            </div>
        @endif

    </div>

</div>

@endsection