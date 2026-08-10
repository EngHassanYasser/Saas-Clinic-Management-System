<?php

use App\Enums\AppointmentStatus;
use App\Enums\EnRoleType;use App\Enums\EnSubscriptionStatus;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Services\Clinic\ClinicStatisticsService;

beforeEach(function () {
    $this->service = app(ClinicStatisticsService::class);
});
it('returns the expected dashboard statistics', function () {
    User::factory()->count(5)->create([
        'type' =>EnRoleType::PATIENT,
    ]);

    $clinicOwner = User::factory()->create([
        'type' =>EnRoleType::CLINIC,
    ]);

    $clinics = Clinic::factory()
        ->count(4)
        ->create([
            'owner_id' => $clinicOwner->id,
        ]);

    $patient = User::where('type',EnRoleType::PATIENT)->first();
    $clinic = $clinics->first();

    Appointment::factory()
        ->count(10)
        ->create([
            'patient_id' => $patient->id,
            'clinic_id' => $clinic->id,
        ]);

    Subscription::factory()
        ->count(3)
        ->create([
            'price' => 100,
            'status' => EnSubscriptionStatus::ACTIVE->value,
            'clinic_id' => $clinic->id,
        ]);

    Subscription::factory()
        ->count(2)
        ->create([
            'price' => 250,
            'status' => EnSubscriptionStatus::EXPIRED->value,
            'clinic_id' => $clinic->id,
        ]);

    Appointment::factory()
        ->count(2)
        ->create([
            'status' => AppointmentStatus::CANCELLED->value,
            'patient_id' => $patient->id,
            'clinic_id' => $clinic->id,
        ]);

    $statistics = $this->service->getClinicDashboardStats();

    expect($statistics)->toBe([
        'users_total' => 5,
        'clinics_total' => 4,
        'appointments_total' => 12,
        'earnings_total' => 800.0,
        'active_subscriptions' => 3,
        'cancelled_appointments' => 2,
    ]);
});



it('counts only patients in users total', function () {
    User::factory()->count(7)->create([
        'type' =>EnRoleType::PATIENT,
    ]);

    User::factory()->count(5)->create([
        'type' =>EnRoleType::CLINIC,
    ]);

    User::factory()->count(3)->create([
        'type' =>EnRoleType::SUPER_ADMIN,
    ]);

    $statistics = $this->service->getClinicDashboardStats();

    expect($statistics['users_total'])->toBe(7);
});

it('returns zero users when there are no patients', function () {
    User::factory()->count(5)->create([
        'type' =>EnRoleType::CLINIC,
    ]);

    User::factory()->count(2)->create([
        'type' =>EnRoleType::SUPER_ADMIN,
    ]);

    $statistics = $this->service->getClinicDashboardStats();

    expect($statistics['users_total'])->toBe(0);
});

it('counts all clinics', function () {
    Clinic::factory()->count(8)->create();

    $statistics = $this->service->getClinicDashboardStats();

    expect($statistics['clinics_total'])->toBe(8);
});

it('returns zero clinics when there are no clinics', function () {
    $statistics = $this->service->getClinicDashboardStats();

    expect($statistics['clinics_total'])->toBe(0);
});

it('counts all appointments regardless of status', function () {
    Appointment::factory()->count(4)->create([
        'status' => AppointmentStatus::PENDING->value,
    ]);

    Appointment::factory()->count(3)->create([
        'status' => AppointmentStatus::CONFIRMED->value,
    ]);

    Appointment::factory()->count(2)->create([
        'status' => AppointmentStatus::CANCELLED->value,
    ]);

    $statistics = $this->service->getClinicDashboardStats();

    expect($statistics['appointments_total'])->toBe(9);
});

it('returns zero appointments when there are no appointments', function () {
    $statistics = $this->service->getClinicDashboardStats();

    expect($statistics['appointments_total'])->toBe(0);
});

it('calculates total earnings from all subscriptions', function () {
    Subscription::factory()->create([
        'price' => 100,
    ]);

    Subscription::factory()->create([
        'price' => 250.50,
    ]);

    Subscription::factory()->create([
        'price' => 99.75,
    ]);

    $statistics = $this->service->getClinicDashboardStats();

    expect($statistics['earnings_total'])->toBe(450.25);
});

it('includes subscription prices regardless of subscription status', function () {
    Subscription::factory()->create([
        'price' => 100,
        'status' => EnSubscriptionStatus::ACTIVE->value,
    ]);

    Subscription::factory()->create([
        'price' => 200,
        'status' => EnSubscriptionStatus::EXPIRED->value,
    ]);

    Subscription::factory()->create([
        'price' => 300,
        'status' => EnSubscriptionStatus::CANCELLED->value,
    ]);

    Subscription::factory()->create([
        'price' => 400,
        'status' => EnSubscriptionStatus::PENDING->value,
    ]);

    $statistics = $this->service->getClinicDashboardStats();

    expect($statistics['earnings_total'])->toBe(1000.0);
});

