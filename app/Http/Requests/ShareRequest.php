<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ShareRequest extends FormRequest
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
        $shareId = $this->route('share');

        return [
            'name' => 'required|unique:share,name,'.$shareId.'|max:255',
            'id_gender' => 'required|exists:gender,id',
            'id_person' => 'required|exists:person,id',
            'link' => 'required|unique:share,link,'.$shareId.'|max:255',
            'active' => 'required|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            
            'name.required' => "The name field is required.",
            'name.unique' => "The name is already in use.",
            'name.max' => "The name must not be greater than :max characters.",

            'id_gender.required' => "The id_gender field is required.",
            'id_gender.exists' => "The selected id_gender is invalid.",

            'id_person.required' => "The id_person field is required.",
            'id_person.exists' => "The selected id_person is invalid.",
            
            'link.required' => "The link field is required.",
            'link.unique' => "The link is already in use.",
            'link.max' => "The link must not be greater than :max characters.",

            'active.required' => "The active field is required.",
            'active.in' => "The active field can only accept values 0 or 1.",
        ];
    }
}