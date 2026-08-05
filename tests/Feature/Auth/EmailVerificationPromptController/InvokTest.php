<?php
use App\Models\User;

it('redirects verified user to dashboard', function () {

    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $this->actingAs($user);

    $response = $this->get(route('verification.notice'));

    $response->assertRedirect(route('dashboard.index', absolute: false));
});
it('displays verification notice for unverified user', function () {

    $user = User::factory()->unverified()->create();

    $this->actingAs($user);

    $response = $this->get(route('verification.notice'));

    $response->assertOk();

    $response->assertViewIs('auth.verify-email');
});
it('redirects guest to login page', function () {

    $response = $this->get(route('verification.notice'));

    $response->assertRedirect(route('login'));
});
it('keeps authenticated user logged in', function () {

    $user = User::factory()->unverified()->create();

    $this->actingAs($user);

    $this->get(route('verification.notice'));

    $this->assertAuthenticatedAs($user);
});