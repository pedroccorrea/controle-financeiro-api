<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recebimento extends Model
{
    /** @use HasFactory<\Database\Factories\RecebimentoFactory> */
    use HasFactory;

    protected $fillable = ['user_id', 'nome', 'valor'];
}
