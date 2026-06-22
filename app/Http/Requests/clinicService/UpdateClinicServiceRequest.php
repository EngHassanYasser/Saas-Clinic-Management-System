<?php

namespace App\Http\Requests\clinicService;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateClinicServiceRequest extends FormRequest
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
            'id'=>['required','integer'],
            'clinic_service_id' => 'required|string|max:255',
            'doctor_id' => 'required|exists:doctors,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0.01',
        ];
    }
}
