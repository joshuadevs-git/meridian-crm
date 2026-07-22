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

<div class="flex items-start gap-4">
    <label class="w-36 shrink-0 pt-2.5 text-sm font-medium text-gray-600">Employee</label>

    <div class="flex-1">
        <select
            name="employee_id"
            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200"
            required>

            <option value="">Select Employee</option>

            @foreach($employees as $employee)
                <option
                    value="{{ $employee->id }}"
                    @selected(old('employee_id', $attendance->employee_id ?? '') == $employee->id)>
                    {{ $employee->employee_no }} - {{ $employee->first_name }} {{ $employee->last_name }}
                </option>
            @endforeach

        </select>

        @error('employee_id')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="flex items-start gap-4">
    <label class="w-36 shrink-0 pt-2.5 text-sm font-medium text-gray-600">Attendance Date</label>

    <div class="flex-1">
        <input
            type="date"
            name="attendance_date"
            value="{{ old('attendance_date', isset($attendance) ? $attendance->attendance_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200"
            required>

        @error('attendance_date')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="flex items-start gap-4">
    <label class="w-36 shrink-0 pt-2.5 text-sm font-medium text-gray-600">Time In</label>

    <div class="flex-1">
        <input
            type="time"
            name="time_in"
            value="{{ old('time_in', isset($attendance) && $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('H:i') : '') }}"
            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

        @error('time_in')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="flex items-start gap-4">
    <label class="w-36 shrink-0 pt-2.5 text-sm font-medium text-gray-600">Time Out</label>

    <div class="flex-1">
        <input
            type="time"
            name="time_out"
            value="{{ old('time_out', isset($attendance) && $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('H:i') : '') }}"
            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

        @error('time_out')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="flex items-start gap-4">
    <label class="w-36 shrink-0 pt-2.5 text-sm font-medium text-gray-600">Status</label>

    <div class="flex-1">
        <select
            name="status"
            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

            @foreach(['Present', 'Late', 'Absent', 'Leave'] as $status)
                <option value="{{ $status }}" @selected(old('status', $attendance->status ?? 'Present') == $status)>
                    {{ $status }}
                </option>
            @endforeach

        </select>

        @error('status')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="flex items-start gap-4">
    <label class="w-36 shrink-0 pt-2.5 text-sm font-medium text-gray-600">Remarks</label>

    <div class="flex-1">
        <textarea
            name="remarks"
            rows="3"
            class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">{{ old('remarks', $attendance->remarks ?? '') }}</textarea>

        @error('remarks')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="flex items-center gap-4 pt-2">
    <div class="w-36 shrink-0"></div>

    <div class="flex gap-3">
        <button
            type="submit"
            class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-6 py-2.5 rounded-lg shadow-sm transition">
            Save Attendance
        </button>

        <a href="{{ route('attendances.index') }}"
           class="bg-white border border-gray-200 hover:bg-gray-50 text-gray-600 text-sm font-medium px-6 py-2.5 rounded-lg transition">
            Cancel
        </a>
    </div>
</div>