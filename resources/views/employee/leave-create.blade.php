@extends('layouts.crm')

@section('title', '')

@section('content')

<div class="max-w-3xl space-y-6">

    <div>
        <h1 class="text-2xl font-semibold text-gray-800">
            Request Leave
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Submit a leave request for your account.
        </p>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">

        <div class="mb-6">
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

        @if ($errors->any())
            <div class="mb-5 rounded-lg bg-red-50 border border-red-200 p-4">
                <ul class="list-disc ml-5 text-red-600 text-sm space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('employee.leaves.store') }}" method="POST">
            @csrf

            <div class="space-y-5">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Leave Type
                    </label>

                    <select
                        name="leave_type"
                        required
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm"
                    >
                        <option value="">Select Leave Type</option>
                        <option value="Vacation Leave">Vacation Leave</option>
                        <option value="Sick Leave">Sick Leave</option>
                        <option value="Emergency Leave">Emergency Leave</option>
                        <option value="Personal Leave">Personal Leave</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Start Date
                        </label>

                        <input
                            type="date"
                            name="start_date"
                            value="{{ old('start_date') }}"
                            required
                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm"
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            End Date
                        </label>

                        <input
                            type="date"
                            name="end_date"
                            value="{{ old('end_date') }}"
                            required
                            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm"
                        >
                    </div>

                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Reason
                    </label>

                    <textarea
                        name="reason"
                        rows="4"
                        class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm"
                        placeholder="Enter the reason for your leave..."
                    >{{ old('reason') }}</textarea>
                </div>

            </div>

            <div class="mt-6 pt-6 border-t border-gray-100 flex gap-3">

                <button
                    type="submit"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-6 py-2.5 rounded-xl shadow-sm transition"
                >
                    Submit Leave Request
                </button>

                <a
                    href="{{ route('my-leaves') }}"
                    class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-medium px-6 py-2.5 rounded-xl"
                >
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

@endsection