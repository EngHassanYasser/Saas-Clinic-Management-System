<?php
it('displays reset password page', function () {

    $response = $this->get(route('password.reset', [
        'token' => 'fake-token',
    ]));

    $response->assertOk();

    $response->assertViewIs('auth.reset-password');
});
it('passes request object to view', function () {

    $response = $this->get(route('password.reset', [
        'token' => 'fake-token',
    ]));

    $response->assertViewHas('request', function ($request) {
        return $request->token === 'fake-token';
    });
});
it('passes email to reset password view', function () {

    $response = $this->get(route('password.reset', [
        'token' => 'fake-token',
        'email' => 'test@example.com',
    ]));

    $response->assertViewHas('request', function ($request) {
        return $request->email === 'test@example.com';
    });
});
it('passes token to reset password view', function () {

    $response = $this->get(route('password.reset', [
        'token' => '123456',
    ]));

    $response->assertViewHas('request', function ($request) {
        return $request->route('token') === '123456';
    });
});