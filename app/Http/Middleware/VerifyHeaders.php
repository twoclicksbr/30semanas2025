<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Credential;

class VerifyHeaders
{
    public function handle(Request $request, Closure $next)
    {
        $username = $request->header('username');
        $token = $request->header('token');

        if (!$username || !$token) {
            return response()->json([
                'status' => false,
                'message' => 'Headers username and token are required and cannot be empty',
            ], 400);
        }

        // Buscar a credencial no banco
        $credential = Credential::where('username', $username)
            ->where('token', $token)
            ->where('active', 1)
            ->first();

        if (!$credential) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid username or token',
            ], 401);
        }

        // Verifica se a requisição é para a tabela credential e exige can_request = 1
        if ($request->is('api/v1/credential*') && $credential->can_request != 1) {
            return response()->json([
                'status' => false,
                'message' => 'Permission denied: cannot access credential resources',
            ], 403);
        }

        // Armazena o ID da credencial na sessão
        Session::put('id_credential', $credential->id);

        $response = $next($request);

        // Limpa o ID da sessão após a resposta ser enviada
        Session::forget('id_credential');

        return $response;
    }
}
