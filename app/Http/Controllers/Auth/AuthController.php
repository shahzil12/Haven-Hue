<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

use Illuminate\Support\Facades\Mail;
use App\Mail\DemoMail;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'buyer', // Default to buyer
            'email_verified_at' => Carbon::now(),
        ]);

        $mailData = [
            'title' => 'Welcome to Haven & Hue',
            'body' => 'Welcome to Haven & Hue! We are excited to have you on board.',
        ];

        Mail::to($user->email)->send(new DemoMail($mailData));

        Auth::login($user);
        return $this->redirectBasedOnRole();
    }



    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return $this->redirectBasedOnRole();
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    protected function redirectBasedOnRole()
    {
        $role = Auth::user()->role;
        if ($role === 'admin') return redirect()->route('admin.dashboard');
        // Seller role is deprecated, treated as buyer or redirected to home
        return redirect()->route('home');
    }
}
