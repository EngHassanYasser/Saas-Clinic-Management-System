<?php

namespace App\DTOs\Services\Doctor;

use App\Http\Requests\doctor\StoreDoctorRequest;
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

    public static function fromRequest(StoreDoctorRequest $request):self{
        return new self(
            name:$request->string('name'),
            email:$request->string('email'),
            phone:$request->string('phone'),
            image:$request->file('image'),
            specialityId:$request->integer('specialityId')
        );
    }
}
