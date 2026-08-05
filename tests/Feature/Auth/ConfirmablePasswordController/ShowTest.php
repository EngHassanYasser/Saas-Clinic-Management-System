<?php

use App\Models\User;

it('displays the confirm password page for authenticated user', function () {

    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->get(route('password.confirm'));

    $response->assertOk();

    $response->assertViewIs('auth.confirm-password');
});
it('redirects guest to login page', function () {

    $response = $this->get(route('password.confirm'));

    $response->assertRedirect(route('login'));
});