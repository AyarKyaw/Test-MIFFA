<?php

namespace App\Http\Controllers;

use App\Models\User;
use Google\Client as GoogleClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // Handle user registration
    public function register(Request $request)
    {
        // 1. Validate form fields matching the Blade view
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone'    => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // 2. Create the user record in the database
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
        ]);

        // 3. Automatically log the user in after successful registration
        Auth::login($user, $request->has('remember'));

        // 4. Regenerate session and redirect
        $request->session()->regenerate();

        return redirect()->intended('/')->with('success', 'Account created successfully! Welcome to MIFFA.');
    }

    // Handle standard login attempt
    public function login(Request $request)
    {
        // 1. Validate form fields
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Check "Remember Me" checkbox
        $remember = $request->has('remember');

        // 3. Attempt to log the user in
        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            // Redirect to protected dashboard or home page
            return redirect()->intended('/')->with('success', 'Welcome back!');
        }

        // 4. Return back with error if credentials fail
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // Handle Google Sign-In
    public function handleGoogleOneTap(Request $request)
    {
        $request->validate([
            'email'     => 'required|email',
            'google_id' => 'required|string',
            'name'      => 'nullable|string',
        ]);

        try {
            $googleId = $request->google_id;
            $email    = $request->email;
            $name     = $request->name ?? 'Google User';

            // Check if user already exists
            $user = User::where('google_id', $googleId)
                        ->orWhere('email', $email)
                        ->first();

            $isNewRegistration = false;

            if (!$user) {
                // Brand new user registration via Google
                $user = User::create([
                    'name'      => $name,
                    'email'     => $email,
                    'google_id' => $googleId,
                    'password'  => Hash::make(Str::random(16)),
                ]);
                
                $isNewRegistration = true;
            } else {
                // Link Google ID if account originally used normal email/password
                if (!$user->google_id) {
                    $user->update(['google_id' => $googleId]);
                }
            }

            // Log user in
            Auth::login($user, true);
            $request->session()->regenerate();

            // Handle session feedback & redirect targets based on status
            if ($isNewRegistration) {
                session()->flash('success', 'Registration successful! Welcome to MIFFA.');
                $redirectUrl = url('/'); // Or route to an onboarding page e.g. url('/welcome')
            } else {
                session()->flash('success', 'Welcome back, ' . $user->name . '!');
                $redirectUrl = url('/'); // Standard home/
            }

            return response()->json([
                'success'  => true,
                'is_new'   => $isNewRegistration,
                'redirect' => $redirectUrl
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}