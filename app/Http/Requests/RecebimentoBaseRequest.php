<?php

namespace App\Http\Requests;

use App\Traits\apiResponseFormatter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class RecebimentoBaseRequest extends FormRequest
{
    use apiResponseFormatter;
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required',
            'nome' => 'required|min:3',
            'valor' => 'required|numeric|decimal:0,2|min:0.01'
        ];
    }

    public function messages() {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'valor.decimal' => 'O campo valor deve ser um número com no máximo 2 casas decimais.',
            'valor.numeric' => 'O capo valor deve ser um número válido.',
            'valor.min' => 'O capo valor não pode ser zerado.'
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
