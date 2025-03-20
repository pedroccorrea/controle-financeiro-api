<?php

namespace App\Http\Requests;

use App\Traits\apiResponseFormatter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class ContribuicaoMetaBaseRquest extends FormRequest
{
    use apiResponseFormatter;
    
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'meta_investimento_id' => 'required|exists:meta_investimentos,id',
            'valor' => 'required|numeric|decimal:0,2|min:0.01'
        ];
    }

    public function messages() {
        return [
            'required' => 'O campo :attribute é obrigatório.',
            'meta_investimento_id.exists' => 'Meta não encontrada. Informe uma meta valida.',
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
