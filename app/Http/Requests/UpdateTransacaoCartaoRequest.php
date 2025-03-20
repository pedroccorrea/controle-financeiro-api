<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTransacaoCartaoRequest extends TransacaoCartaoBaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = parent::rules();

        return [
            'user_id' => 'sometimes|required|exists:users,id',
            'nome' => 'sometimes|required|min:3',
            'valor' => 'sometimes|required|numeric|decimal:0,2|min:0.01',
            'quantidade_parcelas' => 'sometimes|required|integer|min:1',
            'data' => 'sometimes|required|date'
        ];
    }
}
