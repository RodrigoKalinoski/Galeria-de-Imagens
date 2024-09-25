<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Método para exibir o formulário de registro
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // Método para registrar um novo usuário
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
        ]);

        Auth::login($user);

        return redirect()->route('home'); // Redirecionar para a página inicial ou galeria
    }

    // Método para exibir o formulário de login
    public function showLoginForm()
    {
        return view('auth.login');
    }

    // Método para realizar login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->route('home'); // Redirecionar após login bem-sucedido
        }

        return back()->withErrors([
            'email' => 'As credenciais informadas não correspondem aos nossos registros.',
        ]);
    }

    // Método para logout
    public function logout()
    {
        Auth::logout();
        return redirect()->route('home'); // Redirecionar após logout
    }
}