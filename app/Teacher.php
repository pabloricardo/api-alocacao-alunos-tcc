<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $fillable = [
        'type',
        'studentLimit',
        'userId',
    ];

    /**
     * Table teacher relationship with user
     */
    public function users()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Table teacher relationship with student
     */
    public function students()
    {
        return $this->hasMany('App\Student', 'teacherId');
    }
}