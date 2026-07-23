<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        // Generar OTP
        $otp = $user->generateOtp();

        try {
            // Enviar correo
            Mail::send('emails.otp', ['otp' => $otp, 'user' => $user], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Código de verificación de seguridad');
            });
        } catch (\Exception $e) {
            // Si el correo falla, cerramos sesión y mostramos error al usuario
            Auth::logout();
            return back()->withErrors([
                'email' => 'Error real: ' . $e->getMessage() . ' en ' . $e->getFile() . ':' . $e->getLine()
            ]);
        }

        // Si el correo se envía correctamente, cerramos sesión temporalmente
        Auth::logout();

        // Guardar ID y preferencias en sesión para verificación posterior
        $request->session()->put('otp_user_id', $user->id);
        $request->session()->put('otp_remember', $request->boolean('remember'));

        return redirect()->route('otp.verify');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}