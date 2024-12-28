<?php
namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProgramRequest extends FormRequest
{
    public function rules()
    {
        return [
            'offset' => 'required|integer|min:0',
            'count' => 'required|integer|min:1|max:100',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation Error',
            'errors' => $validator->errors(),
        ], 422));
    }
}