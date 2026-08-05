<?php

use App\Models\User;

it('logs out authenticated user', function () {

    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->post(route('logout'));

    $this->assertGuest();

    $response->assertRedirect('/');
});
it('invalidates the session after logout', function () {

    $user = User::factory()->create();

    $this->actingAs($user);

    session(['test_key' => 'test_value']);

    $this->post(route('logout'));

    expect(session()->has('test_key'))->toBeFalse();
});
it('regenerates csrf token after logout', function () {

    $user = User::factory()->create();

    $this->actingAs($user);

    $oldToken = session()->token();

    $this->post(route('logout'));

    expect(session()->token())
        ->not->toBe($oldToken);
});
it('redirects guest to login when trying to logout', function () {

    $response = $this->post(route('logout'));

    $response->assertRedirect(route('login'));

    $this->assertGuest();
});
