<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $primaryKey = "matricula";

    protected $table = "professores";

    public $incrementing = false;

    public $timestamps = false; 

    protected $fillable = [
        'nome', 
        'matricula',
        'disciplina',
        'quantidade_orientacoes', 
        'email', 
        'status', 
        'descricao'
    ];
}
