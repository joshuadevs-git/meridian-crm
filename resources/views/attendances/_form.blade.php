@csrf

@if ($errors->any())
    <div class="mb-4 rounded-lg bg-red-100 border border-red-400 p-4">
        <ul class="list-disc ml-5 text-red-700">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    <div>
        <label class="block mb-2 font-medium">
            Employee
        </label>

        <select
            name="employee_id"
            class="w-full border rounded-lg px-3 py-2"
            required>

            <option value="">Select Employee</option>

            @foreach($employees as $employee)

                <option
                    value="{{ $employee->id }}"
                    @selected(old('employee_id', $attendance->employee_id ?? '') == $employee->id)>

                    {{ $employee->employee_no }} -
                    {{ $employee->first_name }}
                    {{ $employee->last_name }}

                </option>

            @endforeach

        </select>

        @error('employee_id')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div>

        <label class="block mb-2 font-medium">
            Attendance Date
        </label>

        <input
            type="date"
            name="attendance_date"
            value="{{ old('attendance_date', isset($attendance) ? $attendance->attendance_date->format('Y-m-d') : now()->format('Y-m-d')) }}"
            class="w-full border rounded-lg px-3 py-2"
            required>

        @error('attendance_date')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror

    </div>

    <div>

        <label class="block mb-2 font-medium">
            Time In
        </label>

        <input
            type="time"
            name="time_in"
            value="{{ old('time_in', isset($attendance) && $attendance->time_in ? \Carbon\Carbon::parse($attendance->time_in)->format('H:i') : '') }}"
            class="w-full border rounded-lg px-3 py-2">

        @error('time_in')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror

    </div>

    <div>

        <label class="block mb-2 font-medium">
            Time Out
        </label>

        <input
            type="time"
            name="time_out"
            value="{{ old('time_out', isset($attendance) && $attendance->time_out ? \Carbon\Carbon::parse($attendance->time_out)->format('H:i') : '') }}"
            class="w-full border rounded-lg px-3 py-2">

        @error('time_out')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror

    </div>

    <div>

        <label class="block mb-2 font-medium">
            Status
        </label>

        <select
            name="status"
            class="w-full border rounded-lg px-3 py-2">

            @foreach(['Present','Late','Absent','Leave'] as $status)

                <option
                    value="{{ $status }}"
                    @selected(old('status', $attendance->status ?? 'Present') == $status)>

                    {{ $status }}

                </option>

            @endforeach

        </select>

        @error('status')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror

    </div>

    <div class="md:col-span-2">

        <label class="block mb-2 font-medium">
            Remarks
        </label>

        <textarea
            name="remarks"
            rows="3"
            class="w-full border rounded-lg px-3 py-2">{{ old('remarks', $attendance->remarks ?? '') }}</textarea>

        @error('remarks')
            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror

    </div>

</div>

<div class="mt-6">

    <button
        type="submit"
        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">

        Save Attendance

    </button>

</div>