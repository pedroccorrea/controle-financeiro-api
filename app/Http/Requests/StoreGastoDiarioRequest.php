<?php

namespace App\Http\Requests;

class StoreGastoDiarioRequest extends GastoDiarioBaseRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
