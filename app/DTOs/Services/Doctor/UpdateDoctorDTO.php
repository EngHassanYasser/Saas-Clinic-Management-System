<?php

namespace App\DTOs\Services\Doctor;

use Illuminate\Http\UploadedFile;

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

    public static function fromRequest(array $validatedData): self
    {
        return new self(
            name: $validatedData['name'],
            image: $validatedData['image'],
            specialityId: $validatedData['specialityId'],
            email: $validatedData['email'],
            phone: $validatedData['phone'],
            isActive: $validatedData['isActive']
        );
    }
}
