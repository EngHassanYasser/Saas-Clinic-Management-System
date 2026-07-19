<?php

namespace App\Http\Requests\appointments;

use Illuminate\Foundation\Http\FormRequest;
class RescheduleAppointment extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'appointmentId' => $this->route('appointmentId'),
            'visit_date'    => $this->route('visit_date'),
            'start_time'          => $this->route('slot'),
        ]);
    }

    public function rules(): array
    {
        return [
            'appointmentId' => ['required', 'integer', 'exists:appointments,id'],
            'visit_date'    => ['required', 'date'],
            'start_time'          => ['required', 'date_format:H:i'],
        ];
    }
}