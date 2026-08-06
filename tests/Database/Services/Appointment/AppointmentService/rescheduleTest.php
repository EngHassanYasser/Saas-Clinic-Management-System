<?php

use App\Models\Appointment;
use App\Models\Clinic;
use App\Services\Appointment\AppointmentAvailabilityService;
use App\Services\Appointment\AppointmentService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Mockery;

beforeEach(function () {
    $this->availabilityService = Mockery::mock(AppointmentAvailabilityService::class);

    $this->app->instance(
        AppointmentAvailabilityService::class,
        $this->availabilityService
    );

    $this->service = app(AppointmentService::class);
});
it('reschedules appointment successfully', function () {

    $appointment = Appointment::factory()->create([
        'visit_date' => '2026-08-10',
        'start_time' => '09:00:00',
        'end_time' => '09:30:00',
    ]);

    $this->availabilityService->shouldReceive('getSlotDurationByVisitDate')
        ->once()->andReturn(30);

    // أنشئ Schedule بحيث getSlotDurationByVisitDate() ترجع 30 دقيقة

    $result = $this->service->reschedule([
        'appointmentId' => $appointment->id,
        'visit_date' => '2026-08-20',
        'start_time' => '10:00:00',
    ], $appointment->clinic_id);

    expect($result)->toBeTrue();

    $this->assertDatabaseHas('appointments', [
        'id' => $appointment->id,
        'visit_date' => '2026-08-20',
        'start_time' => '10:00:00',
        'end_time' => '10:30:00',
    ]);
});
it('throws exception when no schedule exists', function () {

   $this->availabilityService->shouldReceive('getSlotDurationByVisitDate')
   ->once()->andReturn(0);

    $appointment = Appointment::factory()->create();
 
    expect(fn () => $this->service->reschedule([
        'appointmentId' => $appointment->id,
        'visit_date' => '2026-08-20',
        'start_time' => '10:00:00',
    ], $appointment->clinic_id))->toThrow(Exception::class, 'No schedule found.');
});

it('throws model not found exception when appointment does not exist', function () {

    expect(fn () => $this->service->reschedule([
        'appointmentId' => 999999,
        'visit_date' => '2026-08-20',
        'start_time' => '10:00:00',
    ], 1))->toThrow(ModelNotFoundException::class);
});
it('throws model not found when appointment belongs to another clinic', function () {
    $clinic1 = Clinic::factory()->create();
    $clinic2 = Clinic::factory()->create();
    $appointment = Appointment::factory()->create([
        'clinic_id' => $clinic2->id,
    ]);

    expect(fn () => $this->service->reschedule([
        'appointmentId' => $appointment->id,
        'visit_date' => '2026-08-20',
        'start_time' => '10:00:00',
    ], $clinic1->id))->toThrow(ModelNotFoundException::class);
});
it('calculates end time using slot duration', function () {

    $appointment = Appointment::factory()->create();

     $this->availabilityService->shouldReceive('getSlotDurationByVisitDate')
        ->once()->andReturn(30);

    $this->service->reschedule([
        'appointmentId' => $appointment->id,
        'visit_date' => '2026-08-20',
        'start_time' => '09:00:00',
    ], $appointment->clinic_id);

    expect($appointment->fresh()->end_time)->toBe('09:30:00');
});
