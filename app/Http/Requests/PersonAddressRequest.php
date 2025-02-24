<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PersonAddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'erros' => $validator->errors(),
        ], 422));
    }

    public function rules(): array
    {
        // Recuperar o id do usuário enviado na URL
        // $personAddressId = $this->route('person_address');

        return [

            'id_person' => 'required|max:11',
            'cep' => 'required|max:255',
            'logradouro' => 'required|string',
            'numero' => 'required|string',
            'complemento' => 'nullable|string',
            'bairro' => 'required|string',
            'localidade' => 'required|string',
            'uf' => 'required|string',
            'active' => 'required|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [

            'id_person.required' => "The id_person field is required.",
            'id_person.max' => "The id_person must not be greater than :max characters.",
            
            'cep.required' => "The cep field is required.",
            'cep.max' => "The cep must not be greater than :max characters.",

            'logradouro.required' => "The logradouro field is required.",
            'logradouro.max' => "The logradouro must not be greater than :max characters.",
            
            'numero.required' => "The numero field is required.",
            'numero.max' => "The numero must not be greater than :max characters.",
            
            'complemento.max' => "The complemento must not be greater than :max characters.",
            
            'bairro.required' => "The bairro field is required.",
            'bairro.max' => "The bairro must not be greater than :max characters.",
            
            'localidade.required' => "The localidade field is required.",
            'localidade.max' => "The localidade must not be greater than :max characters.",
            
            'uf.required' => "The uf field is required.",
            'uf.max' => "The uf must not be greater than :max characters.",

            'active.required' => "The active field is required.",
            'active.in' => "The active field can only accept values 0 or 1.",
        ];
    }
}