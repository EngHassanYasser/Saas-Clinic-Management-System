<?php

use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('requires token', function () {

    $response = $this->post(route('password.store'), [
        'email' => 'test@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertSessionHasErrors('token');
});

it('requires email', function () {

    $response = $this->post(route('password.store'), [
        'token' => 'token',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertSessionHasErrors('email');
});

it('requires valid email', function () {

    $response = $this->post(route('password.store'), [
        'token' => 'token',
        'email' => 'invalid-email',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertSessionHasErrors('email');
});

it('requires password', function () {

    $response = $this->post(route('password.store'), [
        'token' => 'token',
        'email' => 'test@example.com',
    ]);

    $response->assertSessionHasErrors('password');
});

it('requires password confirmation', function () {

    $response = $this->post(route('password.store'), [
        'token' => 'token',
        'email' => 'test@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('password');
});

it('calls password broker with correct credentials', function () {

    Password::shouldReceive('reset')
        ->once()
        ->withArgs(function ($credentials, $closure) {

            expect($credentials)->toBe([
                'email' => 'test@example.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'token' => 'token',
            ]);

            expect($closure)->toBeInstanceOf(Closure::class);

            return true;
        })
        ->andReturn(Password::PASSWORD_RESET);

    $response = $this->post(route('password.store'), [
        'token' => 'token',
        'email' => 'test@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertRedirect(route('login'));

    $response->assertSessionHas('status');
});

it('redirects to login when password reset succeeds', function () {

    Password::shouldReceive('reset')
        ->once()
        ->andReturn(Password::PASSWORD_RESET);

    $response = $this->post(route('password.store'), [
        'token' => 'token',
        'email' => 'test@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertRedirect(route('login'));

    $response->assertSessionHas('status');
});

it('redirects back when password reset fails', function () {

    Password::shouldReceive('reset')
        ->once()
        ->andReturn(Password::INVALID_TOKEN);

    $response = $this->from(route('password.request'))
        ->post(route('password.store'), [
            'token' => 'invalid-token',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

    $response->assertRedirect(route('password.request'));

    $response->assertSessionHasErrors('email');
});

it('keeps email input when password reset fails', function () {

    Password::shouldReceive('reset')
        ->once()
        ->andReturn(Password::INVALID_TOKEN);

    $response = $this->from(route('password.request'))
        ->post(route('password.store'), [
            'token' => 'invalid-token',
            'email' => 'test@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

    $response->assertSessionHasInput('email', 'test@example.com');
});