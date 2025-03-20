<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTransacaoCartaoRequest extends TransacaoCartaoBaseRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
