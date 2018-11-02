<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $primaryKey = "id_area";

    protected $table = "area";

    public $timestamps = false;

    protected $fillable = [
        "nome_da_area"
    ];
}
