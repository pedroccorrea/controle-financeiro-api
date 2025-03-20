<?php

namespace App\Http\Requests;

use App\Traits\apiResponseFormatter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class CategoriaBaseRequest extends FormRequest
{
    use apiResponseFormatter;
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'nome' => 'required'
        ];
    }

    public function messages() {
        return [
            'required' => 'O campo :attribute é obrigatório.',
        ];
    }
    
    protected function failedValidation(Validator $validator)
    {
        $response = $this->formatResponse(
            $validator->errors(),
            'Erro na validação dos dados',
            422
        );

        throw new \Illuminate\Validation\ValidationException($validator, $response);
    }
}
