<?php

namespace App\Http\Requests;

use App\Traits\ApiResponseFormatter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class TransacaoParcelaCartaoBaseRequest extends FormRequest
{
    use ApiResponseFormatter;
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'transacao_cartao_id' => 'required|exists:transacao_cartoes,id',
            'valor' => 'required|numeric|decimal:0,2|min:0.01',
            'data_vencimento' => 'required|date',
            'numero_parcela' => 'required|integer',
            'status' => 'required|boolean',
        ];
    }

    public function messages() {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'valor.decimal' => 'O campo valor deve ser um número com no máximo 2 casas decimais.',
            'valor.numeric' => 'O Campo valor deve ser um número válido.',
            'valor.min' => 'O Campo valor não pode ser zerado.',
            'data_vencimento.date' => 'O campo data deve ser uma data válida no formato YYYY-MM-DD.',
            'status.boolean' => 'O campo "status" deve ser verdadeiro (true) ou falso (false).',
            'numero_parcela.integer' => 'O numero da parcela deve ser um número inteiro.'
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
