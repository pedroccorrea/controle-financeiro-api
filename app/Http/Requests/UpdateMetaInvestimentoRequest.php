<?php

namespace App\Http\Requests;

class UpdateMetaInvestimentoRequest extends MetaInvestimentoBaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules():array 
    {
        $rules = parent::rules();

        return [
                'nome' => 'sometimes|required|min:2', 
                'valor' => 'sometimes|required|decimal:0,2', 
                'data_inicio' => 'sometimes|required',
                'data_fim'=> 'sometimes|required'
            ];
    }
}
