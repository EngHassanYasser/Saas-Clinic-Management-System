<?php
namespace App\DTOs\Services\Schedule;

class StoreScheduleDTO {
        public function __construct(
            public readonly string $startTime,
            public readonly string $endTime,
            public readonly int $slotDuration,
            public readonly string $startBreak,
            public readonly string $endBreak,
            public readonly bool $isAvailable,
            public readonly int $doctorId,
            public readonly array $dayIds,
            
        ){}
        public static function fromRequest(array $validatedData):self{
            return new self(
                startTime:$validatedData['startTime'],
                endTime:$validatedData['endTime'],
                slotDuration:$validatedData['slotDuration'],
                startBreak:$validatedData['startBreak'],
                endBreak:$validatedData['endBreak'],
                isAvailable:$validatedData['isAvailable'],
                doctorId:$validatedData['doctorId'],
                dayIds:$validatedData['dayIds'],
            );
        }
}