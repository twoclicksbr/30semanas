<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonRequest;
use App\Models\PersonModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PersonController extends Controller
{   

    public function index(Request $request) : JsonResponse
    {

        // Obtém o id_credential da sessão
        $credentialId = Session::get('id_credential');
        
        // Obtém os parâmetros da requisição
        $id = $request->input('id');

        $name = $request->input('name');
        $id_gender = $request->integer('id_gender');
        $cpf = $request->input('cpf');
        $dt_nascimento = $request->input('dt_nascimento');
        $whatsapp = $request->input('whatsapp');
        $email = $request->input('email');
        $eklesia = $request->input('eklesia');

        $active = $request->input('active');

        // Ordenação e Paginação
        $sortBy = $request->input('sort_by', 'id'); 
        $sortOrder = $request->input('sort_order', 'desc'); 
        $perPage = $request->input('per_page', 25); 

        // Constrói a consulta base
        $query = PersonModel::where('id_credential', $credentialId);

        // Filtros opcionais
        if (!empty($id)) {
            $idArray = explode(',', $id); 
            $query->whereIn('id', $idArray);
        }

        if (!empty($id_gender)) {
            $query->whereIn('id_gender', $id_gender);
        }

        if (!empty($name)) {
            $query->where('name', 'LIKE', '%' . $name . '%');
        }

        if (!empty($cpf)) {
            $query->where('cpf', $cpf);
        }

        if (!empty($dt_nascimento)) {
            $query->where('dt_nascimento', $dt_nascimento);
        }
        
        if (!empty($whatsapp)) {
            $query->where('whatsapp', 'LIKE', '%' . $whatsapp . '%');
        }
        
        if (!empty($email)) {
            $query->where('email', 'LIKE', '%' . $email . '%');
        }
        
        if (!empty($eklesia)) {
            $query->where('eklesia', $eklesia);
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
                'cpf' => $cpf,
                'dt_nascimento' => $dt_nascimento,
                'whatsapp' => $whatsapp,
                'email' => $email,
                'eklesia' => $eklesia,
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
            $result = PersonModel::where('id', $id)->where('id_credential', $credentialId)->firstOrFail();
            
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

    public function store(PersonRequest $request) : JsonResponse
    {
        DB::beginTransaction();

        try {
            // Obtém o id_credential da sessão
            $credentialId = Session::get('id_credential');

            if (is_null($credentialId)) {
                throw new Exception('Credential ID not found in session');
            }
            
            $result = PersonModel::create([

                'id_credential' => $credentialId,
                'name' => $request->name,
                'id_gender' => $request->id_gender,
                'cpf' => $request->cpf,
                'dt_nascimento' => $request->dt_nascimento,
                'whatsapp' => $request->whatsapp,
                'email' => $request->email,
                'eklesia' => $request->eklesia,
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
                'error' => $e->getMessage(), // Adicionando mensagem de erro detalhada
            ], 400);
        }
    }

    public function update(PersonRequest $request, $id) : JsonResponse
    {
        DB::beginTransaction();

        try {

            // Obtém o id_credential da sessão
            $credentialId = Session::get('id_credential');

            // Encontre a credencial pelo ID e id_credential
            $result = PersonModel::where('id', $id)->where('id_credential', $credentialId)->firstOrFail();

            // Atualize os campos da credencial
            $result->update([
                'name' => $request->name,
                'id_gender' => $request->id_gender,
                'cpf' => $request->cpf,
                'dt_nascimento' => $request->dt_nascimento,
                'whatsapp' => $request->whatsapp,
                'email' => $request->email,
                'eklesia' => $request->eklesia,
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
                'error' => $e->getMessage(), // Adicionando mensagem de erro detalhada
            ], 404);

        } catch (Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => false,
                'message' => "Record not updated.",
                'error' => $e->getMessage(), // Adicionando mensagem de erro detalhada
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
            $result = PersonModel::where('id', $id)->where('id_credential', $credentialId)->firstOrFail();

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
