<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    //set access to public 
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    //set validate request
    public function rules(): array
    {
        return [
            'username' => ['required', 'max:100'],
            'name' => ['required', 'max:100'],
            'address' => ['required', 'max:200'],
            'phone' => ['required', 'max:15'],
            'email' => ['required', 'max:50', 'email'],
            'password' => ['required', 'max:100'],

        ];
    }

    // retrun errors of there unvalidation

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            "errors" => $validator->getMessageBag()
        ], 400));
    }
}
