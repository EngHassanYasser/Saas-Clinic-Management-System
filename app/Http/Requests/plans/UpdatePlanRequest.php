<?php

namespace App\Http\Requests\plans;

use App\Enums\EnPlanStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdatePlanRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],

            'maxDoctors' => ['required', 'integer', 'min:1'],

            'monthlyAppointmentsLimit' => ['required', 'integer', 'min:1'],

            'monthlyPrice' => ['required', 'numeric', 'min:0'],

            'status' => ['required', new Enum(EnPlanStatus::class)],
        ];
    }
}
