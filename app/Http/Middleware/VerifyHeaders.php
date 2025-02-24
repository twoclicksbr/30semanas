<?php

namespace App\Http\Middleware;

use App\Models\CredentialModel;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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

        // Verifica se o username e token existem no banco de dados
        $credential = CredentialModel::where('username', $username)->where('token', $token)->where('active', 1)->first();

        if (!$credential) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid username or token',
            ], 401);
        }

        // Armazena o ID da credencial na sessão
        Session::put('id_credential', $credential->id);

        $response = $next($request);

        // Limpa o ID da sessão após a resposta ser enviada
        Session::forget('id_credential');

        return $response;
    }
}