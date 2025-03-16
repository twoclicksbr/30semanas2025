<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Credential;

class AuthenticateCredential
{
    public function handle(Request $request, Closure $next)
    {
        // Capturar username e token da requisição
        $username = $request->header('username');
        $token = $request->header('token');

        // Verificar se os headers estão presentes
        if (!$username || !$token) {
            return response()->json([
                'error' => 'Missing required headers',
                'details' => [
                    'username' => 'Username header is required',
                    'token' => 'Token header is required'
                ]
            ], 400);
        }

        // Buscar a credencial apenas uma vez
        static $cachedCredential = null;

        if (!$cachedCredential || $cachedCredential->username !== $username) {
            $cachedCredential = Credential::where('username', $username)
                ->where('active', 1)
                ->first();
        }

        // Verificar se o usuário existe e está ativo
        if (!$cachedCredential || $token !== $cachedCredential->token) {
            return response()->json([
                'error' => 'Unauthorized',
                'details' => 'Invalid username, token or account is inactive'
            ], 403);
        }

        // Adicionar a credencial autenticada na requisição
        $request->attributes->add(['authenticated_credential' => $cachedCredential]);

        return $next($request);
    }
}

