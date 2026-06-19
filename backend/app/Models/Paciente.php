<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'nome',
    'data_nascimento',
    'cpf',
    'sexo',
    'cep',
    'cidade',
    'endereco',
    'complemento',
    'status',
])]
class Paciente extends Model
{
    use HasFactory;

    protected $casts = [
        'data_nascimento' => 'date',
    ];
}
