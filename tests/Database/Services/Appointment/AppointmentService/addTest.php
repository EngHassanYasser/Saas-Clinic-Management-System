<?php
use App\Exceptions\SlotDoesNotAvailable;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\ClinicService;
use App\Models\Doctor;
use App\Models\User;
use App\Services\Appointment\AppointmentAvailabilityService;
use App\Services\Appointment\AppointmentService;
use Mockery;

beforeEach(function () {

    $this->availabilityService = Mockery::mock(AppointmentAvailabilityService::class);

    $this->app->instance(
        AppointmentAvailabilityService::class,
        $this->availabilityService
    );

    $this->service = app(AppointmentService::class);
});
it('creates appointment successfully', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();
    $patient = User::factory()->create();
    $clinicService = ClinicService::factory()->create();

    $this->availabilityService
        ->shouldReceive('isSlotAvailable')
        ->once()
        ->with(
            $clinic->id,
            $doctor->id,
            '2026-08-20',
            '10:00:00'
        )
        ->andReturn(true);

    $this->availabilityService
        ->shouldReceive('getSlotDurationByVisitDate')
        ->once()
        ->with(
            $clinic->id,
            $doctor->id,
            '2026-08-20'
        )
        ->andReturn(30);

    $appointment = $this->service->add([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'clinic_service_id' => $clinicService->id,
        'visit_date' => '2026-08-20',
        'slot' => '10:00:00',
    ], $patient->id);

    expect($appointment)
        ->toBeInstanceOf(Appointment::class);

    $this->assertDatabaseHas('appointments', [
        'id' => $appointment->id,
        'patient_id' => $patient->id,
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'clinic_service_id' => $clinicService->id,
        'visit_date' => '2026-08-20',
        'start_time' => '10:00:00',
        'end_time' => '10:30:00',
    ]);
});
it('throws exception when slot is not available', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();
    $patient = User::factory()->create();
    $clinicService = ClinicService::factory()->create();

    $this->availabilityService
        ->shouldReceive('isSlotAvailable')
        ->once()
        ->andReturn(false);

    expect(fn () => $this->service->add([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'clinicService_id' => $clinicService->id,
        'visit_date' => '2026-08-20',
        'slot' => '10:00:00',
    ], $patient->id))
        ->toThrow(SlotDoesNotAvailable::class);

    $this->assertDatabaseCount('appointments', 0);
});
it('calculates end time using slot duration', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();
    $patient = User::factory()->create();
    $clinicService = ClinicService::factory()->create();

    $this->availabilityService
        ->shouldReceive('isSlotAvailable')
        ->once()
        ->andReturn(true);

    $this->availabilityService
        ->shouldReceive('getSlotDurationByVisitDate')
        ->once()
        ->andReturn(45);

    $appointment = $this->service->add([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'clinic_service_id' => $clinicService->id,
        'visit_date' => '2026-08-20',
        'slot' => '09:00:00',
    ], $patient->id);

    expect($appointment->fresh()->end_time)
        ->toBe('09:45:00');
});