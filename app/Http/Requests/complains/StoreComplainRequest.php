<?php

namespace App\Http\Requests\complains;

use App\Enums\ComplainStatus;
use App\Enums\DepartmentType;
use App\Enums\IssueType;
use App\Enums\RoleType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

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
            'department_name' => ['required',new Enum(DepartmentType::class)],
            'doctor_id' => ['nullable', 'exists:doctors,id'],
            'visit_date' => ['required', 'date'],
            'issue_type' => ['required', new Enum(IssueType::class)],
            'severity' => ['required', 'in:low,medium,high,critical'],
            'description' => ['required', 'string', 'max:500'],
            'status' => ['required', new Enum(ComplainStatus::class)],
        ];
    }
    public function after(): array
    {
        return [
            function ($validator) {

                if (!$this->patient_name && Auth::user()->type== RoleType::CLINIC->value) {
                    $validator->errors()->add(
                        'patient_name',
                        'الرجاء كتابة اسم المريض او المشتكي'
                    );
                }
            },
        ];
    }
}
