<?php

use App\Enums\RoleType;
use App\Models\User;
use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use App\Events\UserCreated;

it('creates normal user successfully', function () {

    Event::fake();

    $response = $this->post(route('register'), [
        'name' => 'Ahmed Mohamed',
        'email' => 'ahmed@test.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'type' => RoleType::PATIENT->value,
    ]);


    $response->assertRedirect();


    $this->assertDatabaseHas('users', [
        'email' => 'ahmed@test.com',
        'name' => 'ahmed mohamed',
        'type' => RoleType::PATIENT->value,
    ]);


    Event::assertDispatched(UserCreated::class);
    Event::assertDispatched(Registered::class);


    $this->assertAuthenticated();

});
it('creates clinic with clinic user', function () {

    $response = $this->post(route('register'), [

        'name' => 'Clinic Owner',
        'email' => 'clinic@test.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
        'type' => RoleType::CLINIC->value,

    ]);


    $response->assertRedirect();


    $user = User::where('email','clinic@test.com')->first();


    expect($user)->not()->toBeNull();


    $this->assertDatabaseHas('clinics',[

        'owner_id'=>$user->id
    ]);

});
it('does not create user with duplicated email', function () {


    User::factory()->create([
        'email'=>'test@test.com'
    ]);


    $response = $this->post(route('register'),[

        'name'=>'Test',
        'email'=>'test@test.com',
        'password'=>'Password123!',
        'password_confirmation'=>'Password123!',
        'type'=>RoleType::PATIENT->value

    ]);


    $response->assertSessionHasErrors('email');


    expect(User::where('email','test@test.com')->count())
        ->toBe(1);

});
it('requires required fields', function () {


    $response=$this->post(route('register'),[]);


    $response->assertSessionHasErrors([
        'name',
        'email',
        'password',
        'type'
    ]);


});
it('requires password confirmation', function () {


    $response=$this->post(route('register'),[

        'name'=>'Ahmed',
        'email'=>'a@test.com',
        'password'=>'Password123!',
        'password_confirmation'=>'wrong',
        'type'=>RoleType::PATIENT->value

    ]);


    $response->assertSessionHasErrors('password');


});
it('rejects invalid user type', function () {


    $response=$this->post(route('register'),[

        'name'=>'Ahmed',
        'email'=>'a@test.com',
        'password'=>'Password123!',
        'password_confirmation'=>'Password123!',
        'type'=>'admin123'

    ]);


    $response->assertSessionHasErrors('type');


});
it('generates unique username', function(){


    User::factory()->create([
        'user_name'=>'ahmed'
    ]);


    $this->post(route('register'),[

        'name'=>'Ahmed',
        'email'=>'a@test.com',
        'password'=>'Password123!',
        'password_confirmation'=>'Password123!',
        'type'=>RoleType::PATIENT->value

    ]);


    $user=User::where('email','a@test.com')->first();
    
    expect($user->user_name)
        ->not()
        ->toBe('ahmed');
});