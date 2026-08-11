<?php

namespace App\DTOs\Services\Doctor;

use Illuminate\Http\UploadedFile;

class StoreDoctorDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $phone,
        public readonly ?UploadedFile $image,
        public readonly int $specialityId,
    ){}

    public static function fromRequest(array $validatedData):self{
        return new self(
            name:$validatedData['name'],
            email:$validatedData['email'],
            phone:$validatedData['phone'],
            image:$validatedData['image'],
            specialityId:$validatedData['specialityId']
        );
    }
}
