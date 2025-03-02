<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShareRequest;
use App\Models\ShareModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ShareController extends Controller
{   
    public function index(Request $request) : JsonResponse
    {
        // Obtém o id_credential da sessão
        $credentialId = Session::get('id_credential');

        // Obtém os parâmetros da requisição
        $id = $request->input('id');
        $name = $request->input('name');
        $id_gender = $request->input('id_gender');
        $id_person = $request->input('id_person');
        $link = $request->input('link');
        $active = $request->input('active');

        // Ordenação e Paginação
        $sortBy = $request->input('sort_by', 'id'); 
        $sortOrder = $request->input('sort_order', 'desc'); 
        $perPage = $request->input('per_page', 25); 

        // Constrói a consulta base
        $query = ShareModel::where('id_credential', $credentialId);

        // Filtros opcionais
        if (!empty($id)) {
            $idArray = explode(',', $id); 
            $query->whereIn('id', $idArray);
        }

        if (!empty($name)) {
            $query->where('name', 'LIKE', '%' . $name . '%');
        }

        if (!empty($id_gender)) {
            $id_genderArray = explode(',', $id_gender); 
            $query->whereIn('id_gender', $id_genderArray);
        }

        if (!empty($id_person)) {
            $id_personArray = explode(',', $id_person); 
            $query->whereIn('id_person', $id_personArray);
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
            'result' => $result,
            'search' => [
                'id' => $id,
                'name' => $name,
                'id_gender' => $id_gender,
                'id_person' => $id_person,
                'link' => $link,
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
            // Obtém o id_credential da sessão
            $credentialId = Session::get('id_credential');

            // Tente encontrar a credencial pelo ID e id_credential
            $result = ShareModel::where('id', $id)->where('id_credential', $credentialId)->firstOrFail();
            
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

    public function store(ShareRequest $request) : JsonResponse
    {
        DB::beginTransaction();

        try {
            // Obtém o id_credential da sessão
            $credentialId = Session::get('id_credential');

            if (is_null($credentialId)) {
                throw new Exception('Credential ID not found in session');
            }
            
            $result = ShareModel::create([
                'id_credential' => $credentialId,
                'name' => $request->name,
                'id_gender' => $request->id_gender,
                'id_person' => $request->id_person,
                'link' => $request->link,
                'active' => $request->active,
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
                'error' => $e->getMessage(), // Inclui a mensagem de erro
            ], 400);
        }

    }

    public function update(ShareRequest $request, $id) : JsonResponse
    {
        DB::beginTransaction();

        try {
            // Obtém o id_credential da sessão
            $credentialId = Session::get('id_credential');

            // Encontre a credencial pelo ID e id_credential
            $result = ShareModel::where('id', $id)->where('id_credential', $credentialId)->firstOrFail();

            // Atualize os campos da credencial
            $result->update([
                'name' => $request->name,
                'id_gender' => $request->id_gender,
                'id_person' => $request->id_person,
                'link' => $request->link,
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
            ], 400);
        }
    }

    public function destroy($id) : JsonResponse
    {
        DB::beginTransaction();

        try {
            // Obtém o id_credential da sessão
            $credentialId = Session::get('id_credential');

            // Encontre a credencial pelo ID e id_credential
            $result = ShareModel::where('id', $id)->where('id_credential', $credentialId)->firstOrFail();

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
