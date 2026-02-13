<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show login page
     */
    public function showLogin(Request $request)
    {
        // Save the previous URL if user is accessing login page directly
        // This allows us to redirect back after successful login
        if (!$request->session()->has('url.intended')) {
            $referer = $request->headers->get('referer');
            // Only save if referer is from same domain and not already a login/auth page
            if ($referer && 
                str_contains($referer, $request->getSchemeAndHttpHost()) &&
                !str_contains($referer, '/login') &&
                !str_contains($referer, '/signup') &&
                !str_contains($referer, '/register')) {
                $request->session()->put('url.intended', $referer);
            }
        }
        
        return view('accounts.login');
    }

    /**
     * Show signup page
     */
    public function showSignup(Request $request)
    {
        // Save the previous URL if user is accessing signup page directly
        // This allows us to redirect back after successful registration
        if (!$request->session()->has('url.intended')) {
            $referer = $request->headers->get('referer');
            // Only save if referer is from same domain and not already a login/auth page
            if ($referer && 
                str_contains($referer, $request->getSchemeAndHttpHost()) &&
                !str_contains($referer, '/login') &&
                !str_contains($referer, '/signup') &&
                !str_contains($referer, '/register')) {
                $request->session()->put('url.intended', $referer);
            }
        }
        
        return view('accounts.signup');
    }

    /**
     * Handle login form submission
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Get intended URL from session (set by middleware or showLogin)
            $intendedUrl = $request->session()->pull('url.intended', null);
            
            // If no intended URL, use the default dashboard
            if (!$intendedUrl) {
                $intendedUrl = route('dashboard');
            }
            
            return redirect($intendedUrl);
        }

        return back()->withErrors([
            'username' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->withInput($request->except('password'));
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        // Handle AJAX request to save URL (from JavaScript)
        if ($request->expectsJson() || $request->has('action') && $request->input('action') === 'save_url') {
            $request->session()->put('logout_redirect_url', $request->input('url'));
            return response()->json(['status' => 'success']);
        }
        
        // Get the previous URL from form input, session, or referer
        $previousUrl = $request->input('current_url');
        
        // If not in form, try to get from session
        if (!$previousUrl) {
            $previousUrl = $request->session()->pull('logout_redirect_url', null);
        }
        
        // If not in session, try to get from referer
        if (!$previousUrl) {
            $previousUrl = $request->headers->get('referer');
        }
        
        // If no referer or referer is login/auth page, use dashboard
        if (!$previousUrl || 
            str_contains($previousUrl, '/login') || 
            str_contains($previousUrl, '/signup') ||
            str_contains($previousUrl, '/register') ||
            str_contains($previousUrl, '/logout')) {
            $previousUrl = route('dashboard');
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect($previousUrl)->with('success', 'Anda telah berhasil logout.');
    }

    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan. Silakan pilih username lain.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar. Silakan gunakan email lain atau login.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        // Check if it's an AJAX/JSON request (from web form using fetch)
        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            // For API requests, create token
            if ($request->is('api/*')) {
                $token = $user->createToken('auth_token')->plainTextToken;
                return response()->json([
                    'status' => 'success',
                    'message' => 'Registrasi berhasil',
                    'data' => [
                        'user' => $user,
                        'token' => $token
                    ]
                ], 201);
            }
            
            // For web AJAX requests, return success without token
            return response()->json([
                'status' => 'success',
                'message' => 'Registrasi berhasil'
            ], 201);
        }

        // Get intended URL from session (set by middleware or showLogin)
        $intendedUrl = $request->session()->pull('url.intended', null);
        
        // If no intended URL, use the default dashboard
        if (!$intendedUrl) {
            $intendedUrl = route('dashboard');
        }
        
        return redirect($intendedUrl);
    }

    /**
     * API Login - returns token
     */
    public function apiLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Login berhasil',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Kredensial tidak valid'
        ], 401);
    }

    /**
     * API Logout
     */
    public function apiLogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout berhasil'
        ]);
    }

    /**
     * Redirect to Google OAuth
     */
    public function redirectToGoogle()
    {
        // Get redirect URI from config
        $redirectUrl = config('services.google.redirect');
        
        // If redirect URL is not set in config, build it from current request
        if (!$redirectUrl || $redirectUrl === 'http://127.0.0.1:8000/accounts/google/login/callback') {
            $scheme = request()->getScheme(); // http or https
            $host = request()->getHost(); // astabaya.bpskotasurabaya.com or localhost
            $port = request()->getPort();
            
            // Build base URL
            if ($port && $port != 80 && $port != 443) {
                $baseUrl = "{$scheme}://{$host}:{$port}";
            } else {
                $baseUrl = "{$scheme}://{$host}";
            }
            
            $redirectUrl = $baseUrl . '/accounts/google/login/callback';
        }
        
        // If using .test domain, convert to 127.0.0.1 for Google OAuth
        if (str_contains($redirectUrl, '.test')) {
            $port = request()->getPort();
            $baseUrl = $port && $port != 80 && $port != 443 
                ? "http://127.0.0.1:{$port}" 
                : 'http://127.0.0.1:8000';
            $redirectUrl = $baseUrl . '/accounts/google/login/callback';
        }
        
        return Socialite::driver('google')
            ->redirectUrl($redirectUrl)
            ->redirect();
    }

    /**
     * Handle Google OAuth callback
     */
    public function handleGoogleCallback(Request $request)
    {
        try {
            // Get redirect URI from config
            $redirectUrl = config('services.google.redirect');
            
            // If redirect URL is not set in config, build it from current request
            if (!$redirectUrl || $redirectUrl === 'http://127.0.0.1:8000/accounts/google/login/callback') {
                $scheme = request()->getScheme(); // http or https
                $host = request()->getHost(); // astabaya.bpskotasurabaya.com or localhost
                $port = request()->getPort();
                
                // Build base URL
                if ($port && $port != 80 && $port != 443) {
                    $baseUrl = "{$scheme}://{$host}:{$port}";
                } else {
                    $baseUrl = "{$scheme}://{$host}";
                }
                
                $redirectUrl = $baseUrl . '/accounts/google/login/callback';
            }
            
            // If using .test domain, convert to 127.0.0.1 for Google OAuth
            if (str_contains($redirectUrl, '.test')) {
                $port = request()->getPort();
                $baseUrl = $port && $port != 80 && $port != 443 
                    ? "http://127.0.0.1:{$port}" 
                    : 'http://127.0.0.1:8000';
                $redirectUrl = $baseUrl . '/accounts/google/login/callback';
            }
            
            $googleUser = Socialite::driver('google')
                ->redirectUrl($redirectUrl)
                ->user();

            // Check if user exists by email
            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                // Create new user
                $user = User::create([
                    'username' => $googleUser->email ?? Str::random(10),
                    'email' => $googleUser->email,
                    'password' => Hash::make(Str::random(16)), // Random password for OAuth users
                    'name' => $googleUser->name,
                ]);
            }

            // Update user info if changed
            if ($googleUser->name && $user->name !== $googleUser->name) {
                $user->update(['name' => $googleUser->name]);
            }

            Auth::login($user, true);
            
            // Get intended URL from session (set by middleware or showLogin)
            $intendedUrl = $request->session()->pull('url.intended', null);
            
            // If no intended URL, use the default dashboard
            if (!$intendedUrl) {
                $intendedUrl = route('dashboard');
            }
            
            return redirect($intendedUrl);
        } catch (\Exception $e) {
            \Log::error('Google OAuth callback error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors([
                'error' => 'Terjadi kesalahan saat login dengan Google. Silakan coba lagi.'
            ]);
        }
    }
}
