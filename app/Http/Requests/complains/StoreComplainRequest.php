<?php

namespace App\Http\Requests\complaints;

use App\Enums\EnComplaintStatus;
use App\Enums\EnDepartmentType;
use App\Enums\EnIssueType;
use App\Enums\EnRoleType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class StoreComplaintRequest extends FormRequest
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
            'patientName' => ['nullable', 'string', 'max:255'],
            'departmentName' => ['required',new Enum(EnDepartmentType::class)],
            'doctorId' => ['nullable', 'exists:doctors,id'],
            'visiteDate' => ['required', 'date'],
            'issueType' => ['required', new Enum(EnIssueType::class)],
            'severity' => ['required', 'in:low,medium,high,critical'],
            'description' => ['required', 'string', 'max:500'],
            'status' => ['required', new Enum(EnComplaintStatus::class)],
        ];
    }
    public function after(): array
    {
        return [
            function ($validator) {

                if (!$this->patient_name && Auth::user()->type==EnRoleType::CLINIC->value) {
                    $validator->errors()->add(
                        'patientName',
                        'الرجاء كتابة اسم المريض او المشتكي'
                    );
                }
            },
        ];
    }
}
