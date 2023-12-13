<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SelterUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() != null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required'],
            'name' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string'],
            'picture' => ['nullable','file','mimes:jpg,png,jpeg','max:2048'],
            'city' => ['nullable'],
            'description' => ['nullable'],
            'sosial_media_1' => ['nullable'],
            'sosial_media_2' => ['nullable'],
            'sosial_media_3' => ['nullable'],
            'phone' => ['nullable'],
            'lon' => ['nullable'],
            'let' => ['nullable']
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            "errors" => $validator->getMessageBag()
        ], 400));
    }
}
