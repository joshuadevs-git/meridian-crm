@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-4">

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
                    @selected(old('employee_id', $payroll->employee_id ?? '') == $employee->id)>
                    {{ $employee->employee_no }} - {{ $employee->first_name }} {{ $employee->last_name }}
                </option>
            @endforeach

        </select>
    </div>

    <div class="flex items-center gap-4">
        <label class="w-32 shrink-0 text-sm font-medium text-gray-600">Payroll Date</label>

        <input
            type="date"
            name="payroll_date"
            value="{{ old('payroll_date', isset($payroll) ? $payroll->payroll_date->format('Y-m-d') : '') }}"
            class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">
    </div>

    <div class="flex items-center gap-4">
        <label class="w-32 shrink-0 text-sm font-medium text-gray-600">Basic Salary</label>

        <input
            type="number"
            step="0.01"
            name="basic_salary"
            value="{{ old('basic_salary', $payroll->basic_salary ?? '') }}"
            class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">
    </div>

    <div class="flex items-center gap-4">
        <label class="w-32 shrink-0 text-sm font-medium text-gray-600">Allowance</label>

        <input
            type="number"
            step="0.01"
            name="allowance"
            value="{{ old('allowance', $payroll->allowance ?? 0) }}"
            class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">
    </div>

    <div class="flex items-center gap-4">
        <label class="w-32 shrink-0 text-sm font-medium text-gray-600">Deduction</label>

        <input
            type="number"
            step="0.01"
            name="deduction"
            value="{{ old('deduction', $payroll->deduction ?? 0) }}"
            class="flex-1 rounded-lg border border-gray-200 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-200">
    </div>

</div>

<div class="mt-6 pt-6 border-t border-gray-100">
    <button
        type="submit"
        class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-6 py-2.5 rounded-xl shadow-sm transition">
        Save Payroll
    </button>
</div>