<?php

namespace App\Http\Controllers\Auth;

use App\Enums\RoleType;
use App\Events\UserCreated;
use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect(Request $request)
    {
        session()->put('account_type', $request->type);

        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        DB::transaction(function () {
            $googleUser = Socialite::driver('google')->user();
            $user = User::where('email', $googleUser->email)->first();

            if (! $user) {
                $user = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'password' => bcrypt(Str::random(32)),
                    'type' => session('account_type'),
                    'google_id' => $googleUser->id,
                    'user_name' => User::generateUniqueUsername($googleUser->name),
                ]);
                if ($user->type === RoleType::CLINIC->value) {
                    Clinic::create([
                        'owner_id' => $user->id,
                        'name' => $user->name,
                        'phone' => '+012456574',
                        'email' => $user->email,
                        'description' => null,
                        'slug' => 'testclinic',
                        'address' => 'jdlsfks',
                        'latitude' => null,
                        'longitude' => null,
                        'logo' => null,
                        'image_cover_name' => null,

                        'is_featured' => 0,
                        'featured_until' => null,
                    ]);
                }

                event(new UserCreated($user));
                event(new Registered($user));
            } else {

                if (! $user->google_id) {
                    $user->update([
                        'google_id' => $googleUser->id,
                    ]);
                }
            }
            Auth::login($user);
        });

        return redirect()->route('dashboard.index');
    }
}
