<?php

namespace App\Http\Requests;

class StoreRecebimentoRequest extends RecebimentoBaseRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
