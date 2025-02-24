<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class CredentialRequest extends FormRequest
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
        $credentialId = $this->route('credential');

        return [
            'username' => 'required|unique:credential,username,'.$credentialId.'|max:255|regex:/^\S*$/',
            'token' => 'required',
            'active' => 'required|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [
            'username.required' => "The username field is required.",
            'username.unique' => "The username is already in use.",
            'username.regex' => "The username field cannot contain spaces.",
            'username.max' => "The username must not be greater than :max characters.",
            'token.required' => "The token field is required.",
            'active.required' => "The active field is required.",
            'active.in' => "The active field can only accept values 0 or 1.",
        ];
    }
}