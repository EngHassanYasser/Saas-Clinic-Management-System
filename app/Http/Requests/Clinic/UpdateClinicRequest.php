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
            'name' => [
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

            'cityId' => [
                'required',
                'integer',
                'exists:cities,id',
            ],
            'logo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
            'workDays' => ['required', 'array', 'min:1'],
            'workDays.*' => ['integer', 'exists:days,id'],
            'openTime' => ['required', 'date_format:H:i:s'],
            'closeTime' => ['required', 'date_format:H:i:s'],
        ];
    }
}
