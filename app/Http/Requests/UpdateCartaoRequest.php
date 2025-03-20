<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCartaoRequest extends CartaoBaseRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = parent::rules();
        return [
            'nome' => 'sometimes|required',
            'limite' => 'sometimes|required|numeric|decimal:0,2|min:0.01',
            'data_vencimento' => 'sometimes|required|date_format:Y-m-d',
        ];
    }
}
