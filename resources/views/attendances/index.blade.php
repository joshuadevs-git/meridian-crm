@extends('layouts.crm')

@section('title', 'Attendance Management')

@section('content')

<div class="bg-white rounded-xl shadow p-6">

    <div class="flex justify-between items-center mb-6">

        <h2 class="text-2xl font-bold">
            Attendance Management
        </h2>

        <form id="filterForm" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">

    <input
        type="text"
        name="search"
        placeholder="Search employee..."
        value="{{ request('search') }}"
        class="border rounded-lg px-3 py-2">

    <select
        name="branch"
        class="border rounded-lg px-3 py-2">

        <option value="">All Branches</option>

        @foreach($branches as $item)

            <option
                value="{{ $item->id }}"
                @selected(request('branch') == $item->id)>

                {{ $item->name }}

            </option>

        @endforeach

    </select>

    <select
        name="status"
        class="border rounded-lg px-3 py-2">

        <option value="">All Status</option>

        @foreach(['Present','Late','Absent','Leave'] as $item)

            <option
                value="{{ $item }}"
                @selected(request('status') == $item)>

                {{ $item }}

            </option>

        @endforeach

    </select>

    <input
        type="date"
        name="date_from"
        value="{{ request('date_from') }}"
        class="border rounded-lg px-3 py-2">

    <input
        type="date"
        name="date_to"
        value="{{ request('date_to') }}"
        class="border rounded-lg px-3 py-2">

</form>

        <a href="{{ route('attendances.create') }}"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">

            + Add Attendance

        </a>

    </div>

    <table class="min-w-full">

        <thead class="border-b">

            <tr>

                <th class="text-left py-3">Employee</th>
                <th class="text-left py-3">Branch</th>
                <th class="text-left py-3">Date</th>
                <th class="text-left py-3">Time In</th>
                <th class="text-left py-3">Time Out</th>
                <th class="text-left py-3">Status</th>
                <th class="text-left py-3">Actions</th>

            </tr>

        </thead>

        <tbody>

        @forelse($attendances as $attendance)

            <tr class="border-b">

                <td>
                    {{ $attendance->employee->first_name }}
                    {{ $attendance->employee->last_name }}
                </td>

                <td>
                    {{ $attendance->employee->branch->name }}
                </td>

                <td>
                    {{ $attendance->attendance_date->format('M d, Y') }}
                </td>

                <td>
                    {{ $attendance->time_in ?? '-' }}
                </td>

                <td>
                    {{ $attendance->time_out ?? '-' }}
                </td>

                <td>
                    {{ $attendance->status }}
                </td>

               <td class="py-3">

    <td class="py-4">
    <div class="flex items-center gap-4">
        <a href="{{ route('attendances.edit', $attendance) }}"
           class="text-blue-600 hover:underline">
            Edit
        </a>

        <form action="{{ route('attendances.destroy', $attendance) }}"
              method="POST"
              onsubmit="return confirm('Are you sure you want to delete this attendance?')">
            @csrf
            @method('DELETE')

            <button type="submit"
                    class="text-red-600 hover:underline">
                Delete
            </button>
        </form>
    </div>
</td>

            </tr>

        @empty

            <tr>

                <td colspan="7" class="text-center py-8">

                    No attendance records found.

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

    <div class="mt-6">
        {{ $attendances->links() }}
    </div>

</div>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('filterForm');

    form.querySelectorAll('input, select').forEach(field => {

        field.addEventListener('input', () => {

            form.submit();

        });

        field.addEventListener('change', () => {

            form.submit();

        });

    });

});
</script>
@endpush