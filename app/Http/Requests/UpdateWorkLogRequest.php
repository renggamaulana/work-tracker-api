<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWorkLogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'task_description' => 'required|string',
            'date' => 'required|date',
            'hourly_rate' => 'required|numeric',
            'additional_charges' => 'nullable|numeric',
            'contributors' => 'required|array|min:1',
            'contributors.*.employee_name' => 'required|string',
            'contributors.*.hours_spent' => 'required|numeric|min:0.1',
        ];
    }
}
