<?php

namespace App\Http\Controllers;

use App\Http\Requests\CredentialRequest;
use App\Models\CredentialModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CredentialController extends Controller
{   

    public function index(Request $request) : JsonResponse
    {
        // Obtém os parâmetros da requisição
        $id = $request->input('id');
        $username = $request->input('username');
        $token = $request->input('token');
        $active = $request->input('active');

        // Ordenação e Paginação
        $sortBy = $request->input('sort_by', 'id'); 
        $sortOrder = $request->input('sort_order', 'desc'); 
        $perPage = $request->input('per_page', 25); 

        // Constrói a consulta base
        $query = CredentialModel::query();

        // Filtros opcionais
        if (!empty($id)) {
            $idArray = explode(',', $id); 
            $query->whereIn('id', $idArray);
        }

        if (!empty($username)) {
            $query->where('username', 'LIKE', '%' . $username . '%');
        }

        if (!empty($token)) {
            $query->where('token', 'LIKE', '%' . $token . '%');
        }

        if (!is_null($active) && in_array($active, [0, 1])) {
            $query->where('active', $active);
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
            'credential' => $result,
            'search' => [
                'id' => $id,
                'username' => $username,
                'token' => $token,
                'active' => $active,
                
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
            // Tente encontrar a credencial pelo ID. 
            $result = CredentialModel::findOrFail($id);
            
            return response()->json([ 
                'status' => true, 
                'credential' => $result, 
            ], 200);

        } catch (ModelNotFoundException $e) { 
            // Retorna uma mensagem personalizada caso o ID não seja encontrado 
            return response()->json([ 
                'status' => false, 
                'message' => 'Credential not found.', 
            ], 404); 
        }
    }

    public function store(CredentialRequest $request) : JsonResponse
    {
        DB::beginTransaction();

        try {
            
            $result = CredentialModel::create([
                'username' => $request->username,
                'token' => $request->token,
                'active' => $request->active,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'credential' => $result,
                'message' => "Record successfully registered.",
            ], 201);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'message' => "Record not registered.",
            ], 400);
        }
    }

    public function update(CredentialRequest $request, $id) : JsonResponse
    {
        DB::beginTransaction();

        try {
            // Encontre a credencial pelo ID
            $result = CredentialModel::findOrFail($id);

            // Atualize os campos da credencial
            $result->update([
                'username' => $request->username,
                'token' => $request->token,
                'active' => $request->active,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'credential' => $result,
                'message' => "Record successfully updated.",
            ], 200);

        } catch (ModelNotFoundException $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'message' => "Credential not found.",
            ], 404);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'message' => "Record not updated.",
            ], 400);
        }
    }

    public function distroy($id) : JsonResponse
    {
        DB::beginTransaction();

        try {
            // Encontre a credencial pelo ID
            $result = CredentialModel::findOrFail($id);

            // Deleta a credencial
            $result->delete();

            // Atualiza o campo del para 1
            // $result->active = 0;
            // $result->del = 1;
            // $result->save();

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => "Record successfully deleted.",
            ], 200);

        } catch (ModelNotFoundException $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'message' => "Credential not found.",
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
