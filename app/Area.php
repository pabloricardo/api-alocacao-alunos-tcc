<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $fillable = [
        'name'
    ];

    /**
     * Table teacher relationship with user
     */
    public function ocupationArea()
    {
        return $this->belongsTo('App\OcupationArea', 'areaId');
    }

    /**
     * Table teacher relationship with user
     */
    public function notificableTeacher()
    {
        return $this->belongsTo('App\NotificableTeacher', 'areaId');
    }
}
