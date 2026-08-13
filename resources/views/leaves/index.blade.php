@extends('layouts.crm')

@section('title', '')

@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100">

    <div class="flex justify-between items-center p-6 border-b border-gray-100">

        <div>
            <h2 class="text-xl font-bold text-gray-800">Leave Management</h2>
            <p class="text-sm text-gray-400 mt-1">Review and manage employee leave requests</p>
        </div>

        <a href="{{ route('leaves.create') }}"
           class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-small px-4 py-2.5 rounded-xl shadow-sm transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Leave
        </a>

    </div>

   {{-- Filters --}}
<div class="p-6 border-b border-gray-100 bg-gray-50/50">

    <form
        id="filterForm"
        method="GET"
        action="{{ route('leaves.index') }}">

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">

            {{-- Search --}}
            <input
                id="searchInput"
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Search employee..."
                class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

            {{-- Branch --}}
            <select
                id="branchSelect"
                name="branch"
                class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

                <option value="">All Branches</option>

                @foreach($branches as $item)
                    <option
                        value="{{ $item->id }}"
                        {{ $branch == $item->id ? 'selected' : '' }}>
                        {{ $item->name }}
                    </option>
                @endforeach

            </select>

            {{-- Leave Type --}}
            <select
                id="leaveTypeSelect"
                name="leave_type"
                class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

                <option value="">All Leave Types</option>

                @foreach($leaveTypes as $type)
                    <option
                        value="{{ $type }}"
                        {{ $leaveType == $type ? 'selected' : '' }}>
                        {{ $type }}
                    </option>
                @endforeach

            </select>

            {{-- Status --}}
            <select
                id="statusSelect"
                name="status"
                class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

                <option value="">All Status</option>

                <option
                    value="Pending"
                    {{ $status == 'Pending' ? 'selected' : '' }}>
                    Pending
                </option>

                <option
                    value="Approved"
                    {{ $status == 'Approved' ? 'selected' : '' }}>
                    Approved
                </option>

                <option
                    value="Rejected"
                    {{ $status == 'Rejected' ? 'selected' : '' }}>
                    Rejected
                </option>

            </select>

            {{-- Sort --}}
            <select
                id="sortSelect"
                name="sort"
                class="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

                <option value="latest" {{ $sort == 'latest' ? 'selected' : '' }}>
                    Newest First
                </option>

                <option value="oldest" {{ $sort == 'oldest' ? 'selected' : '' }}>
                    Oldest First
                </option>

                <option value="start_new" {{ $sort == 'start_new' ? 'selected' : '' }}>
                    Start Date (Newest)
                </option>

                <option value="start_old" {{ $sort == 'start_old' ? 'selected' : '' }}>
                    Start Date (Oldest)
                </option>

                <option value="end_new" {{ $sort == 'end_new' ? 'selected' : '' }}>
                    End Date (Newest)
                </option>

                <option value="end_old" {{ $sort == 'end_old' ? 'selected' : '' }}>
                    End Date (Oldest)
                </option>

            </select>

            {{-- Clear --}}
            <a
                href="{{ route('leaves.index') }}"
                class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium rounded-lg flex items-center justify-center transition">
                Clear Filters
            </a>

        </div>

    </form>

</div>

    <div class="overflow-x-auto">

        <table class="min-w-full text-sm">

            <thead class="bg-gray-50 text-gray-400 uppercase text-xs">
                <tr>
                    <th class="text-left px-6 py-3">Employee</th>
                    <th class="text-left px-6 py-3">Branch</th>
                    <th class="text-left px-6 py-3">Type</th>
                    <th class="text-left px-6 py-3">Start Date</th>
                    <th class="text-left px-6 py-3">End Date</th>
                    <th class="px-5 py-3 text-left font-medium">Reason</th>
                    <th class="text-left px-6 py-3">Status</th>
                    <th class="text-right px-6 py-3">Actions</th>
                </tr>
            </thead>

            <tbody>

            @forelse($leaves as $leave)

                <tr class="border-b border-gray-50 hover:bg-gray-50">

                    <td class="px-6 py-4 font-medium text-gray-800">
                        {{ $leave->employee->first_name }} {{ $leave->employee->last_name }}
                    </td>

                    <td class="px-6 py-4 text-gray-600">
                        {{ $leave->employee->branch->name }}
                    </td>

                    <td class="px-6 py-4 text-gray-600">{{ $leave->leave_type }}</td>

                    <td class="px-6 py-4 text-gray-600">{{ $leave->start_date->format('M d, Y') }}</td>

                    <td class="px-6 py-4 text-gray-600">{{ $leave->end_date->format('M d, Y') }}</td>

                    <td class="px-5 py-4 text-gray-600">{{ $leave->reason ?: '—' }} </td>

                    <td class="px-6 py-4">
                        @php
                            $statusStyles = [
                                'Pending' => 'bg-amber-50 text-amber-600',
                                'Approved' => 'bg-emerald-50 text-emerald-600',
                                'Rejected' => 'bg-red-50 text-red-500',
                            ];
                            $style = $statusStyles[$leave->status] ?? 'bg-gray-100 text-gray-600';
                        @endphp

                        <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $style }}">
                            {{ $leave->status }}
                        </span>
                    </td>

                    <td class="px-6 py-4">
                        <div class="flex items-center justify-end gap-2">

                            <a href="{{ route('leaves.edit', $leave) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-50 hover:bg-gray-100 text-gray-600 text-xs font-medium rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>

                            <form
                                action="{{ route('leaves.destroy', $leave) }}"
                                method="POST"
                                class="inline"
                                onsubmit="return confirm('Delete leave request?');">

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
                        No leave requests found.
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    @if(method_exists($leaves, 'links'))
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $leaves->links() }}
        </div>
    @endif

</div>

@endsection


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        const form = document.getElementById('filterForm');

        const filters = [
            'searchInput',
            'branchSelect',
            'leaveTypeSelect',
            'statusSelect',
            'sortSelect'
        ];

        filters.forEach(function (id) {

            const element = document.getElementById(id);

            if (!element) {
                return;
            }

            element.addEventListener('change', function () {
                form.submit();
            });

        });

        const searchInput = document.getElementById('searchInput');

        if (searchInput) {
            let timeout;

            searchInput.addEventListener('input', function () {

                clearTimeout(timeout);

                timeout = setTimeout(function () {
                    form.submit();
                }, 500);

            });
        }

    });
</script>
@endpush