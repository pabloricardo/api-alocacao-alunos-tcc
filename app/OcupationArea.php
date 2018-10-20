<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class OcupationArea extends Model
{
    public $incrementing = false;

    public $timestamps = false; 

    protected $table = "area_professores";

    protected $fillable = [
        'id_area',
        'matricula'
    ];

   /**
     * Table teachers relationship with user
     */
    public function teachers()
    {
        return $this->hasMany('App\Teacher', 'teacherId');
    }

    /**
     * Table teachers relationship with user
     */
    public function areas()
    {
        return $this->hasMany('App\Area', 'areaId');
    }
}
