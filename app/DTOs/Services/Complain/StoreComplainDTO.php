<?php

namespace App\DTOs\Services\Complaint;

use App\Enums\ComplaintStatus;
use App\Enums\enSeverity;
use App\Enums\IssueType;
use App\Http\Requests\complaintts\StoreComplaintRequest;

class StoreComplaintDTO
{
    public function __construct(
        public readonly string $patientName,
        public readonly string $departmentName,
        public readonly int $doctorId,
        public readonly string $visiteDate,
        public readonly IssueType $issueType,
        public readonly enSeverity $severity,
        public readonly string $description,
        public readonly ComplaintStatus $status
    ) {}

    public static function fromRequest(StoreComplaintRequest $request): self
    {
        return new self(
            patientName: $request->string('patientName'),
            departmentName: $request->string('departmentName'),
            doctorId: $request->integer('doctorId'),
            visiteDate: $request->string('visiteDate'),
            issueType: $request->IssueType('issueType'),
            severity: $request->enSeverity('severity'),
            description: $request->string('description'),
            status: $request->ComplaintStatus('status')
        );
    }
}
