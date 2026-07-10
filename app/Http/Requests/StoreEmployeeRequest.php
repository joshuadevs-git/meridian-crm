<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreEmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
{
    return [
        'employee_no' => 'required|string|max:50|unique:employees',
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:employees',
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
