<div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">

    <div class="flex items-center gap-4">
        <label class="w-32 shrink-0 text-sm font-medium text-gray-600">Employee No.</label>
        <input
            type="text"
            name="employee_no"
            value="{{ old('employee_no', $employee->employee_no ?? '') }}"
            class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">
    </div>

    <div class="flex items-center gap-4">
        <label class="w-32 shrink-0 text-sm font-medium text-gray-600">First Name</label>
        <input
            type="text"
            name="first_name"
            value="{{ old('first_name', $employee->first_name ?? '') }}"
            class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">
    </div>

    <div class="flex items-center gap-4">
        <label class="w-32 shrink-0 text-sm font-medium text-gray-600">Last Name</label>
        <input
            type="text"
            name="last_name"
            value="{{ old('last_name', $employee->last_name ?? '') }}"
            class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">
    </div>

    <div class="flex items-center gap-4">
        <label class="w-32 shrink-0 text-sm font-medium text-gray-600">Email</label>
        <input
            type="email"
            name="email"
            value="{{ old('email', $employee->email ?? '') }}"
            class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">
    </div>

    <div class="flex items-center gap-4">
        <label class="w-32 shrink-0 text-sm font-medium text-gray-600">Phone</label>
        <input
            type="text"
            name="phone"
            value="{{ old('phone', $employee->phone ?? '') }}"
            class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">
    </div>

    <div class="flex items-center gap-4">
        <label class="w-32 shrink-0 text-sm font-medium text-gray-600">Branch</label>

        <select
            name="branch_id"
            class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">

            <option value="">Select Branch</option>

            @foreach($branches as $branch)
                <option
                    value="{{ $branch->id }}"
                    {{ old('branch_id', $employee->branch_id ?? '') == $branch->id ? 'selected' : '' }}>
                    {{ $branch->name }}
                </option>
            @endforeach

        </select>
    </div>

    <div class="flex items-center gap-4">
        <label class="w-32 shrink-0 text-sm font-medium text-gray-600">Position</label>
        <input
            type="text"
            name="position"
            value="{{ old('position', $employee->position ?? '') }}"
            class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">
    </div>

    <div class="flex items-center gap-4">
        <label class="w-32 shrink-0 text-sm font-medium text-gray-600">Department</label>
        <input
            type="text"
            name="department"
            value="{{ old('department', $employee->department ?? '') }}"
            class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">
    </div>

    <div class="flex items-center gap-4">
        <label class="w-32 shrink-0 text-sm font-medium text-gray-600">Hire Date</label>
        <input
            type="date"
            name="hire_date"
            value="{{ old('hire_date', isset($employee) ? $employee->hire_date?->format('Y-m-d') : '') }}"
            class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">
    </div>

    <div class="flex items-center gap-4">
        <label class="w-32 shrink-0 text-sm font-medium text-gray-600">Salary</label>
        <input
            type="number"
            step="0.01"
            name="salary"
            value="{{ old('salary', $employee->salary ?? '') }}"
            class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">
    </div>

    <div class="md:col-span-2 flex items-center gap-4">
        <label class="w-32 shrink-0 text-sm font-medium text-gray-600">Status</label>

        <label class="flex-1 inline-flex items-center gap-2 text-sm text-gray-600">
            <input
                type="checkbox"
                name="is_active"
                value="1"
                {{ old('is_active', $employee->is_active ?? true) ? 'checked' : '' }}
                class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-200">
            Active Employee
        </label>
    </div>

</div>