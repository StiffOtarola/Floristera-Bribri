<?php

namespace App\Http\Controllers;

use App\Models\Suscriptor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthController extends Controller
{
    // ══════════════════════════════════════════════════════
    // FORMULARIO DE LOGIN (compartido admin + cliente)
    // ══════════════════════════════════════════════════════

    public function loginForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }
        if (Auth::guard('web')->check()) {
            return redirect()->route('home');
        }

        // FIX #2: 'auth.login' (el archivo que existe), no 'auth.login-admin'
        return view('auth.login');
    }

    // ══════════════════════════════════════════════════════
    // LOGIN — intenta admin primero, luego cliente
    // ══════════════════════════════════════════════════════

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = [
            'email'    => $request->input('email'),
            'password' => $request->input('password'),
        ];

        // FIX #3: intentar admin primero
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        // FIX #3: si no es admin, intentar como cliente (suscriptor)
        if (Auth::guard('web')->attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        return back()
            ->withInput(['email' => $request->input('email')])
            ->withErrors(['email' => 'Credenciales incorrectas.']);
    }

    // ══════════════════════════════════════════════════════
    // LOGOUT ADMIN
    // ══════════════════════════════════════════════════════

    public function logoutAdmin(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // FIX #1: ruta correcta es 'login', no 'login.admin'
        return redirect()->route('login');
    }

    // ══════════════════════════════════════════════════════
    // REGISTRO DE CLIENTE
    // ══════════════════════════════════════════════════════

    public function registroForm()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('home');
        }
        return view('auth.registro');
    }

    public function registro(Request $request)
    {
        $request->validate([
            'nombre'   => 'required|string|max:100',
            'email'    => 'required|email|unique:suscriptores,email',
            'password' => 'required|min:8|confirmed',
        ]);

        $suscriptor = Suscriptor::create([
            'nombre'        => trim($request->input('nombre')),
            'email'         => strtolower(trim($request->input('email'))),
            'password_hash' => Hash::make($request->input('password')),
            'activo'        => true,
        ]);

        Auth::guard('web')->login($suscriptor);
        $request->session()->regenerate();

        return redirect()->route('home')
            ->with('success', '¡Bienvenida, ' . $suscriptor->nombre . '! Tu cuenta fue creada exitosamente.');
    }

    // ══════════════════════════════════════════════════════
    // LOGOUT CLIENTE
    // ══════════════════════════════════════════════════════

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    // ══════════════════════════════════════════════════════
    // RECUPERAR CONTRASEÑA — solicitar enlace
    // ══════════════════════════════════════════════════════

    public function recuperarForm()
    {
        return view('auth.recuperar');
    }

    public function enviarReset(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Usa el broker por defecto ('suscriptores' en config/auth.php).
        Password::sendResetLink($request->only('email'));

        // Mensaje genérico: no revela si el correo está o no registrado.
        return back()->with('success',
            'Si ese correo está registrado, te enviamos un enlace para restablecer tu contraseña. Revisá tu bandeja de entrada (y la carpeta de spam).');
    }

    // ══════════════════════════════════════════════════════
    // RECUPERAR CONTRASEÑA — formulario con token
    // ══════════════════════════════════════════════════════

    public function restablecerForm(Request $request, string $token)
    {
        return view('auth.restablecer', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }

    public function restablecer(Request $request)
    {
        $request->validate([
            'token'    => 'required',
            'email'    => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($suscriptor, $password) {
                // La tabla usa password_hash, no la columna estándar 'password'.
                $suscriptor->password_hash = Hash::make($password);
                $suscriptor->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')
                ->with('success', 'Tu contraseña fue restablecida. Ya podés iniciar sesión.');
        }

        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'El enlace no es válido o ya expiró. Solicitá uno nuevo.']);
    }
}