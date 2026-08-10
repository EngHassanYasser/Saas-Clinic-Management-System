<?php

namespace App\Http\Requests\appointments;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
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
            'clinicId' => ['required', 'integer', 'exists:clinics,id'],
            'doctorId' => ['required', 'integer', 'exists:doctors,id'],
            'medicalServiceId' => ['required', 'integer', 'exists:medicalServices,id'],
            'visiteDate' => ['required', 'date', 'after_or_equal:today'],
            'slot' => ['required', 'date_format:H:i'],
        ];
    }
}
