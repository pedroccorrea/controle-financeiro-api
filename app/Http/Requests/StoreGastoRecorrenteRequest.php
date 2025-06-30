<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGastoRecorrenteRequest extends GastoRecorrenteBaseRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
