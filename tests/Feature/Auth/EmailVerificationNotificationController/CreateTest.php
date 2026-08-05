<?php
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\Notification;
use App\Models\User;

it('redirects verified user to dashboard', function () {

    Notification::fake();

    $user = User::factory()->create([
        'email_verified_at' => now(),
    ]);

    $this->actingAs($user);

    $response = $this->post(route('verification.send'));

    $response->assertRedirect(route('dashboard.index', absolute: false));

    Notification::assertNothingSent();
});


it('sends verification email to unverified user', function () {

    Notification::fake();

    $user = User::factory()->unverified()->create();

    $this->actingAs($user);

    $response = $this->from('/verify-email')
        ->post(route('verification.send'));

    $response->assertRedirect('/verify-email');

    $response->assertSessionHas('status', 'verification-link-sent');

    Notification::assertSentTo($user, VerifyEmail::class);
});
it('redirects guest to login page', function () {

    Notification::fake();

    $response = $this->post(route('verification.send'));

    $response->assertRedirect(route('login'));

    Notification::assertNothingSent();
});
it('sends verification email only to authenticated user', function () {

    Notification::fake();

    $authenticated = User::factory()->unverified()->create();

    $another = User::factory()->unverified()->create();

    $this->actingAs($authenticated);

    $this->post(route('verification.send'));

    Notification::assertSentTo($authenticated, VerifyEmail::class);

    Notification::assertNotSentTo($another, VerifyEmail::class);
});
it('keeps user authenticated', function () {

    $user = User::factory()->unverified()->create();

    $this->actingAs($user);

    $this->post(route('verification.send'));

    $this->assertAuthenticatedAs($user);
});