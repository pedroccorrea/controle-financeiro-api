<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateGastoDiarioRequest extends GastoDiarioBaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules=parent::rules();
        return [
            'user_id' => 'sometimes|required',
            'nome' => 'sometimes|required|min:3',
            'categoria_id' => 'sometimes|required|exists:categorias,id',
            'valor' => 'sometimes|required|numeric|decimal:0,2|min:0.01',
            'transacao_cartao_id' => 'sometimes|exists:transacao_cartoes,id',
        ];
    }
}
