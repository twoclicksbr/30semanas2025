<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RecPasswordController extends Controller
{
    public function sendToken(Request $request)
    {

        // Log::info('📬 Requisição recebida no sendToken', $request->all());

        $request->validate(['email' => 'required|email']);

        $user = DB::table('person_user')->where('email', $request->email)->first();

        // Log::info('🔍 Resultado do user:', ['user' => $user]);

        if (!$user) {
            return response()->json(['error' => 'Email not found.'], 404);
        }

        $token = mt_rand(100000, 999999);

        // Log::info('✅ Usuário encontrado:', (array) $user);


        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        Mail::send('emails.reset_password_code', [
            'userName' => $user->email,
            'token' => $token,
        ], function ($message) use ($request) {
            $message->to($request->email)->subject('Código de Recuperação de Senha');
        });

        // Log::info('📨 E-mail enviado com token: ' . $token);


        return response()->json(['message' => 'Token sent to email.']);
    }

    public function verifyToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|numeric'
        ]);

        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return response()->json(['error' => 'Invalid token or email.'], 400);
        }

        return response()->json(['message' => 'Token is valid.']);
    }


    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|numeric',
            'password' => 'required|min:6'
        ]);

        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return response()->json(['error' => 'Invalid token or email.'], 400);
        }

        DB::table('person_user')
            ->where('email', $request->email)
            ->update(['password' => bcrypt($request->password)]);

        DB::table('password_resets')
            ->where('email', $request->email)
            ->delete();

        return response()->json(['message' => 'Password updated successfully.']);
    }

}
