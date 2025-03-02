<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class VideoRequest extends FormRequest
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
        $videoId = $this->route('video');

        return [

            'name' => 'required|unique:video,name,'.$videoId.'|max:255',
            'date' => 'required|date|after_or_equal:1900-01-01|before_or_equal:today',
            'link' => 'required|unique:video,link,'.$videoId.'|max:255',
            'active' => 'required|in:0,1',
        ];
    }

    public function messages(): array
    {
        return [

            'name.required' => "The name field is required.",
            'name.unique' => "The name is already in use.",
            'name.max' => "The name must not be greater than :max characters.",
            
            'date.required' => "The date field is required.",
            'date.date' => "The date must be a valid date format.",
            'date.after_or_equal' => "The date must be after or equal to January 1, 1900.",
            'date.before_or_equal' => "The date must not be in the future.",

            'link.required' => "The link field is required.",
            'link.unique' => "The link is already in use.",
            'link.max' => "The link must not be greater than :max characters.",

            'active.required' => "The active field is required.",
            'active.in' => "The active field can only accept values 0 or 1.",
        ];
    }
}