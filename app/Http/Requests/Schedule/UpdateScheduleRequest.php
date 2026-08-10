<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
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
           'startTime' => ['required', 'date_format:H:i'],
            'endTime' => ['required', 'date_format:H:i', 'after:start_time'],

            'slotDuration' => ['required', 'integer', 'min:15'],

            'startBreak' => ['nullable', 'date_format:H:i'],
            'endBreak' => ['nullable', 'date_format:H:i', 'after:start_break'],

            'isAvailable' => ['required', 'boolean'],

            'doctorId' => ['required', 'integer', 'exists:doctors,id'],
            'dayIds.*' => ['integer', 'exists:days,id'],
        ];
    }
}
