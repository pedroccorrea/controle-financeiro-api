<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParcelaCartao extends Model
{
    /** @use HasFactory<\Database\Factories\ParcelaCartaoFactory> */
    use HasFactory;

    protected $table = 'parcela_cartoes';

    protected $fillable = ['transacao_cartao_id', 'valor', 'data_vencimento', 'numero_parcela', 'status'];

    public function transacao() 
    {
        return $this->belongsTo(TransacaoCartao::class, 'transacao_cartao_id');
    }

    public function cartao()
    {
        return $this->hasOneThrough(Cartao::class, TransacaoCartao::class, 'id', 'id', 'transacao_cartao_id', 'cartao_id');
    }

    public function gastoDiario()
    {
        return $this->hasOneThrough(GastoDiario::class, TransacaoCartao::class, 'id', 'transacao_cartao_id', 'transacao_cartao_id', 'id');
    }
}
