<?php

use App\Enums\EnRoleType;use App\Models\User;

it('displays the login page for guests', function () {

    $response = $this->get(route('login'));

    $response->assertOk();

    $response->assertViewIs('auth.login');
});
it('redirects patient to appointments page', function () {

    $user = User::factory()->create([
        'type' =>EnRoleType::PATIENT,
    ]);

    $this->actingAs($user);

    $response = $this->get(route('dashboard.index'));

    $response->assertRedirect(route('appointments.index'));
});
it('redirects clinic user to clinic dashboard', function () {

    $user = User::factory()->create([
        'type' =>EnRoleType::CLINIC,
    ]);

    $this->actingAs($user);

    $response = $this->get(route('dashboard.index'));

    $response->assertRedirect(route('clinic.stats'));
});
it('redirects super admin to admin dashboard', function () {

    $user = User::factory()->create([
        'type' =>EnRoleType::SUPER_ADMIN,
    ]);

    $this->actingAs($user);

    $response = $this->get(route('dashboard.index'));

    $response->assertRedirect(route('dashboard.getstats'));
});
