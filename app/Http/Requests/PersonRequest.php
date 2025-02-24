<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class PersonRequest extends FormRequest
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
        $personId = $this->route('person');
        $credentialId = session('id_credential');

        $rules = [
            'name' => 'required|max:255',
            'id_gender' => 'required|exists:gender,id',
            
            'cpf' => [
                'required',
                'max:11',
                Rule::unique('person', 'cpf')->ignore($personId)->where(function ($query) use ($credentialId) {
                    return $query->where('id_credential', $credentialId);
                }),
            ],
            
            'dt_nascimento' => 'required|max:10',
            
            'email' => [
                'required',
                'max:255',
                Rule::unique('person', 'email')->ignore($personId)->where(function ($query) use ($credentialId) {
                    return $query->where('id_credential', $credentialId);
                }),
            ],

            'eklesia' => [
                'required',
                'regex:/^\d+$/',
                Rule::unique('person', 'eklesia')->ignore($personId)->where(function ($query) use ($credentialId) {
                    return $query->where('id_credential', $credentialId);
                }),
            ],
            
            'active' => 'required|in:0,1',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => "The name field is required.",
            'name.max' => "The name must not be greater than :max characters.",
            
            'id_gender.required' => "The id_gender field is required.",
            'id_gender.exists' => "The selected id_gender is invalid.",
            
            'cpf.required' => "The cpf field is required.",
            'cpf.unique' => "The cpf is already in use.",
            'cpf.max' => "The cpf must not be greater than :max characters.",
            
            'dt_nascimento.required' => "The dt_nascimento field is required.",
            'dt_nascimento.max' => "The dt_nascimento must not be greater than :max characters.",
            
            'whatsapp.required' => "The whatsapp field is required.",
            'whatsapp.unique' => "The whatsapp is already in use.",
            'whatsapp.max' => "The whatsapp must not be greater than :max characters.",
            
            'email.required' => "The email field is required.",
            'email.unique' => "The email is already in use.",
            'email.max' => "The email must not be greater than :max characters.",
            
            'eklesia.required' => 'The eklesia field is required.',
            'eklesia.unique' => 'The eklesia value is already in use.',
            'eklesia.regex' => 'The eklesia field must contain only numbers without spaces.',
            
            'active.required' => "The active field is required.",
            'active.in' => "The active field can only accept values 0 or 1.",
        ];
    }
}
