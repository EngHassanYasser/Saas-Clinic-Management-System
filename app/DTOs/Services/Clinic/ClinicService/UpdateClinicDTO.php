<?php

namespace App\DTOs\Services\Clinic\ClinicService;

use App\Http\Requests\Clinic\UpdateClinicRequest;
use Illuminate\Http\UploadedFile;

class UpdateClinicDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $address,
        public readonly string $phone,
        public readonly int $cityId,
        public readonly UploadedFile $logo,
        public readonly array $workDays,
        public readonly string $openTime,
        public readonly string $closeTime,

    ) {}

    public static function fromRequest(UpdateClinicRequest $request): self
    {
        return new self(
            name: $request->string('name'),
            email: $request->string('email'),
            password: $request->string('password'),
            address: $request->string('address'),
            phone: $request->string('phone'),
            cityId: $request->int('cityId'),
            logo: $request->uploadedFile('logo'),
            workDays: $request->array('workDays'),
            openTime:$request->string('openTime'),
            closeTime:$request->string('closeTime')
        );
    }
}
