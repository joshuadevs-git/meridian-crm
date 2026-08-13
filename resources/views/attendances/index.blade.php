@extends('layouts.crm')

@section('title', '')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100">

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-6 border-b border-gray-100">

        <div>
            <h2 class="text-xl font-bold text-gray-800">Attendance Management</h2>
            <p class="text-sm text-gray-400 mt-1">Track and manage employee attendance records</p>
        </div>

        <a href="{{ route('attendances.create') }}"
           class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-small px-4 py-2.5 rounded-xl shadow-sm transition shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Attendance
        </a>

    </div>

    {{-- Filters --}}
    <div class="p-6 border-b border-gray-100 bg-gray-50/50">

        <form id="filterForm" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">

            <input
                type="text"
                name="search"
                placeholder="Search employee..."
                value="{{ request('search') }}"
                class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

            <select
                name="branch"
                class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

                <option value="">All Branches</option>

                @foreach($branches as $item)
                    <option value="{{ $item->id }}" @selected(request('branch') == $item->id)>
                        {{ $item->name }}
                    </option>
                @endforeach

            </select>

            <select
                name="status"
                class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

                <option value="">All Status</option>

                @foreach(['Present', 'Late', 'Absent', 'Leave'] as $item)
                    <option value="{{ $item }}" @selected(request('status') == $item)>
                        {{ $item }}
                    </option>
                @endforeach

            </select>

            <input
                type="date"
                name="date_from"
                value="{{ request('date_from') }}"
                class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

            <input
                type="date"
                name="date_to"
                value="{{ request('date_to') }}"
                class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

        </form>

    </div>

    <div class="overflow-x-auto">

        <table class="min-w-full text-sm">

            <thead class="bg-gray-50 text-gray-400 uppercase text-xs">
                <tr>
                    <th class="text-left px-6 py-3">Employee</th>
                    <th class="text-left px-6 py-3">Branch</th>
                    <th class="text-left px-6 py-3">Date</th>
                    <th class="text-left px-6 py-3">Time In</th>
                    <th class="text-left px-6 py-3">Time Out</th>
                    <th class="text-left px-6 py-3">Status</th>
                    <th class="text-right px-6 py-3">Actions</th>
                </tr>
            </thead>

            <tbody>

            @forelse($attendances as $attendance)

                <tr class="border-b border-gray-50 hover:bg-gray-50">

                    <td class="px-6 py-4 font-medium text-gray-800">
                        {{ $attendance->employee->first_name }} {{ $attendance->employee->last_name }}
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        {{ $attendance->employee->branch->name }}
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        {{ $attendance->attendance_date->format('M d, Y') }}
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        {{ $attendance->time_in ?? '-' }}
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        {{ $attendance->time_out ?? '-' }}
                    </td>

                    <td class="px-6 py-4">
                        @php
                            $statusStyles = [
                                'Present' => 'bg-emerald-50 text-emerald-600',
                                'Late' => 'bg-amber-50 text-amber-600',
                                'Absent' => 'bg-red-50 text-red-500',
                                'Leave' => 'bg-indigo-50 text-indigo-500',
                            ];
                            $style = $statusStyles[$attendance->status] ?? 'bg-gray-100 text-gray-600';
                        @endphp

                        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $style }}">
                            {{ $attendance->status }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">

                            <a href="{{ route('attendances.edit', $attendance) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-600 text-xs font-medium rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>

                            <form action="{{ route('attendances.destroy', $attendance) }}"
                                  method="POST"
                                  onsubmit="return confirm('Are you sure you want to delete this attendance?');">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
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
                        No attendance records found.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    <div class="px-6 py-4 border-t border-gray-100">
        {{ $attendances->links() }}
    </div>

</div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('filterForm');

    form.querySelectorAll('input, select').forEach(field => {
        field.addEventListener('input', () => form.submit());
        field.addEventListener('change', () => form.submit());
    });
});
</script>
@endpush