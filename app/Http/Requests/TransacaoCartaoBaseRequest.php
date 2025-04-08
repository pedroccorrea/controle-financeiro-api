<?php

namespace App\Http\Requests;

use App\Traits\ApiResponseFormatter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class TransacaoCartaoBaseRequest extends FormRequest
{
    use ApiResponseFormatter;
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'cartao_id' => 'required|exists:cartoes,id',
            'tipo' => 'required|in:1,2',
            'valor' => 'required|numeric|decimal:0,2|min:0.01',
            'quantidade_parcelas' => 'integer',
            'data' => 'required|date'
        ];
    }

    public function messages() {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'valor.decimal' => 'O campo valor deve ser um número com no máximo 2 casas decimais.',
            'valor.numeric' => 'O capo valor deve ser um número válido.',
            'valor.min' => 'O Campo valor não pode ser zerado.',
            'data.date' => 'O campo data deve ser uma data válida no formato YYYY-MM-DD.'
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
