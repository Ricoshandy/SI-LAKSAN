<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validated = $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::whereEmail($validated['email'])->first();

        if ($user == null) {
            return redirect()->back()->with('error', 'Pengguna Tidak Ditemukan');
        }

        if (Auth::attempt($validated)) {
            $request->session()->regenerate();

            return match($user->role) {
                'dosen'       => redirect()->route('dosen_dashboard')->with('success', 'Login Berhasil'),
                'kepegawaian' => redirect()->route('kepegawaian_dashboard')->with('success', 'Login Berhasil'),
                'comite'      => redirect()->route('comite_dashboard')->with('success', 'Login Berhasil'),
                'senat'       => redirect()->route('senat_dashboard')->with('success', 'Login Berhasil'),
                'superadmin'  => redirect()->route('superadmin_dashboard')->with('success', 'Login Berhasil'),
                default       => redirect()->route('login.form')->with('error', 'Role tidak dikenali'),
            };
        }

        return redirect()->back()->with('error', 'Password Salah.');
    }

    public function login_form()
    {
        return view('Auth.login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login.form');
    }
}