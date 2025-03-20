<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreParcelaCartaoRequest extends TransacaoParcelaCartaoBaseRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
