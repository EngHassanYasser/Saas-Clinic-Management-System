<?php
use App\Enums\RoleType;
use App\Events\UserCreated;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
uses(RefreshDatabase::class);
it('creates a new user from google account', function () {

    Event::fake();

    session([
        'account_type' => RoleType::PATIENT->value,
    ]);

    $googleUser = Mockery::mock();

    $googleUser->shouldReceive('getAttribute')->andReturnNull();

    $googleUser->id = 'google-123';
    $googleUser->name = 'Ahmed Mohamed';
    $googleUser->email = 'ahmed@example.com';

    $provider = Mockery::mock();

    $provider->shouldReceive('user')
        ->once()
        ->andReturn($googleUser);

    Socialite::shouldReceive('driver')
        ->once()
        ->with('google')
        ->andReturn($provider);

    $response = $this->get(route('google.callback'));

    $response->assertRedirect(route('dashboard.index'));

    $this->assertAuthenticated();

    $user = User::where('email', 'ahmed@example.com')->first();

    expect($user)->not->toBeNull();

    expect($user->name)->toBe('Ahmed Mohamed');

    expect($user->google_id)->toBe('google-123');

    expect($user->type)->toBe(RoleType::PATIENT);

    Event::assertDispatched(UserCreated::class);

    Event::assertDispatched(Registered::class);
});
