<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoriaRequest extends CategoriaBaseRequest
{
    public function authorize(): bool
    {
        return true;
    }
}
