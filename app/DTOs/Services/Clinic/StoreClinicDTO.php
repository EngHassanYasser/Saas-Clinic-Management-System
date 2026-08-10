<?php

namespace App\DTOs\Services\Clinic;

use App\Http\Requests\Clinic\StoreClinicRequest;

class StoreClinicDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $username,
        public readonly string $fullName,
        public readonly string $password,
        public readonly string $address,
        public readonly string $phone,
        public readonly int $cityId,
        public readonly string $gendor,
    ) {}

    public static function fromRequest(StoreClinicRequest $request): self
    {
        return new self(
            name: $request->string('name'),
            email: $request->string('email'),
            username: $request->string('username'),
            fullName: $request->string('fullName'),
            password: $request->string('password'),
            address: $request->string('address'),
            phone: $request->string('phone'),
            cityId: $request->integer('cityId'),
            gendor: $request->string('gendor'),
        );
    }
}
