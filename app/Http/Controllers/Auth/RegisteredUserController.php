<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
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
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        // Set this to true or false as needed to implement the email validation logic
        $needValidateEmail = true;

        $emailRules = [
            'required',
            'string',
            'lowercase',
            'email',
            'max:255',
            'unique:' . User::class,
        ];

        if ($needValidateEmail) {
            $emailRules[] = function ($attribute, $value, $fail) {
            if (!str_ends_with($value, '@mail.ugm.ac.id')) {
                $fail('The email must end with @mail.ugm.ac.id.');
            }
            };
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => $emailRules,
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Set default role to user
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('home');
    }
}
