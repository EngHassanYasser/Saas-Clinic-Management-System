<?php
namespace App\DTOs\Services\Schedule;

use App\Http\Requests\Schedule\StoreScheduleRequest;

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
        public static function fromRequest(StoreScheduleRequest $request):self{
            return new self(
                startTime:$request->string('startTime'),
                endTime:$request->string('endTime'),
                slotDuration:$request->integer('slotDuration'),
                startBreak:$request->string('startBreak'),
                endBreak:$request->string('endBreak'),
                isAvailable:$request->boolean('isAvailable'),
                doctorId:$request->integer('doctorId'),
                dayIds:$request->array('dayIds'),
            );
        }
}