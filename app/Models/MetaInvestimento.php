<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MetaInvestimento extends Model
{
    /** @use HasFactory<\Database\Factories\MetaInvestimentoFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'nome', 'valor', 'data_inicio', 'data_fim'];

    public function contribuicao(): HasMany
    {
        return $this->hasMany(ContribuicaoMeta::class);
    }
}
