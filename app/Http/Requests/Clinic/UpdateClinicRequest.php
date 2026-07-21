<?php

namespace App\Http\Requests\Clinic;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClinicRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {

        $clinic = $this->route('clinic');
        return [
            'clinic_name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')
                    ->ignore($clinic->owner_id),
            ],

            'user_name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'alpha_dash',
                Rule::unique('users', 'user_name')
                    ->ignore($clinic->owner_id),
            ],

            'full_name' => [
                'required',
                'string',
                'max:255',
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'address' => [
                'required',
                'string',
                'max:500',
            ],

            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('clinics', 'phone')
                    ->ignore($clinic->id),
            ],

            'city_id' => [
                'required',
                'integer',
                'exists:cities,id',
            ],
            'gendor' => [
                'required',
                Rule::in(['male', 'female']),
            ],
        ];
    }
}
