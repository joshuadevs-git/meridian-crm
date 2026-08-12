@extends('layouts.crm')

@section('title', 'My Leaves')

@section('content')

<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center justify-between">

    <div>
        <h1 class="text-2xl font-semibold text-gray-800">
            My Leaves
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            View your leave requests and their current status.
        </p>
    </div>

    <a href="{{ route('employee.leaves.create') }}"
       class="inline-flex items-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-xl shadow-sm transition">

        + Request Leave

    </a>

</div>

    {{-- Employee Information --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs uppercase tracking-wide text-gray-400">
            Employee
        </p>

        <h2 class="text-lg font-semibold text-gray-800 mt-1">
            {{ $employee->first_name }} {{ $employee->last_name }}
        </h2>

        <p class="text-sm text-gray-500">
            Employee No: {{ $employee->employee_no }}
        </p>
    </div>

    {{-- Leave Table --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

        <div class="px-5 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">
                Leave History
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-5 py-3 text-left font-medium">
                            Leave Type
                        </th>

                        <th class="px-5 py-3 text-left font-medium">
                            Start Date
                        </th>

                        <th class="px-5 py-3 text-left font-medium">
                            End Date
                        </th>

                        <th class="px-5 py-3 text-left font-medium">
                            Reason
                        </th>

                        <th class="px-5 py-3 text-left font-medium">
                            Status
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($leaves as $leave)

                        <tr class="hover:bg-gray-50">

                            <td class="px-5 py-4 font-medium text-gray-700">
                                {{ $leave->leave_type }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $leave->start_date?->format('M d, Y') }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $leave->end_date?->format('M d, Y') }}
                            </td>

                            <td class="px-5 py-4 text-gray-600">
                                {{ $leave->reason ?: '—' }}
                            </td>

                            <td class="px-5 py-4">

                                @php
                                    $statusClass = match(strtolower($leave->status)) {
                                        'approved' => 'bg-green-100 text-green-700',
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'rejected' => 'bg-red-100 text-red-700',
                                        default => 'bg-gray-100 text-gray-600',
                                    };
                                @endphp

                                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                    {{ $leave->status }}
                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="px-5 py-10 text-center text-gray-400">
                                No leave records found.
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        @if($leaves->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $leaves->links() }}
            </div>
        @endif

    </div>

</div>

@endsection