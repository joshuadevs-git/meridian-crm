@csrf

@if ($errors->any())
    <div class="mb-4 bg-red-100 border border-red-400 p-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<select name="employee_id" class="w-full border rounded-lg px-3 py-2" required>

    <option value="">Select Employee</option>

    @foreach($employees as $employee)

        <option
            value="{{ $employee->id }}"
            {{ old('employee_id', $leave?->employee_id) == $employee->id ? 'selected' : '' }}>

            {{ $employee->employee_no }}
            -
            {{ $employee->first_name }}
            {{ $employee->last_name }}

        </option>

    @endforeach

</select>

    <div>
        <label class="block mb-2 font-medium">Leave Type</label>

        <select
            name="leave_type"
            class="w-full border rounded-lg px-3 py-2">

            @foreach([
                'Vacation Leave',
                'Sick Leave',
                'Emergency Leave',
                'Maternity Leave',
                'Paternity Leave',
                'Others'
            ] as $type)

                <option
                    value="{{ $type }}"
                    @selected(old('leave_type', $leave->leave_type ?? '') == $type)>

                    {{ $type }}

                </option>

            @endforeach

        </select>
    </div>

    <div>
        <label class="block mb-2 font-medium">Start Date</label>

        <input
            type="date"
            name="start_date"
            value="{{ old('start_date', isset($leave) ? $leave->start_date->format('Y-m-d') : '') }}"
            class="w-full border rounded-lg px-3 py-2"
            required>
    </div>

    <div>
        <label class="block mb-2 font-medium">End Date</label>

        <input
            type="date"
            name="end_date"
            value="{{ old('end_date', isset($leave) ? $leave->end_date->format('Y-m-d') : '') }}"
            class="w-full border rounded-lg px-3 py-2"
            required>
    </div>

    <div>
        <label class="block mb-2 font-medium">Status</label>

        <select
            name="status"
            class="w-full border rounded-lg px-3 py-2">

            @foreach(['Pending', 'Approved', 'Rejected'] as $status)

                <option
                    value="{{ $status }}"
                    @selected(old('status', $leave->status ?? 'Pending') == $status)>

                    {{ $status }}

                </option>

            @endforeach

        </select>
    </div>

    <div class="md:col-span-2">

        <label class="block mb-2 font-medium">Reason</label>

        <textarea
            name="reason"
            rows="3"
            class="w-full border rounded-lg px-3 py-2">{{ old('reason', $leave->reason ?? '') }}</textarea>

    </div>

    <div class="mt-6">

    <button
        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">

        Save Leave Request

    </button>

</div>

</div>
