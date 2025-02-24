<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class TypePersonRequest extends FormRequest
{
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
        $type_personId = $this->route('type_person');

        return [

            'name' => 'required|unique:type_person,name,'.$type_personId.'max:255',
            'active' => 'required|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [

            'name.required' => "The name field is required.",
            'name.unique' => "The name is already in use.",
            'name.max' => "The name must not be greater than :max characters.",

            'active.required' => "The active field is required.",
            'active.in' => "The active field can only accept values 0 or 1.",
        ];
    }
}