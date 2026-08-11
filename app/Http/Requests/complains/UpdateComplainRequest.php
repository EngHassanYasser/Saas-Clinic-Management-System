<?php

namespace App\Http\Requests\complaints;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateComplaintRequest extends FormRequest
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
            'patientName' => ['nullable', 'string', 'max:255'],
            'departmentName' => ['required'],
            'doctorId' => ['nullable', 'exists:doctors,id'],
            'visiteDate' => ['required', 'date'],
            'issueType' => ['required'],
            'severity' => ['required'],
            'description' => ['required', 'string', 'max:500'],
            'status' => ['required'],
            'resolutionNotes'=>['nullable','string','max:500'],
        ];
    }
}
