<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransacaoCartao extends Model
{
    /** @use HasFactory<\Database\Factories\TransacaoCartaoFactory> */
    use HasFactory;
    protected $table = 'transacao_cartoes';
    protected $fillable = ['user_id', 'cartao_id', 'tipo', 'nome', 'valor', 'quantidade_parcelas', 'data'];

    protected static function booted()
    {
        static::deleting(function ($transacaoCartao) {
            $transacaoCartao->parcelas()->delete();
        });
    }

    public function parcelas() 
    {
        return $this->hasMany(ParcelaCartao::class, 'transacao_cartao_id');
    }
    public function cartao() 
    {
        return $this->belongsTo(Cartao::class, 'cartao_id');
    }
    public function gastoDiario() 
    {
        return $this->hasOne(GastoDiario::class, 'transacao_cartao_id');
    }
}
