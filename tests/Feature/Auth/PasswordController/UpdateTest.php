<?php

use Illuminate\Support\Facades\Hash;
use App\Models\User;

it('updates password successfully', function () {

    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $this->actingAs($user);

    $response = $this->put(route('password.update'), [
        'current_password' => 'password',
        'password' => 'NewPassword123!',
        'password_confirmation' => 'NewPassword123!',
    ]);

    $response->assertSessionHasNoErrors();

    $response->assertSessionHas('status', 'password-updated');

    expect(Hash::check(
        'NewPassword123!',
        $user->fresh()->password
    ))->toBeTrue();
});
it('requires correct current password', function () {

    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $this->actingAs($user);

    $response = $this->put(route('password.update'), [
        'current_password' => 'wrong-password',
        'password' => 'NewPassword123!',
        'password_confirmation' => 'NewPassword123!',
    ]);

    $response->assertSessionHasErrorsIn(
        'updatePassword',
        'current_password'
    );

    expect(Hash::check(
        'password',
        $user->fresh()->password
    ))->toBeTrue();
});
it('requires current password', function () {

    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->put(route('password.update'), [
        'password' => 'NewPassword123!',
        'password_confirmation' => 'NewPassword123!',
    ]);

    $response->assertSessionHasErrorsIn(
        'updatePassword',
        'current_password'
    );
});
it('requires new password', function () {

    $user = User::factory()->create();

    $this->actingAs($user);

    $response = $this->put(route('password.update'), [
        'current_password' => 'password',
    ]);

    $response->assertSessionHasErrorsIn(
        'updatePassword',
        'password'
    );
});
it('requires password confirmation', function () {

    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $this->actingAs($user);

    $response = $this->put(route('password.update'), [
        'current_password' => 'password',
        'password' => 'NewPassword123!',
        'password_confirmation' => 'another-password',
    ]);

    $response->assertSessionHasErrorsIn(
        'updatePassword',
        'password'
    );
});
it('requires strong password', function () {

    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $this->actingAs($user);

    $response = $this->put(route('password.update'), [
        'current_password' => 'password',
        'password' => '123',
        'password_confirmation' => '123',
    ]);

    $response->assertSessionHasErrorsIn(
        'updatePassword',
        'password'
    );
});
it('does not update password when validation fails', function () {

    $user = User::factory()->create([
        'password' => Hash::make('password'),
    ]);

    $this->actingAs($user);

    $this->put(route('password.update'), [
        'current_password' => 'wrong-password',
        'password' => 'NewPassword123!',
        'password_confirmation' => 'NewPassword123!',
    ]);

    expect(Hash::check(
        'password',
        $user->fresh()->password
    ))->toBeTrue();
});
it('requires authentication', function () {

    $response = $this->put(route('password.update'), [
        'current_password' => 'password',
        'password' => 'NewPassword123!',
        'password_confirmation' => 'NewPassword123!',
    ]);

    $response->assertRedirect(route('login'));
});