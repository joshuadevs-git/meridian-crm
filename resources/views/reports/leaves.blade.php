@extends('layouts.crm')

@section('title', 'Leave Report')

@section('content')

<div class="p-6">

    <h2 class="text-2xl font-bold mb-6">
   
    </h2>

    <form method="GET" action="{{ route('reports.leaves') }}" class="mb-6">

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

        <div>
            <input
                type="month"
                name="month"
                value="{{ $month }}"
                class="w-full border rounded-lg px-3 py-2">
        </div>

        <div>
            <select
                name="status"
                class="w-full border rounded-lg px-3 py-2">

                <option value="">All Status</option>

                <option value="Pending"
                    {{ $status == 'Pending' ? 'selected' : '' }}>
                    Pending
                </option>

                <option value="Approved"
                    {{ $status == 'Approved' ? 'selected' : '' }}>
                    Approved
                </option>

                <option value="Rejected"
                    {{ $status == 'Rejected' ? 'selected' : '' }}>
                    Rejected
                </option>

            </select>
        </div>

        <div>
            <input
                type="text"
                name="search"
                value="{{ $search }}"
                placeholder="Search employee..."
                class="w-full border rounded-lg px-3 py-2">
        </div>

        <div>
            <button
                class="bg-blue-600 text-white px-4 py-2 rounded-lg">

                Filter

            </button>
        </div>

    </div>

</form>


<div class="mb-4 flex gap-2">

    <a href="{{ route('reports.leaves.excel', request()->query()) }}"
       class="bg-green-600 text-white px-4 py-2 rounded">
        Excel
    </a>

    <a href="{{ route('reports.leaves.csv', request()->query()) }}"
       class="bg-blue-600 text-white px-4 py-2 rounded">
        CSV
    </a>

    <a href="{{ route('reports.leaves.pdf', request()->query()) }}"
       class="bg-red-600 text-white px-4 py-2 rounded">
        PDF
    </a>

</div>

    <table class="w-full border">

        <thead>
            <tr>
                <th>Employee</th>
                <th>Leave Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
            </tr>
        </thead>

        <tbody>

            @foreach($leaveReport as $leave)

                <tr>
                    <td>
                        {{ $leave->employee->first_name }}
                        {{ $leave->employee->last_name }}
                    </td>

                    <td>{{ $leave->leave_type }}</td>
                    <td>{{ $leave->start_date }}</td>
                    <td>{{ $leave->end_date }}</td>
                    <td>{{ $leave->status }}</td>
                </tr>

            @endforeach

        </tbody>

    </table>

    <div class="mt-4">
        {{ $leaveReport->links() }}
    </div>

</div>

@endsection