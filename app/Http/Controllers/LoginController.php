<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\PersonUserModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log; // Importa o Log
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class LoginController extends Controller
{
    // Função para realizar o login
    public function login(LoginRequest $request): JsonResponse
    {
        Log::info('Login route reached', ['request' => $request->all()]); // Log inicial

        DB::beginTransaction(); // Inicia a transação no banco

        try {
            $validated = $request->validated(); // Valida os dados recebidos
            Log::info('Request validated', ['validated' => $validated]);

            // Obtém o id_credential da sessão definido pelo middleware
            $credentialId = Session::get('id_credential');
            Log::info('Session id_credential', ['id_credential' => $credentialId]);

            // Busca o usuário ativo pelo id_credential, email e active = 1
            $user = PersonUserModel::where('id_credential', $credentialId)
                ->where('email', $validated['email'])
                ->where('active', 1)
                ->firstOrFail();
            Log::info('User found', ['user' => $user]);

            // Verifica se a senha fornecida está correta
            if (!Hash::check($validated['password'], $user->password)) {
                Log::warning('Invalid password attempt', ['email' => $validated['email']]);
                throw new Exception('Invalid email or password');
            }

            // Cria sessão com o id_person usando o nome auth_id_person
            Session::put('auth_id_person', $user->id_person);
            Log::info('Session created', ['auth_id_person' => $user->id_person]);

            DB::commit(); // Confirma a transação

            // Retorna resposta de sucesso
            return response()->json([
                'status' => true,
                'message' => 'Login successful.',
            ], 200);

        } catch (ModelNotFoundException $e) {
            DB::rollback(); // Reverte a transação em caso de erro
            Log::error('User not found', ['error' => $e->getMessage()]);

            // Retorna erro caso o usuário não seja encontrado
            return response()->json([
                'status' => false,
                'message' => 'Invalid user credentials.',
                'error' => $e->getMessage(),
            ], 404);

        } catch (Exception $e) {
            DB::rollback(); // Reverte a transação em caso de erro
            Log::error('Login failed', ['error' => $e->getMessage()]);

            // Retorna erro genérico em caso de falha no login
            return response()->json([
                'status' => false,
                'message' => 'Login failed.',
                'error' => $e->getMessage(),
            ], 401);
        }
    }
}
