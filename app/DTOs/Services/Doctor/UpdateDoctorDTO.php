<?php

namespace App\DTOs\Services\Doctor;

use App\Http\Requests\doctor\UpdateDoctorRequest;

class UpdateDoctorDTO
{
    public function __construct(
        public readonly string $name,
        public readonly ?UploadedFile $image,
        public readonly int $specialityId,
        public readonly string $email,
        public readonly string $phone,
        public readonly bool $isActive
    ) {}

    public static function fromRequest(UpdateDoctorRequest $request): self
    {
        return new self(
            name: $request->string('name'),
            image: $request->file('image'),
            specialityId: $request->integer('specialityId'),
            email: $request->string('email'),
            phone: $request->string('phone'),
            isActive: $request->boolean('isActive')
        );
    }
}
