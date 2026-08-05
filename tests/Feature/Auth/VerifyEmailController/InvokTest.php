<?php

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;

uses(RefreshDatabase::class);

it('verifies the user email', function () {

    Event::fake();

    $user = User::factory()->unverified()->create();

    $url = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id' => $user->id,
            'hash' => sha1($user->getEmailForVerification()),
        ]
    );

    $response = $this
        ->actingAs($user)
        ->get($url);

    $response
        ->assertRedirect(route('dashboard.index', absolute: false).'?verified=1');

    expect($user->fresh()->hasVerifiedEmail())->toBeTrue();

    Event::assertDispatched(Verified::class);
});
it('does not dispatch verified event if email is already verified', function () {

    Event::fake();

    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $url = URL::temporarySignedRoute(
        'verification.verify',
        now()->addMinutes(60),
        [
            'id' => $user->id,
            'hash' => sha1($user->getEmailForVerification()),
        ]
    );

    $response = $this
        ->actingAs($user)
        ->get($url);

    $response
        ->assertRedirect(route('dashboard.index', absolute: false).'?verified=1');

    Event::assertNotDispatched(Verified::class);
});
