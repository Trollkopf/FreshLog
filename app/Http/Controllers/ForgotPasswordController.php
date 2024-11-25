<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        // Generar un token único
        $token = Str::random(60);

        // Guardar en la base de datos
        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            [
                'token' => Hash::make($token),
                'created_at' => Carbon::now(),
            ]
        );

        // Enviar el correo
        Mail::send('auth.passwords.reset_email', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Restablecer contraseña');
        });

        return back()->with('status', 'Se ha enviado un enlace para restablecer la contraseña.');
    }

    public function showResetForm($token)
    {
        return view('auth.passwords.reset', ['token' => $token]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|confirmed|min:8',
            'token' => 'required',
        ]);

        // Verificar el token
        $passwordReset = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if (!$passwordReset || !Hash::check($request->token, $passwordReset->token)) {
            return back()->withErrors(['token' => 'El token es inválido o ha expirado.']);
        }

        // Actualizar la contraseña
        $user = \App\Models\User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        // Eliminar el token
        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect('/login')->with('status', 'Tu contraseña ha sido restablecida.');
    }
}
