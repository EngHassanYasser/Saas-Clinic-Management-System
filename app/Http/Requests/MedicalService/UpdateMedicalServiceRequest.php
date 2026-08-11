<?php

namespace App\Http\Requests\Medical_Service;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMedical_ServiceRequest extends FormRequest
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
            'medicalServicePriceId'=>['required','integer'],
            'doctorId' => 'required|exists:doctors,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0.01',
        ];
    }
}
