@csrf

@if ($errors->any())
    <div class="mb-2 rounded-lg bg-red-50 border border-red-200 p-4">
        <ul class="list-disc ml-5 text-red-600 text-sm space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">

    <!-- Employee -->
    <div class="flex items-center gap-4 md:col-span-2">
        <label class="w-32 shrink-0 text-sm font-medium text-gray-600">Employee</label>

        <select
            name="employee_id"
            class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200"
            required>

            <option value="">Select Employee</option>

            @foreach($employees as $employee)
                <option
                    value="{{ $employee->id }}"
                    @selected(old('employee_id', $leave->employee_id ?? '') == $employee->id)>
                    {{ $employee->employee_no }} - {{ $employee->first_name }} {{ $employee->last_name }}
                </option>
            @endforeach

        </select>
    </div>

    <!-- Leave Type -->
    <div class="flex items-center gap-4">
        <label class="w-32 shrink-0 text-sm font-medium text-gray-600">Leave Type</label>

        <select
            name="leave_type"
            class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

            <option value="Vacation Leave" @selected(old('leave_type', $leave->leave_type ?? '') == 'Vacation Leave')>
                Vacation Leave
            </option>

            <option value="Sick Leave" @selected(old('leave_type', $leave->leave_type ?? '') == 'Sick Leave')>
                Sick Leave
            </option>

            <option value="Emergency Leave" @selected(old('leave_type', $leave->leave_type ?? '') == 'Emergency Leave')>
                Emergency Leave
            </option>

            <option value="Maternity Leave" @selected(old('leave_type', $leave->leave_type ?? '') == 'Maternity Leave')>
                Maternity Leave
            </option>

            <option value="Paternity Leave" @selected(old('leave_type', $leave->leave_type ?? '') == 'Paternity Leave')>
                Paternity Leave
            </option>

            <option value="Others" @selected(old('leave_type', $leave->leave_type ?? '') == 'Others')>
                Others
            </option>

        </select>
    </div>

    <!-- Start Date -->
    <div class="flex items-center gap-4">
        <label class="w-32 shrink-0 text-sm font-medium text-gray-600">Start Date</label>

        <input
            type="date"
            name="start_date"
            value="{{ old('start_date', isset($leave) ? $leave->start_date?->format('Y-m-d') : '') }}"
            class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200"
            required>
    </div>

    <!-- End Date -->
    <div class="flex items-center gap-4">
        <label class="w-32 shrink-0 text-sm font-medium text-gray-600">End Date</label>

        <input
            type="date"
            name="end_date"
            value="{{ old('end_date', isset($leave) ? $leave->end_date?->format('Y-m-d') : '') }}"
            class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200"
            required>
    </div>

    <!-- Status -->
    <div class="flex items-center gap-4">
        <label class="w-32 shrink-0 text-sm font-medium text-gray-600">Status</label>

        <select
            name="status"
            class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

            <option value="Pending" @selected(old('status', $leave->status ?? '') == 'Pending')>
                Pending
            </option>

            <option value="Approved" @selected(old('status', $leave->status ?? '') == 'Approved')>
                Approved
            </option>

            <option value="Rejected" @selected(old('status', $leave->status ?? '') == 'Rejected')>
                Rejected
            </option>

        </select>
    </div>

    <!-- Reason -->
    <div class="md:col-span-2 flex items-start gap-4">
        <label class="w-32 shrink-0 pt-2.5 text-sm font-medium text-gray-600">Reason</label>

        <textarea
            name="reason"
            rows="4"
            class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">{{ old('reason', $leave->reason ?? '') }}</textarea>
    </div>

</div>

<div class="mt-6 pt-6 border-t border-gray-100 flex gap-3">

    <button
        type="submit"
        class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-6 py-2.5 rounded-xl shadow-sm transition">
        {{ isset($leave) ? 'Update Leave Request' : 'Save Leave Request' }}
    </button>

    <a href="{{ route('leaves.index') }}"
       class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-medium px-6 py-2.5 rounded-xl transition">
        Cancel
    </a>

</div>