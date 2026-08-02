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

            'city_id' => [
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
            'work_days' => ['required', 'array', 'min:1'],
            'work_days.*' => ['integer', 'exists:days,id'],
            'open_time' => ['required', 'date_format:H:i:s'],
            'close_time' => ['required', 'date_format:H:i:s'],
        ];
    }
}
