<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Muestra el formulario de Login
    public function showLoginForm()
    {
        // Si el usuario ya está autenticado, redirigirlo.
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        // Esto supone que tienes una vista en resources/views/auth/login.blade.php
        return view('auth.login'); 
    }

    // Procesa la solicitud de Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Intenta autenticar al usuario
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Redirige al dashboard
            return redirect()->intended('/dashboard'); 
        }

        // Si falla
        return back()->withErrors([
            'email' => 'Las credenciales proporcionadas no coinciden con nuestros registros.',
        ])->onlyInput('email');
    }

    // Cierra la sesión (Logout)
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirige al formulario de login
        return redirect()->route('login'); 
    }
}