<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $primaryKey = "matricula";

    protected $table = "usuario";

    public $incrementing = false;

    public $timestamps = false; 

    protected $fillable = [
        'matricula',
        'senha',
        'permissao', 
    ];

    protected $hidden = [
        'senha'
    ];
}
