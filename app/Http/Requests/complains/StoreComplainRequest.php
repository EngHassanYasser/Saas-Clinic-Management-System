<?php

namespace App\Http\Requests\complains;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreComplainRequest extends FormRequest
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
            'patient_name' => ['nullable', 'string', 'max:255'],
            'department_name' => ['required','in:radiology,reception,laboratory,pharmacy,accounting,customer_service,nursing,administration,clinics,technical_support'],
            'doctor_id' => ['nullable', 'exists:doctors,id'],
            'visit_date' => ['required', 'date'],
            'issue_type' => ['required', 'in:complaint,suggestion,technical_issue,billing,medical,other'],
            'severity' => ['required', 'in:low,medium,high,critical'],
            'description' => ['required', 'string', 'max:500'],
            'status' => ['required', 'in:pending,reviewing,resolved,rejected'],
        ];
    }
    public function after(): array
    {
        return [
            function ($validator) {

                if (!$this->patient_name && Auth::user()->type="clinic") {
                    $validator->errors()->add(
                        'patient_name',
                        'الرجاء كتابة اسم المريض او المشتكي'
                    );
                }
            },
        ];
    }
}
