<?php

namespace App\Http\Requests;

use App\Traits\ApiResponseFormatter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class CartaoBaseRequest extends FormRequest
{
    use ApiResponseFormatter;
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'required',
            'limite' => 'required|numeric|decimal:0,2|min:0.01',
            'data_vencimento' => 'required|date_format:Y-m-d',
        ];
    }

    public function messages() {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'limite.decimal' => 'O campo limite deve ser um número com no máximo 2 casas decimais.',
            'limite.numeric' => 'O Campo limite deve ser um número válido.',
            'limite.min' => 'O Campo limite não pode ser zerado.',
            'date_format' => 'O campo :attribute deve ser uma data válida no formato Y-m-d.',
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
