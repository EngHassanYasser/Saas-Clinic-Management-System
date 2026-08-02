<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserCreated;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $googleUser = Socialite::driver('google')->user();
        $user = User::where('email', $googleUser->email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'password' => bcrypt(Str::random(32)),
                'type' => session('account_type'),
                'google_id' => $googleUser->id,
                'user_name' => User::generateUniqueUsername($googleUser->name),
            ]);

            event(new UserCreated($user));
            event(new Registered($user));
        } else {

            if (!$user->google_id) {
                $user->update([
                    'google_id' => $googleUser->id,
                ]);
            }
        }

        Auth::login($user);

        return redirect()->route('dashboard.index');
    }
}
