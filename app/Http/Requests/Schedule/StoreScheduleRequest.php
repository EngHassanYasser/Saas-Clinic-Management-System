<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
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
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],

            'slot_duration' => ['required', 'integer', 'min:15'],

            'start_break' => ['nullable', 'date_format:H:i'],
            'end_break' => ['nullable', 'date_format:H:i', 'after:start_break'],

            'is_available' => ['required', 'boolean'],

            'doctor_id' => ['required', 'integer', 'exists:doctors,id'],
            'day_ids.*' => ['integer', 'exists:days,id'],
        ];
    }
}
