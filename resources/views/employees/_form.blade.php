<div class="grid grid-cols-2 gap-4">

    <div>
        <label class="block mb-2 font-medium">Employee No.</label>
        <input
            type="text"
            name="employee_no"
            value="{{ old('employee_no', $employee->employee_no ?? '') }}"
            class="w-full border rounded-lg px-3 py-2">
    </div>

    <div>
        <label class="block mb-2 font-medium">First Name</label>
        <input
            type="text"
            name="first_name"
            value="{{ old('first_name', $employee->first_name ?? '') }}"
            class="w-full border rounded-lg px-3 py-2">
    </div>

    <div>
        <label class="block mb-2 font-medium">Last Name</label>
        <input
            type="text"
            name="last_name"
            value="{{ old('last_name', $employee->last_name ?? '') }}"
            class="w-full border rounded-lg px-3 py-2">
    </div>

    <div>
        <label class="block mb-2 font-medium">Email</label>
        <input
            type="email"
            name="email"
            value="{{ old('email', $employee->email ?? '') }}"
            class="w-full border rounded-lg px-3 py-2">
    </div>

    <div>
        <label class="block mb-2 font-medium">Phone</label>
        <input
            type="text"
            name="phone"
            value="{{ old('phone', $employee->phone ?? '') }}"
            class="w-full border rounded-lg px-3 py-2">
    </div>

    <div>
        <label class="block mb-2 font-medium">Branch</label>

        <select
            name="branch_id"
            class="w-full border rounded-lg px-3 py-2">

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

    <div>
        <label class="block mb-2 font-medium">Position</label>
        <input
            type="text"
            name="position"
            value="{{ old('position', $employee->position ?? '') }}"
            class="w-full border rounded-lg px-3 py-2">
    </div>

    <div>
        <label class="block mb-2 font-medium">Department</label>
        <input
            type="text"
            name="department"
            value="{{ old('department', $employee->department ?? '') }}"
            class="w-full border rounded-lg px-3 py-2">
    </div>

    <div>
        <label class="block mb-2 font-medium">Hire Date</label>
        <input
            type="date"
            name="hire_date"
            value="{{ old('hire_date', isset($employee) ? $employee->hire_date?->format('Y-m-d') : '') }}"
            class="w-full border rounded-lg px-3 py-2">
    </div>

    <div>
        <label class="block mb-2 font-medium">Salary</label>
        <input
            type="number"
            step="0.01"
            name="salary"
            value="{{ old('salary', $employee->salary ?? '') }}"
            class="w-full border rounded-lg px-3 py-2">
    </div>

    <div class="col-span-2">
        <label class="inline-flex items-center gap-2">

            <input
                type="checkbox"
                name="is_active"
                value="1"
                {{ old('is_active', $employee->is_active ?? true) ? 'checked' : '' }}>

            Active Employee

        </label>
    </div>

</div>