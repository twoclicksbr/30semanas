<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonUserRequest;
use App\Models\PersonUserModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Mail\VerifyEmail;
use Carbon\Carbon;

class PersonUserController extends Controller
{   
    public function index(Request $request) : JsonResponse
    {
        // Obtém o id_credential da sessão
        $credentialId = Session::get('id_credential');

        // Obtém os parâmetros da requisição
        $id = $request->input('id');
        $id_person = $request->input('id_person');
        $email = $request->input('email');
        $active = $request->input('active');
        $email_verified = $request->input('email_verified');
        $last_login_start = $request->input('last_login_start');
        $last_login_end = $request->input('last_login_end');

        // Ordenação e Paginação
        $sortBy = $request->input('sort_by', 'id'); 
        $sortOrder = $request->input('sort_order', 'desc'); 
        $perPage = $request->input('per_page', 25); 

        // Constrói a consulta base
        $query = PersonUserModel::where('id_credential', $credentialId);

        // Filtros opcionais
        if (!empty($id)) {
            $idArray = explode(',', $id); 
            $query->whereIn('id', $idArray);
        }

        if (!empty($id_person)) {
            $query->where('id_person', $id_person);
        }

        if (!empty($email)) {
            $query->where('email', 'LIKE', '%' . $email . '%');
        }

        if (!is_null($active) && in_array($active, [0, 1])) {
            $query->where('active', $active);
        }

        if (!is_null($email_verified)) {
            if ($email_verified) {
                $query->whereNotNull('email_verified_at');
            } else {
                $query->whereNull('email_verified_at');
            }
        }

        if (!empty($last_login_start) && !empty($last_login_end)) {
            $query->whereBetween('last_login_at', [$last_login_start, $last_login_end]);
        }

        // Verifica se há um parâmetro 'created_at' na requisição e aplica o filtro
        $created_at_start = $request->query('created_at_start');
        $created_at_end = $request->query('created_at_end');
        if (isset($created_at_start) && isset($created_at_end)) {
            $query->whereBetween(DB::raw('DATE(created_at)'), [$created_at_start, $created_at_end]);
        }

        // Filtro de intervalo de datas para updated_at (com hora)
        $updated_at_start = $request->input('updated_at_start');
        $updated_at_end = $request->input('updated_at_end');

        if (!empty($updated_at_start) && !empty($updated_at_end)) {
            $query->whereBetween('updated_at', [$updated_at_start, $updated_at_end]);
        }

        // Aplicando ordenação e paginação
        $result = $query->orderBy($sortBy, $sortOrder)->paginate($perPage);

        return response()->json([
            'status' => true,
            'result' => $result,
            'search' => [
                'id' => $id,
                'id_person' => $id_person,
                'email' => $email,
                'active' => $active,
                'email_verified' => $email_verified,
                'last_login_start' => $last_login_start,
                'last_login_end' => $last_login_end,
                'sort_by' => $sortBy,
                'sort_order' => $sortOrder,
                'created_at_start' => $created_at_start,
                'created_at_end' => $created_at_end,
                'updated_at_start' => $updated_at_start,
                'updated_at_end' => $updated_at_end,
            ],
        ], 200);
    }

    public function show($id) : JsonResponse
    {
        try { 
            // Obtém o id_credential da sessão
            $credentialId = Session::get('id_credential');

            // Tente encontrar a credencial pelo ID e id_credential
            $result = PersonUserModel::where('id', $id)->where('id_credential', $credentialId)->firstOrFail();
            
            return response()->json([ 
                'status' => true, 
                'result' => $result, 
            ], 200);

        } catch (ModelNotFoundException $e) { 
            // Retorna uma mensagem personalizada caso o ID não seja encontrado 
            return response()->json([ 
                'status' => false, 
                'message' => 'Record not found.', 
            ], 404); 
        }
    }

    public function store(PersonUserRequest $request) : JsonResponse
    {
        DB::beginTransaction();

        try {
            
            // Obtém o id_credential da sessão
            $credentialId = Session::get('id_credential');
            
            $verificationToken = Str::random(60);
            
            $result = PersonUserModel::create([
                'id_credential' => $credentialId,
                'id_person' => $request->id_person,
                'email' => $request->email,
                'password' => $request->password,
                'active' => $request->active,
                'verification_token' => $verificationToken,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'result' => $result,
                'message' => "Record successfully registered.",
            ], 201);

        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => "Record not registered.",
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function update(PersonUserRequest $request, $id) : JsonResponse
    {
        DB::beginTransaction();

        try {
            $credentialId = Session::get('id_credential');
            $result = PersonUserModel::where('id', $id)->where('id_credential', $credentialId)->firstOrFail();
            
            $result->update([
                'id_person' => $request->id_person,
                'email' => $request->email,
                'password' => $request->password,
                'active' => $request->active,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'result' => $result,
                'message' => "Record successfully updated.",
            ], 200);
        } catch (ModelNotFoundException $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => "Record not found.",
            ], 404);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => "Record not updated.",
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function check_email($id) : JsonResponse
    {
        try {
            $credentialId = Session::get('id_credential');
            
            $user = PersonUserModel::with('person')->where('id', $id)->where('id_credential', $credentialId)->firstOrFail();

            if (!$user->person) {
                throw new Exception('Person relationship not found.');
            }
            
            // Envia o e-mail
            Mail::to($user->email)->send(new VerifyEmail($user));
            
            return response()->json([
                'status' => true,
                'message' => 'Verification email sent successfully.',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Record not found.',
            ], 404);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error sending email.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function validate_email($token)
    {
        try {
            $user = PersonUserModel::where('verification_token', $token)->firstOrFail();
            
            $user->email_verified_at = Carbon::now();
            $user->verification_token = null;
            $user->save();
            
            return view('validate_email_result', [
                'status' => true,
                'message' => 'E-mail validado com sucesso!'
            ]);
        } catch (ModelNotFoundException $e) {
            return view('validate_email_result', [
                'status' => false,
                'message' => 'Token inválido ou expirado.'
            ]);
        } catch (Exception $e) {
            return view('validate_email_result', [
                'status' => false,
                'message' => 'Erro ao validar e-mail. Tente novamente mais tarde.'
            ]);
        }
    }

    public function destroy($id) : JsonResponse
    {
        DB::beginTransaction();

        try {
            // Obtém o id_credential da sessão
            $credentialId = Session::get('id_credential');

            // Encontre a credencial pelo ID e id_credential
            $result = PersonUserModel::where('id', $id)->where('id_credential', $credentialId)->firstOrFail();

            // Deleta a credencial
            $result->delete();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => "Record successfully deleted.",
            ], 200);

        } catch (ModelNotFoundException $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'message' => "Record not found.",
            ], 404);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'message' => "Record not deleted.",
            ], 400);
        }
    }
}
