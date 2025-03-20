<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMetaInvestimentoRequest extends MetaInvestimentoBaseRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
