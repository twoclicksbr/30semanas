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
            'erros' => $validator->errors(),
        ], 422));
    }

    public function rules(): array
    {
        // Recuperar o id do usuário enviado na URL
        $person_userId = $this->route('person_user');

        return [

            'id_person' => 'required|max:11',
            'email' => 'required|unique:person_user,email,'.$person_userId.'max:255',
            'password' => 'required|max:255',
            'active' => 'required|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [

            'id_person.required' => "The id_person field is required.",
            'id_person.max' => "The id_person must not be greater than :max characters.",

            'email.required' => "The email field is required.",
            'email.unique' => "The email is already in use.",
            'email.max' => "The email must not be greater than :max characters.",

            'password.required' => "The password field is required.",
            'password.max' => "The password must not be greater than :max characters.",

            'active.required' => "The active field is required.",
            'active.in' => "The active field can only accept values 0 or 1.",
        ];
    }
}