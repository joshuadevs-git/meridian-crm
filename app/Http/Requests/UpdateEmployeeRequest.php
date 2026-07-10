<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $employee = $this->route('employee');

        return [
            'employee_no' => [
                'required',
                'string',
                'max:50',
                Rule::unique('employees')->ignore($employee),
            ],

            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('employees')->ignore($employee),
            ],

            'phone' => 'nullable|string|max:20',

            'branch_id' => 'required|exists:branches,id',

            'position' => 'required|string|max:255',

            'department' => 'required|string|max:255',

            'hire_date' => 'required|date',

            'salary' => 'nullable|numeric|min:0',

            'is_active' => 'boolean',
        ];
    }
}