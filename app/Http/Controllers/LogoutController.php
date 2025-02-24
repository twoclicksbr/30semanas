<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;

class LogoutController extends Controller
{
    // Função para realizar o logout
    public function logout(): JsonResponse
    {
        // Remove a sessão auth_id_person
        Session::forget('auth_id_person');

        // Retorna resposta de sucesso
        return response()->json([
            'status' => true,
            'message' => 'Logout successful.',
        ], 200);
    }
}
