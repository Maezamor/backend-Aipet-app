<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class SelterCreateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'picture' => ["required", "file", 'mimes:jpg,png,jpeg','max:2048'],
            'city' => ['required'],
            'address' => ['required', 'string'],
            'description' => ['nullable'],
            'sosial_media_1' => ['nullable'],
            'sosial_media_2' => ['nullable'],
            'sosial_media_3' => ['nullable'],
            'phone' => ['required'],
            'let' => ['required'],
            'lon' => ['required'],
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response([
            "errors" => $validator->getMessageBag()
        ], 400));
    }
}
