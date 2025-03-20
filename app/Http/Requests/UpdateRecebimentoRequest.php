<?php

namespace App\Http\Requests;

class UpdateRecebimentoRequest extends RecebimentoBaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = parent::rules();

        return [
            'user_id' => 'sometimes|required',
            'nome' => 'sometimes|required|min:3',
            'valor' => 'sometimes|required|numeric|decimal:0,2|min:0.01'
        ];
    }
    
}
