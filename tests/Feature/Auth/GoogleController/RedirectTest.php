<?php

use App\Enums\EnRoleType;use Laravel\Socialite\Facades\Socialite;
it('stores account type in session and redirects to google', function () {

    Socialite::shouldReceive('driver')
        ->once()
        ->with('google')
        ->andReturnSelf();

    Socialite::shouldReceive('redirect')
        ->once()
        ->andReturn(redirect('https://accounts.google.com'));

    $response = $this->get(route('google.redirect', [
        'type' =>EnRoleType::PATIENT,
    ]));

    expect(session('account_type'))
        ->toBe(RoleType::PATIENT->value);

    $response->assertRedirect('https://accounts.google.com');
});
it('stores selected account type', function ($type) {

    Socialite::shouldReceive('driver')
        ->once()
        ->with('google')
        ->andReturnSelf();

    Socialite::shouldReceive('redirect')
        ->once()
        ->andReturn(redirect('https://accounts.google.com'));

    $this->get(route('google.redirect', [
        'type' => $type,
    ]));

    expect(session('account_type'))
        ->toBe($type->value);

})->with('account-types');
it('overwrites previous account type in session', function () {

    Socialite::shouldReceive('driver')
        ->twice()
        ->with('google')
        ->andReturnSelf();

    Socialite::shouldReceive('redirect')
        ->twice()
        ->andReturn(redirect('https://accounts.google.com'));

    $this->get(route('google.redirect', [
        'type' =>EnRoleType::PATIENT,
    ]));

    $this->get(route('google.redirect', [
        'type' =>EnRoleType::CLINIC,
    ]));

    expect(session('account_type'))
        ->toBe(RoleType::CLINIC->value);
});