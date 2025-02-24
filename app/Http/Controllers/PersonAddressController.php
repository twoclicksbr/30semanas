<?php

namespace App\Http\Controllers;

use App\Http\Requests\PersonAddressRequest;
use App\Models\PersonAddressModel;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PersonAddressController extends Controller
{   

    public function index(Request $request) : JsonResponse
    {

        // Obtém o id_credential da sessão
        $credentialId = Session::get('id_credential');
        
        // Obtém os parâmetros da requisição
        $id = $request->input('id');

        $id_person = $request->input('id_person');
        $cep = $request->input('cep');
        $logradouro = $request->input('logradouro');
        $numero = $request->input('numero');
        $complemento = $request->input('complemento');
        $bairro = $request->input('bairro');
        $localidade = $request->input('localidade');
        $uf = $request->input('uf');
        $active = $request->input('active');

        // Ordenação e Paginação
        $sortBy = $request->input('sort_by', 'id'); 
        $sortOrder = $request->input('sort_order', 'desc'); 
        $perPage = $request->input('per_page', 25); 

        // Constrói a consulta base
        $query = PersonAddressModel::where('id_credential', $credentialId);

        // Filtros opcionais
        if (!empty($id)) {
            $idArray = explode(',', $id); 
            $query->whereIn('id', $idArray);
        }

        


        


        if (!empty($id_person)) {
            $query->where('id_person', $id_person);
        }
        
        if (!empty($cep)) {
            $query->where('cep', $cep);
        }
        
        if (!empty($logradouro)) {
            $query->where('logradouro', 'LIKE', '%' . $logradouro . '%');
        }
        
        if (!empty($numero)) {
            $query->where('numero', 'LIKE', '%' . $numero . '%');
        }
        
        if (!empty($complemento)) {
            $query->where('complemento', 'LIKE', '%' . $complemento . '%');
        }
        
        if (!empty($bairro)) {
            $query->where('bairro', 'LIKE', '%' . $bairro . '%');
        }
        
        if (!empty($localidade)) {
            $query->where('localidade', 'LIKE', '%' . $localidade . '%');
        }
        
        if (!empty($uf)) {
            $query->where('uf', $uf);
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
                'id_person' => $id_person,
                'cep' => $cep,
                'logradouro' => $logradouro,
                'numero' => $numero,
                'complemento' => $complemento,
                'bairro' => $bairro,
                'localidade' => $localidade,
                'uf' => $uf,
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
            $result = PersonAddressModel::where('id', $id)->where('id_credential', $credentialId)->firstOrFail();
            
            return response()->json([ 
                'status' => true, 
                'result' => $result, 
            ], 200);

        } catch (ModelNotFoundException $e) { 
            // Retorna uma mensagem person_addressalizada caso o ID não seja encontrado 
            return response()->json([ 
                'status' => false, 
                'message' => 'Record not found.', 
            ], 404); 
        }
    }

    public function store(PersonAddressRequest $request) : JsonResponse
    {
        DB::beginTransaction();

        try {
            // Obtém o id_credential da sessão
            $credentialId = Session::get('id_credential');

            if (is_null($credentialId)) {
                throw new Exception('Credential ID not found in session');
            }
            
            $result = PersonAddressModel::create([

                'id_credential' => $credentialId,
                'id_person' => $request->id_person,
                'cep' => $request->cep,
                'logradouro' => $request->logradouro,
                'numero' => $request->numero,
                'complemento' => $request->complemento,
                'bairro' => $request->bairro,
                'localidade' => $request->localidade,
                'uf' => $request->uf,
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
            ], 400);
        }
    }

    public function update(PersonAddressRequest $request, $id) : JsonResponse
    {
        DB::beginTransaction();

        try {

            // Obtém o id_credential da sessão
            $credentialId = Session::get('id_credential');

            // Encontre a credencial pelo ID e id_credential
            $result = PersonAddressModel::where('id', $id)->where('id_credential', $credentialId)->firstOrFail();

            // Atualize os campos da credencial
            $result->update([
                
                'id_person' => $request->id_person,
                'cep' => $request->cep,
                'logradouro' => $request->logradouro,
                'numero' => $request->numero,
                'complemento' => $request->complemento,
                'bairro' => $request->bairro,
                'localidade' => $request->localidade,
                'uf' => $request->uf,
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
                // 'error' => $e->getMessage(), // Adicionando mensagem de erro detalhada
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

    public function distroy($id) : JsonResponse
    {
        DB::beginTransaction();

        try {
            // Obtém o id_credential da sessão
            $credentialId = Session::get('id_credential');

            // Encontre a credencial pelo ID e id_credential
            $result = PersonAddressModel::where('id', $id)->where('id_credential', $credentialId)->firstOrFail();

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
