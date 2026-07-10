<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBranchRequest extends FormRequest
{
   public function rules(): array
{
    return [
        'name' => 'required|string|max:255',

        'code' => [
            'required',
            'string',
            'max:20',
            Rule::unique('branches')->ignore($this->branch),
        ],

        'address' => 'nullable|string',
        'phone' => 'nullable|string|max:20',
        'email' => 'nullable|email|max:255',
        'is_active' => 'boolean',
    ];
}
}