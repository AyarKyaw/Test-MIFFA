<?php

namespace App\Http\Controllers;

use App\Models\User;
use Google\Client as GoogleClient;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Mail\ConfirmAccountMail;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    // Handle user registration via Form
    public function register(Request $request)
    {
        // 1. Validate fields
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'google_id' => ['nullable', 'string'],
        ]);

        // 2. Prepare payload (hashed password)
        $userData = [
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'google_id' => $request->google_id,
        ];

        // 3. Store payload in session for resend capability
        session(['pending_registration' => $userData]);

        // 4. Send initial confirmation email
        $this->sendConfirmationEmail($userData);

        // 5. Render waiting page (NO USER CREATED IN DB YET)
        return view('auth.verify-email', ['email' => $request->email]);
    }

    // Resend confirmation link from waiting page
    public function resendConfirmation(Request $request)
    {
        $userData = session('pending_registration');

        if (!$userData) {
            return redirect()->route('register')->withErrors(['email' => 'Session expired. Please register again.']);
        }

        $this->sendConfirmationEmail($userData);

        return back()->with('success', 'A new confirmation link has been sent to ' . $userData['email']);
    }

    // Helper method to send email with signed link
    private function sendConfirmationEmail(array $userData)
    {
        $confirmationUrl = URL::temporarySignedRoute(
            'account.confirm',
            now()->addMinutes(60),
            ['payload' => encrypt($userData)]
        );

        Mail::raw(
            "Welcome to MIFFA!\n\nPlease click the link below to confirm your email and log in to your account:\n\n" . $confirmationUrl, 
            function ($message) use ($userData) {
                $message->to($userData['email'])
                        ->subject('Confirm Your MIFFA Account');
            }
        );
    }

    public function checkVerificationStatus(Request $request)
{
    $pendingEmail = session('pending_registration.email');

    // 1. Already Logged In
    if (Auth::check()) {
        Log::info('POLL STATUS: User is already logged in on this tab.', ['user_id' => Auth::id()]);
        session()->forget('pending_registration');
        return response()->json([
            'confirmed' => true,
            'redirect'  => url('/')
        ]);
    }

    // 2. Pending Email Check
    if ($pendingEmail) {
        $cacheKey = 'confirmed_email_' . md5($pendingEmail);
        $cachedUserId = Cache::get($cacheKey);

        // Check Cache first, then DB
        $user = $cachedUserId ? User::find($cachedUserId) : User::where('email', $pendingEmail)->first();

        Log::info('POLL STATUS: Checking for confirmed user...', [
            'pending_email'  => $pendingEmail,
            'cache_key'      => $cacheKey,
            'cached_user_id' => $cachedUserId,
            'user_found'     => (bool) $user,
        ]);

        if ($user) {
            Auth::login($user, true);
            $request->session()->regenerate();
            session()->forget('pending_registration');
            Cache::forget($cacheKey);

            Log::info('POLL STATUS: SUCCESS! User found, logged in, and redirecting.', ['user_id' => $user->id]);

            return response()->json([
                'confirmed' => true,
                'redirect'  => url('/')
            ]);
        }
    } else {
        Log::warning('POLL STATUS: No pending_registration.email found in session!');
    }

    return response()->json(['confirmed' => false]);
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

            // Redirect to intended page (or verify notice if unverified)
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
                // Brand new user registration via Google: AUTO-VERIFY EMAIL
                $user = User::create([
                    'name'              => $name,
                    'email'             => $email,
                    'google_id'         => $googleId,
                    'password'          => Hash::make(Str::random(16)),
                    'email_verified_at' => now(), // Auto-verified instantly
                ]);
                
                $isNewRegistration = true;
            } else {
                // If existing account links with Google, auto-verify if not already verified
                $updates = [];
                if (!$user->google_id) {
                    $updates['google_id'] = $googleId;
                }
                if (is_null($user->email_verified_at)) {
                    $updates['email_verified_at'] = now();
                }

                if (!empty($updates)) {
                    $user->update($updates);
                }
            }

            // Log user in
            Auth::login($user, true);
            $request->session()->regenerate();

            // Session feedback & redirects
            if ($isNewRegistration) {
                session()->flash('success', 'Registration successful! Welcome to MIFFA.');
            } else {
                session()->flash('success', 'Welcome back, ' . $user->name . '!');
            }

            return response()->json([
                'success'  => true,
                'is_new'   => $isNewRegistration,
                'redirect' => url('/') // Directly to home, bypassing verification screens
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}