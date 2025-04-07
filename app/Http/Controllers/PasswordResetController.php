<?php

namespace App\Http\Controllers;

use App\Models\Log as LogFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PasswordResetController extends Controller
{
    public function showTokenForm(Request $request)
    {
        return view('verify_token', [
            'email' => $request->query('email')
        ]);
    }

    public function showEmailForm()
    {
        return view('rec_password');
    }

    public function sendToken(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = DB::table('person_user')->select('email')->where('email', $request->email)->first();


        if (!$user) {
            return redirect('/rec_password')->with('error', 'E-mail não encontrado.');
        }

        $token = Str::random(6);

        DB::table('password_resets')->updateOrInsert(
            ['email' => $request->email],
            ['token' => $token, 'created_at' => Carbon::now()]
        );

        // dd(config('mail'));

        // Envia e-mail
        Mail::send('emails.reset_password_code', [
            'userName' => $user->email,
            'token' => $token,
        ], function ($message) use ($request) {
            $message->to($request->email)->subject('Código de Recuperação de Senha');
        });
        

        return view('verify_token', ['email' => $request->email]);
    }

    public function verifyToken(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string'
        ]);

        $reset = DB::table('password_resets')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$reset) {
            return back()->with('error', 'Código inválido.');
        }

        return view('reset_password', ['email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        DB::table('person_user')
            ->where('email', $request->email)
            ->update([
                'password' => bcrypt($request->password),
                'email_verified' => 1
            ]);

        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect()->route('login')->with('success', 'Senha alterada com Sucesso.');
    }

    public function verifyEmailCode(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string'
        ]);

        $user = DB::table('person_user')
            ->where('email', $request->email)
            ->where('verification_code', $request->token)
            ->first();

        if (!$user) {
            return back()->with('error', 'Código inválido.');
        }

        DB::table('person_user')
            ->where('email', $request->email)
            ->update([
                'email_verified' => 1,
                'verification_code' => null
            ]);

        // LogFacade::info('Verificando código para: ' . $request->email);
        // LogFacade::info('Token recebido: ' . $request->token);
            
        return redirect()->route('login')->with('success', 'E-mail verificado com sucesso!');
    }

}

