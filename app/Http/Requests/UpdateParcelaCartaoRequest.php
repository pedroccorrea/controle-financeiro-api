<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateParcelaCartaoRequest extends TransacaoParcelaCartaoBaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'transacao_cartao_id' => 'sometimes|required|exists:transacao_cartoes,id',
            'valor' => 'sometimes|required|numeric|decimal:0,2|min:0.01',
            'data_vencimento' => 'sometimes|required|date',
            'status' => 'sometimes|required|boolean',
        ];
    }
}
