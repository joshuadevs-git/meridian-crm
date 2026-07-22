@extends('layouts.crm')

@section('title', 'Leave Management')

@section('content')


<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold">
            Leave Management
        </h2>

        <a href="{{ route('leaves.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">

            + Add Leave

        </a>

    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">

    <div class="bg-white shadow rounded-lg p-5">
        <h3 class="text-gray-500 text-sm">Total Leaves</h3>
        <p class="text-3xl font-bold">
            {{ $totalLeaves }}
        </p>
    </div>

    <div class="bg-yellow-50 shadow rounded-lg p-5">
        <h3 class="text-yellow-700 text-sm">Pending</h3>
        <p class="text-3xl font-bold">
            {{ $pendingLeaves }}
        </p>
    </div>

    <div class="bg-green-50 shadow rounded-lg p-5">
        <h3 class="text-green-700 text-sm">Approved</h3>
        <p class="text-3xl font-bold">
            {{ $approvedLeaves }}
        </p>
    </div>

    <div class="bg-red-50 shadow rounded-lg p-5">
        <h3 class="text-red-700 text-sm">Rejected</h3>
        <p class="text-3xl font-bold">
            {{ $rejectedLeaves }}
        </p>
    </div>

</div>

    <table class="min-w-full">

        <thead class="border-b">

        <tr>
            <th class="text-left py-3">Employee</th>
            <th class="text-left py-3">Branch</th>
            <th class="text-left py-3">Type</th>
            <th class="text-left py-3">Start Date</th>
            <th class="text-left py-3">End Date</th>
            <th class="text-left py-3">Status</th>
            <th>Actions</th>
        </tr>

        </thead>

        <tbody>

        @forelse($leaves as $leave)

        <tr class="border-b">

            <td>
                {{ $leave->employee->first_name }}
                {{ $leave->employee->last_name }}
            </td>

            <td>
                {{ $leave->employee->branch->name }}
            </td>

            <td>{{ $leave->leave_type }}</td>

            <td>{{ $leave->start_date->format('M d, Y') }}</td>

            <td>{{ $leave->end_date->format('M d, Y') }}</td>

            <td>{{ $leave->status }}</td>

            <td class="space-x-2">

    <a
        href="{{ route('leaves.edit', $leave) }}"
        class="text-blue-600">

        Edit

    </a>

    <form
        action="{{ route('leaves.destroy', $leave) }}"
        method="POST"
        class="inline">

        @csrf
        @method('DELETE')

        <button
            onclick="return confirm('Delete leave request?')"
            class="text-red-600">

            Delete

        </button>

    </form>

</td>

        </tr>

        @empty

        <tr>

            <td colspan="6" class="text-center py-8">
                No leave requests found.
            </td>

        </tr>

        

        @endforelse

        </tbody>

    </table>

    <div class="mt-6">
        {{ $leaves->links() }}
    </div>

</div>

@endsection