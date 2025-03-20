<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GastoDiario extends Model
{
    /** @use HasFactory<\Database\Factories\GastoDiarioFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'nome', 'categoria_id', 'transacao_cartao_id', 'valor'];

    protected static function booted()
    {
        static::deleting(function ($gastoDiario) {
            if($gastoDiario->transacaoCartao) {
                $gastoDiario->transacaoCartao->delete();
            }
        });
    }

    public function transacaoCartao() 
    {
        return $this->belongsTo(TransacaoCartao::class, 'transacao_cartao_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}
