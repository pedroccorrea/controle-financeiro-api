<?php

namespace App\Http\Requests;

class StoreContribuicaoMetaRequest extends ContribuicaoMetaBaseRquest
{
    public function authorize(): bool
    {
        return true;
    }
}
