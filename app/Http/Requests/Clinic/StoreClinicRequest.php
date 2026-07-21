<?php

namespace App\Http\Requests\Clinic;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreClinicRequest extends FormRequest
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
            'clinic_name' => ['required', 'string', 'max:255'],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'user_name' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'alpha_dash',
                'unique:users,user_name',
            ],

            'full_name' => [
                'required',
                'string',
                'max:255',
            ],

            'password' => [
                'required',
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
                'unique:clinics,phone',
            ],

            'city_id' => [
                'required',
                'integer',
                'exists:cities,id',
            ],

            'plan_id' => [
                'required',
                'integer',
                'exists:plans,id',
            ],

            'gendor' => [
                'required',
                'in:male,female',
            ],
        ];
    }
}
