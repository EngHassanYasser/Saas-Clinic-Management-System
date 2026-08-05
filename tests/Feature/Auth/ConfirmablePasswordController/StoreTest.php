<?php
use App\Models\User;

it('confirms password successfully', function () {

    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->post(route('password.confirm'), [
        'password' => 'password',
    ]);

    $response->assertRedirect(route('dashboard.index', absolute: false));

    expect(session()->has('auth.password_confirmed_at'))
        ->toBeTrue();
});
it('rejects invalid password', function () {

    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->from(route('password.confirm'))->post(route('password.confirm'), [
        'password' => 'wrong-password',
    ]);

    $response->assertRedirect(route('password.confirm'));

    $response->assertSessionHasErrors('password');

    expect(session()->has('auth.password_confirmed_at'))
        ->toBeFalse();
});
it('requires password', function () {

    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->post(route('password.confirm'), []);

    $response->assertSessionHasErrors('password');
});
it('redirects guest to login page', function () {

    $response = $this->post(route('password.confirm'), [
        'password' => 'password',
    ]);

    $response->assertRedirect(route('login'));
});
it('stores password confirmation timestamp', function () {

    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post(route('password.confirm'), [
        'password' => 'password',
    ]);

    expect(session('auth.password_confirmed_at'))
        ->toBeInt();
});
it('keeps user authenticated after confirming password', function () {

    $user = User::factory()->create();

    $this->actingAs($user);

    $this->post(route('password.confirm'), [
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($user);
});