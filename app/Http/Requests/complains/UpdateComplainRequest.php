<?php

namespace App\Http\Requests\complains;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateComplainRequest extends FormRequest
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
            'patient_name' => ['nullable', 'string', 'max:255'],
            'department_name' => ['required'],
            'doctor_id' => ['nullable', 'exists:doctors,id'],
            'visit_date' => ['required', 'date'],
            'issue_type' => ['required'],
            'severity' => ['required'],
            'description' => ['required', 'string', 'max:500'],
            'status' => ['required'],
            'resolution_notes'=>['nullable','string','max:500'],
        ];
    }
}
