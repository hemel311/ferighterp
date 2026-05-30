<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AuthController extends Controller
{
    public function index()
    {
        return view('login.commonlogin');
    }
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Admin
        if (Auth::guard('admin')->attempt($credentials)) {

            $request->session()->regenerate();

            return redirect()->route('admin.dashboard');
        }

        // Forwarder
        if (Auth::guard('forwarder')->attempt($credentials)) {

            $request->session()->regenerate();

            return redirect()->route('feright.dashboard');
        }

        // Accountant
        if (Auth::guard('accountant')->attempt($credentials)) {

            $request->session()->regenerate();

            return redirect()->route('account.dashboard');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials',
        ]);
    }
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        Auth::guard('feright')->logout();
        Auth::guard('account')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
