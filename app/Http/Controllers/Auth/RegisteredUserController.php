<?php

namespace App\Http\Controllers\Auth;

use App\Enums\EnRoleType;use App\Events\UserCreated;
use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $user = DB::transaction(function () use ($request) {

            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'type' => ['required', new Enum(RoleType::class)],
            ]);

            $user = User::create([
                'name' => strtolower(trim($request->name)),
                'email' => strtolower(trim($request->email)),
                'password' => Hash::make($request->password),
                'type' => $request->type,
                'user_name' => User::generateUniqueUsername($request->name),
            ]);

            if ($user->type ===EnRoleType::CLINIC) {
                Clinic::create([
                    'owner_id' => $user->id,
                    'name' => $user->name,
                    'phone' => null,
                    'email' => $user->email,
                    'slug' => Str::slug($user->name),
                    'address' => null,
                ]);
            }

            return $user;
        });

        event(new UserCreated($user));
        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard.index', absolute: false));
    }
}
