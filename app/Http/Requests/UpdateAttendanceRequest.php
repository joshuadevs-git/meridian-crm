<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'employee_id'     => ['required', 'exists:employees,id'],
            'attendance_date' => ['required', 'date'],
            'time_in'         => ['nullable', 'date_format:H:i'],
            'time_out'        => ['nullable', 'date_format:H:i'],
            'status'          => ['required', 'in:Present,Late,Absent,Leave'],
            'remarks'         => ['nullable', 'string'],
        ];
    }
}