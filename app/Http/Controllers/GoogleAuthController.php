<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google_Client;
use Google\Service\Calendar;
use App\Models\GoogleToken;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class GoogleAuthController extends Controller {
    public function redirectToGoogle() {
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        $client->addScope(Calendar::CALENDAR_EVENTS);
        $client->setAccessType('offline'); // Permite refresh token

        return redirect($client->createAuthUrl());
    }

    public function handleGoogleCallback(Request $request)
    {
        $client = new Google_Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));

        if ($request->has('code')) {
            $token = $client->fetchAccessTokenWithAuthCode($request->code);

            // Debug: Verificar se o token foi recebido
            dd($token);
        }

        return redirect('/dashboard')->with('error', 'Código de autenticação não encontrado.');
    }

    
}