it('returns zero earnings when there are no subscriptions', function () {
    $statistics = $this->service->getClinicDashboardStats();

    expect($statistics['earnings_total'])->toBe(0.0);
});

it('counts only active subscriptions', function () {

    $plan = Plan::factory()->create();

    Subscription::factory()->count(5)->create([
        'status' => EnSubscriptionStatus::ACTIVE,
        'plan_id' => $plan->id,
    ]);

    Subscription::factory()->count(3)->create([
        'status' => EnSubscriptionStatus::EXPIRED,
        'plan_id' => $plan->id,
    ]);

    Subscription::factory()->count(2)->create([
        'status' => EnSubscriptionStatus::CANCELLED,
        'plan_id' => $plan->id,
    ]);

    Subscription::factory()->count(4)->create([
        'status' => EnSubscriptionStatus::PENDING,
        'plan_id' => $plan->id,
    ]);

    $statistics = $this->service->getClinicDashboardStats();

    expect($statistics['active_subscriptions'])->toBe(5);
});

it('returns zero active subscriptions when none are active', function () {
    Subscription::factory()->count(3)->create([
        'status' => EnSubscriptionStatus::EXPIRED,
    ]);

    Subscription::factory()->count(2)->create([
        'status' => EnSubscriptionStatus::CANCELLED,
    ]);

    Subscription::factory()->count(4)->create([
        'status' => EnSubscriptionStatus::PENDING,
    ]);

    $statistics = $this->service->getClinicDashboardStats();

    expect($statistics['active_subscriptions'])->toBe(0);
});

it('counts only cancelled appointments', function () {
    Appointment::factory()->count(4)->create([
        'status' => AppointmentStatus::PENDING,
    ]);

    Appointment::factory()->count(3)->create([
        'status' => AppointmentStatus::CONFIRMED,
    ]);

    Appointment::factory()->count(6)->create([
        'status' => AppointmentStatus::CANCELLED,
    ]);

    $statistics = $this->service->getClinicDashboardStats();

    expect($statistics['cancelled_appointments'])->toBe(6);
});

it('returns zero cancelled appointments when none are cancelled', function () {
    Appointment::factory()->count(5)->create([
        'status' => AppointmentStatus::PENDING,
    ]);

    Appointment::factory()->count(3)->create([
        'status' => AppointmentStatus::CONFIRMED,
    ]);

    $statistics = $this->service->getClinicDashboardStats();

    expect($statistics['cancelled_appointments'])->toBe(0);
});

it('returns all expected statistic keys', function () {
    $statistics = $this->service->getClinicDashboardStats();

    expect($statistics)->toHaveKeys([
        'users_total',
        'clinics_total',
        'appointments_total',
        'earnings_total',
        'active_subscriptions',
        'cancelled_appointments',
    ]);
});

it('returns exactly six statistics', function () {
    $statistics = $this->service->getClinicDashboardStats();

    expect($statistics)->toHaveCount(6);
});

it('returns integer values for count statistics', function () {
    User::factory()->count(3)->create([
        'type' =>EnRoleType::PATIENT,
    ]);

    Clinic::factory()->count(2)->create();

    Appointment::factory()->count(4)->create();

    Subscription::factory()->count(5)->create([
        'status' => EnSubscriptionStatus::ACTIVE,
    ]);

    Appointment::factory()->count(2)->create([
        'status' => AppointmentStatus::CANCELLED,
    ]);

    $statistics = $this->service->getClinicDashboardStats();

    expect($statistics['users_total'])->toBeInt()
        ->and($statistics['clinics_total'])->toBeInt()
        ->and($statistics['appointments_total'])->toBeInt()
        ->and($statistics['active_subscriptions'])->toBeInt()
        ->and($statistics['cancelled_appointments'])->toBeInt();
});

it('returns earnings as a float', function () {
    Subscription::factory()->create([
        'price' => 250.75,
    ]);

    $statistics = $this->service->getClinicDashboardStats();

    expect($statistics['earnings_total'])->toBeFloat();
});

it('does not change the database', function () {
    User::factory()->count(3)->create([
        'type' =>EnRoleType::PATIENT,
    ]);

    Clinic::factory()->count(2)->create();

    Appointment::factory()->count(4)->create();

    Subscription::factory()->count(3)->create();

    $usersBefore = User::count();
    $clinicsBefore = Clinic::count();
    $appointmentsBefore = Appointment::count();
    $subscriptionsBefore = Subscription::count();

    $this->service->getClinicDashboardStats();

    expect(User::count())->toBe($usersBefore)
        ->and(Clinic::count())->toBe($clinicsBefore)
        ->and(Appointment::count())->toBe($appointmentsBefore)
        ->and(Subscription::count())->toBe($subscriptionsBefore);
});

it('handles an empty database correctly', function () {
    $statistics = $this->service->getClinicDashboardStats();

    expect($statistics)->toBe([
        'users_total' => 0,
        'clinics_total' => 0,
        'appointments_total' => 0,
        'earnings_total' => 0.0,
        'active_subscriptions' => 0,
        'cancelled_appointments' => 0,
    ]);
});
