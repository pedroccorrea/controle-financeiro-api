<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContribuicaoMeta extends Model
{
    /** @use HasFactory<\Database\Factories\ContribuicaoMetaFactory> */
    use HasFactory;

    protected $fillable = ['meta_investimento_id', 'valor'];

    public function meta(): BelongsTo
    {
        return $this->belongsTo(MetaInvestimento::class, 'meta_investimento_id');
    }
}
