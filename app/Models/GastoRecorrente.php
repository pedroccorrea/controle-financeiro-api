<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GastoRecorrente extends Model
{
    /** @use HasFactory<\Database\Factories\GastoRecorrenteFactory> */
    use HasFactory;

    protected $fillable = ['nome', 'valor', 'data_vencimento', 'user_id'];

    public function rules() {
        return [
                'nome' => 'required|min:2', 
                'valor' => 'required|decimal:0,2',
                'data_vencimento' => 'required|integer',
            ];
    }
    public function feedback() {
        return [
            'required' => 'O campo :attribute deve ser preenchido!',
            'valor.decimal' => 'O campo valor deve ser um número com no máximo 2 casas decimais.',
            'valor.numeric' => 'O campo valor deve ser um número válido.',
            'valor.min' => 'O campo valor não pode ser zerado.',
            'data_vencimento.integer' => 'O campo data vencimento deve ser um número inteiro.',
            'data_vencimento.required' => 'O campo data vencimento é obrigatório.',
        ];
    }


}
