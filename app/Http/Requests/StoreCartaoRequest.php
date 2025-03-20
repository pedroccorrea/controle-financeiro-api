<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCartaoRequest extends CartaoBaseRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
