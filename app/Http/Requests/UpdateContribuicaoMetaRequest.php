<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContribuicaoMetaRequest extends ContribuicaoMetaBaseRquest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        $rules = parent::rules();
        return [
            'meta_investimento_id' => 'required|exists:meta_investimentos,id',
            'valor' => 'required|numeric|decimal:0,2|min:0.01'
        ];
    }
}
