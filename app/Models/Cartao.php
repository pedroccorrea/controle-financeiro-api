<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cartao extends Model
{
    /** @use HasFactory<\Database\Factories\CartaoFactory> */
    use HasFactory;

    protected $table = 'cartoes';
    protected $fillable = ['user_id', 'nome', 'limite', 'data_vencimento'];

    public function transacoes() 
    {
        return $this->hasMany(TransacaoCartao::class);
    }
}
