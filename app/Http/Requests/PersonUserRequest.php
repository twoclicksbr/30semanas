<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PersonUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ], 422));
    }

    public function rules(): array
    {
        // Recuperar o id do usuário enviado na URL
        $person_userId = $this->route('person_user');

        return [
            'id_person' => 'required|integer',
            'email' => 'required|email|max:255|unique:person_user,email,' . $person_userId,
            'password' => 'required|min:8|max:255',
            'active' => 'required|in:0,1',

            // Novos campos adicionados
            'email_verified_at' => 'nullable|date',
            'verification_token' => 'nullable|string|max:255',
            'password_reset_token' => 'nullable|string|max:255',
            'last_login_at' => 'nullable|date',
        ];
    }

    public function messages(): array
    {
        return [
            'id_person.required' => "The id_person field is required.",
            'id_person.integer' => "The id_person must be an integer.",

            'email.required' => "The email field is required.",
            'email.email' => "The email must be a valid email address.",
            'email.unique' => "The email is already in use.",
            'email.max' => "The email must not be greater than :max characters.",

            'password.required' => "The password field is required.",
            'password.min' => "The password must be at least :min characters.",
            'password.max' => "The password must not be greater than :max characters.",

            'active.required' => "The active field is required.",
            'active.in' => "The active field can only accept values 0 or 1.",

            // Mensagens para os novos campos
            'email_verified_at.date' => "The email_verified_at must be a valid date.",
            
            'verification_token.string' => "The verification_token must be a string.",
            'verification_token.max' => "The verification_token must not be greater than :max characters.",
            
            'password_reset_token.string' => "The password_reset_token must be a string.",
            'password_reset_token.max' => "The password_reset_token must not be greater than :max characters.",
            
            'last_login_at.date' => "The last_login_at must be a valid date.",
        ];
    }
}
