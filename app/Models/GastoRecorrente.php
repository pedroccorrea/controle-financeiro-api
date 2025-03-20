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
                'data_vencimento' => 'required|date',
            ];
    }
    public function feedback() {
        return [
            'required' => 'O campo :attribute deve ser preenchido!',
            'valor.decimal' => 'O campo valor deve ser um número com no máximo 2 casas decimais.',
            'date' => 'O campo :attribute deve ser uma data válida.',
        ];
    }


}
