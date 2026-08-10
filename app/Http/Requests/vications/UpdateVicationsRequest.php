<?php

namespace App\Http\Requests\vacations;

use App\Enums\EnVacationStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateVacationsRequest extends FormRequest
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
            'doctorId' => ['required', 'integer', 'exists:doctors,id'],

            'startDate' => ['required', 'date'],

            'endDate' => [
                'required',
                'date',
                'after_or_equal:start_date',
            ],

            'reason' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'status' => [
                'required',
                new Enum(EnVacationStatus::class),
            ],
        ];
    }
}
