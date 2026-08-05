<?php

use App\Enums\RoleType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

uses(RefreshDatabase::class);

it('allows user to login with valid credentials', function () {

    $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = $this->post(route('login.store'), [
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);

    $response->assertRedirect(route('dashboard.index'));

    $this->assertAuthenticated();
    $this->assertAuthenticatedAs($user);
});

it('rejects login with invalid password', function () {

    User::factory()->create([
        'email' => 'test@example.com',
        'password' => Hash::make('password123'),
    ]);

    $response = $this->post(route('login.store'), [
        'email' => 'test@example.com',
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('rejects login when user does not exist', function () {

    $response = $this->post(route('login.store'), [
        'email' => 'unknown@example.com',
        'password' => 'password123',
    ]);

    $response->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('logs out authenticated user successfully', function () {

    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->post(route('logout'));

    $response->assertRedirect('/');

    $this->assertGuest();
});
it('clinic user can login successfully', function () {

    $clinicUser = User::factory()->create([
        'type' => RoleType::CLINIC->value,
    ]);

    $response = $this->post(route('login.store'), [
        'email' => $clinicUser->email,
        'password' => 'password',
    ]);
    $this->assertAuthenticated();

    $this->assertAuthenticatedAs($clinicUser);

    $response->assertRedirect(route('dashboard.index'));
    expect(auth()->user()->type)
        ->toBe(RoleType::CLINIC);

});

it('cannot login inActive users', function () {

    $user = User::factory()->create([
        'status' => 'inactive',
    ]);

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors();

    $this->assertGuest();
});
