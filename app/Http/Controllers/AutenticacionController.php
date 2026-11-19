<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AutenticacionController extends Controller
{
    // Mostrar la vista de login
    public function showLogin() {
        return view('auth.login');
    }

    // Procesar el inicio de sesión por correo
    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            // Redirigir según el rol del usuario
            if ($user->role === 'cocinero') { 
                return redirect()->route('cocina.index'); 
            } elseif ($user->role === 'mesero') {
                return redirect()->route('entrega.index');
            } else {
                return redirect()->route('menu');
            }
        }

        return back()->withErrors([
            'email' => 'Las credenciales no coinciden con nuestros registros.',
        ]);
    }

    // Cerrar sesión
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // Mostrar la vista de registro
    public function showRegister() {
        return view('auth.register');
    }

    // Procesar el registro del nuevo usuario con rol personalizado
    public function register(Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'in:cliente,cocinero,mesero'], // Valida que el rol sea uno de estos tres
        ]);

        // Crear el usuario con el rol seleccionado
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);

        // Iniciar sesión automáticamente después de registrarse
        Auth::login($user);

        // Redirigir según el rol del usuario recién registrado
        if ($user->role === 'cocinero') { 
            return redirect()->route('cocina.index'); 
        } elseif ($user->role === 'mesero') {
            return redirect()->route('entrega.index');
        } else {
            return redirect()->route('menu');
        }
    }
    
    // Mostrar la vista para solicitar el correo de recuperación
    public function showLinkRequestForm() {
        return view('auth.passwords.email');
    }

    // Procesar el correo introducido y llevar al formulario de nueva contraseña
    public function sendResetLinkEmail(Request $request) {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email']
        ]);

        // Redirige directamente al formulario de cambio pasando el correo por parámetro
        return redirect()->route('password.reset', ['email' => $request->email]);
    }

    // Verificar el correo y mostrar el formulario para ingresar la nueva contraseña
    public function showResetForm(Request $request) {
        return view('auth.passwords.reset', ['email' => $request->email]);
    }

    // Actualizar la contraseña en la base de datos
    public function reset(Request $request) {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::where('email', $request->email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        return redirect()->route('login')->with('success', '¡Contraseña actualizada con éxito! Ya puedes iniciar sesión.');
    }
}