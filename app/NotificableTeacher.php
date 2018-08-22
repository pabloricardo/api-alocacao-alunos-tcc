<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class NotificableTeacher extends Model
{
    protected $fillable = [
        'teacherGuide',
        'answered',
        'teacherId',
        'studentId',
        'areaId',
    ];

    /**
     * Table teachers relationship with user
     */
    public function teachers()
    {
        return $this->hasMany('App\Teacher', 'teacherId');
    }

    /**
     * Table student relationship with user
     */
    public function students()
    {
        return $this->hasMany('App\Student', 'studentId');
    }

    /**
     * Table student relationship with user
     */
    public function areas()
    {
        return $this->hasMany('App\Area', 'areaId');
    }
}
