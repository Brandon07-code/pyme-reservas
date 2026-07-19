<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OtpVerificationController extends Controller
{
    public function show(Request $request)
    {
        if (!$request->session()->has('otp_user_id')) {
            return redirect()->route('login');
        }

        $user = User::find($request->session()->get('otp_user_id'));
        if (!$user) {
            return redirect()->route('login');
        }

        return view('auth.otp-verify');
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);

        $userId = $request->session()->get('otp_user_id');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->verifyOtp($request->code)) {
            return back()->withErrors(['code' => 'El código ingresado no es válido o ha expirado.']);
        }

        // Limpiar OTP para que no se pueda reutilizar
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        // Completar login oficial
        $request->session()->forget('otp_user_id');
        Auth::loginUsingId($user->id, $request->session()->get('otp_remember', false));
        $request->session()->regenerate();

        // Lógica de redirección por roles oficial de JyM Barbería
        if ($user->role_id == 3) {
            return redirect()->route('portal.index');
        }
        return redirect()->route('dashboard');
    }

    public function resend(Request $request)
    {
        $userId = $request->session()->get('otp_user_id');
        $user = User::find($userId);

        if ($user) {
            $otp = $user->generateOtp();
            Mail::send('emails.otp', ['otp' => $otp, 'user' => $user], function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Nuevo código de verificación de seguridad');
            });
        }

        return back()->with('status', 'Se ha enviado un nuevo código a tu correo.');
    }
}
