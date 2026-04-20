<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request) {
        
        $validated = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::whereEmail($validated['email'])->first();
        if ($user == null) {
            return redirect()->back()->with('error', 'Pengguna Tidak Ditemukan');
        }

        if (Auth::attempt($validated)) {
            Auth::login($user);
            return redirect()->route('superadmin_dashboard')->with('success', 'Login Berhasil');
        } else {
            return redirect()->back()->with('error', 'Password Salah.');
        }
    }

       public function login_form(){
        return view('Auth.login');
     }

    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return back();
       
    }
}
