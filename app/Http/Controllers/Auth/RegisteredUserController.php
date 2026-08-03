<?php

namespace App\Http\Controllers\Auth;

use App\Enums\RoleType;
use App\Events\UserCreated;
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
        return DB::transaction(function () use ($request) {
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'type' => ['required',  new Enum(RoleType::class)],
            ]);
            $user = User::create([
                'name' => strtolower(trim($request->name)),
                'email' => strtolower(trim($request->email)),
                'password' => Hash::make($request->password),
                'type' => $request->type,
                'user_name' => User::generateUniqueUsername($request->name),
            ]);
            if ($user->type === RoleType::CLINIC) {
                Clinic::create([
                    'owner_id' => $user->id,
                    'name' => $user->name,
                    'phone' => '+012456574',
                    'email' => $user->email,
                    // optional fields من الجدول
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
        });

        event(new UserCreated($user));
        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard.index', absolute: false));
    }
}
